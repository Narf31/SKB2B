<?php

namespace App\Http\Controllers\Contracts\Online;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Contracts\ActionsScansController;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\ProductsPrograms;
use App\Processes\Operations\Contracts\Contract\ContractAccept;
use App\Processes\Operations\Contracts\Contract\ContractCancel;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Contract\ContractDocuments;
use App\Processes\Operations\Contracts\Contract\ContractMasks;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Validation\ViewForm\ValidationFormController;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use App\Processes\Scenaries\Contracts\OnlineContractSave;
use App\Services\Integration\VernaControllers\VernaPayment;
use App\Services\PaymentAgent\IntegrationPaymentAgent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsCategory;
use App\Models\Contracts\Contracts;

class OnlineController extends Controller {

    public function __construct() {
        $this->middleware('permissions:contracts,online');
        $this->breadcrumbs[] = [
            'label' => 'Оформление',
            'url' => 'contracts/online'
        ];
    }

    public function index(Request $request) {


        $products = Products::where('is_actual', '1')->whereIn('id', auth()->user()->getProductsSale())->orderBy('category_id');
        $categories = ProductsCategory::whereIn('id', $products->distinct('category_id')->pluck('category_id')->toArray())->orderBy('sort', 'asc')->get();





        return view('contracts.online.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function create($product_id, Request $request) {



        $product = Products::where('is_actual', '1')->where('id', '=', (int)$product_id)->first();

        if (!$product) {
            return redirect("/contracts/online/");
        }

        $program = null;
        if(isset($request->program) && (int)$request->program > 0){
            $program = ProductsPrograms::where('is_actual', '1')->where('product_id', $product->id)->where('id', '=', (int)$request->program)->first();
        }


        $contract = ContractCreate::create($product, $program, auth()->id());
        return redirect("/contracts/online/{$contract->id}");
    }


    /**
     * Редактирование расчета
     * @param $id
     */
    public function edit($id)
    {



        $contract = Contracts::getContractId($id);
        $_lName = $contract->product->title;
        if(isset($contract->program)) $_lName = "{$contract->product->title} - {$contract->program->title}";

        $this->breadcrumbs[] = [
            'label' => $_lName,
        ];


        return view('contracts.online.edit', [
            'contract' => $contract,
            'type' => $contract->getViewStateOnline(),
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function save($id, Request $request)
    {

        $contract = Contracts::getContractId($id);
        $data = (object)$request->contract;
        return response()->json((object)OnlineContractSave::handle($contract, $data));
    }


    public function calc($id, Request $request)
    {

        $result = (object)['state'=> false, 'msg' => '', 'html' => ''];

        $data = (object)$request->contract;
        $contract = Contracts::getContractId($id);

        $res = OnlineContractSave::handle($contract, $data);
        $res = (object)$res;

        //проверка/валидация
        if($contract->program && $contract->program->slug != 'calculator'){
            $res = ValidationFormController::main(Contracts::find($contract->id));
        }

        if(!$contract->program){
            $res = ValidationFormController::main(Contracts::find($contract->id));
        }

        if($res->state == true){

            $contract = Contracts::getContractId($id);
            $contract->matching_num = 0;
            $contract->save();
            if($contract->matching_underwriter){
                $matching = $contract->matching_underwriter;
                $matching->status_id = 2;
                $matching->save();
            }

            if(OnlineContractSave::calc($contract)){



                $result->html = '';

                $view = 'contracts.default.tariff.edit';
                if(\View::exists("contracts.default.tariff.products.{$contract->product->slug}")){
                    $view = "contracts.default.tariff.products.{$contract->product->slug}";
                }

                $contract = Contracts::getContractId($id);
                ActionsScansController::checkExistenceOfRequiredDocuments($contract);
                $result->html = view($view, ['contract'=>Contracts::getContractId($id)])->render();

                $result->state = true;

            }

        }else{
            $result = $res;
        }




        return response()->json($result);
    }

    public function setCalculation($id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $calculation = ContractsCalculation::where('contract_id', $contract->id)->where('id', (int)$request->calc)->get()->first();
        $contract->payment_total = $calculation->sum;
        $contract->program_id = $calculation->program_id;
        $contract->insurance_amount = $calculation->insurance_amount;
        $contract->save();
        $calculation->state_calc = 1;
        $calculation->is_actual = 1;
        $calculation->save();

        return response('',200);
    }

    public function release($id)
    {
        $contract = Contracts::getContractId($id);

        if(ContractDocuments::checkDocuments($contract) == false){
            return abort(303, 'Заполните обязательные документы!');
        }

        //ОПРЕДЕЛЯЕМ НУЖНО ЛИ СОГЛАСОВЫВАТЬ ДОГОВОР
        if(MatchingsContract::check($contract) == false){
            return parentReload();
        }

        if(!auth()->user()->hasPermission('contracts', 'send_release')){
            return abort(303, 'Вам запрешено выпускать договор, обратитесь к руководителю!');
        }

        if(($contract->statys_id == 0 || $contract->statys_id == 2 || $contract->statys_id == 3) || !$contract->bso){


            if($contract->statys_id == 2 && $contract->calculation && $contract->calculation->matching){
                if($contract->calculation->matching->status_id == 5){
                    return abort(303, 'Данный договор запрещен для выпуска!');
                }
            }

            //Проверяем все ли заполнено сканы и тд

            $sk_products_payment = $contract->getFinancialGroupPayment();

            $hold_kv = $contract->bso_supplier->hold_kv_product($contract->product_id);

            if($hold_kv){

                $_v = 'default';
                if(\View::exists("contracts.online.release.{$contract->product->slug}")){
                    $_v = $contract->product->slug;
                }

                $bso_serie = BsoSerie::where('product_id', $contract->product->id)
                    ->where('bso_class_id', 0)
                    ->get();


                return view("contracts.online.release.{$_v}", [
                    'contract' => $contract,
                    'sk_products_payment' => $sk_products_payment,
                    'hold_kv' => $hold_kv,
                    'bso_serie' => $bso_serie,
                ]);
            }
            return abort(303, 'Не настроен продукт');
        }


        return abort(303, 'Данный договор уже подтвержден');
    }


    public function accept($id, Request $request)
    {

        $contract = Contracts::getContractId($id);
        $data = (object)$request->contract;
        $data = (object)$data->payment;

        $result = ContractAccept::accept($contract, $data);
        return response()->json($result);
    }


    public function payment($id, $payment_id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $payment = $contract->payments()->find($payment_id);

        if($payment->statys_id == 1){
            abort(303);
        }

        if($payment->invoice_id > 0 && !isset($request->edit)){

            return view('contracts.online.payment_info', [
                'contract' => $contract,
                'payment' => $payment,
            ]);
        }

        $sk_products_payment = $contract->getFinancialGroupPayment();

        return view('contracts.online.payment', [
            'contract' => $contract,
            'sk_products_payment' => $sk_products_payment,
            'payment' => $payment,
        ]);


    }

    public function payment_accept($id, $payment_id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $payment = $contract->payments()->find($payment_id);
        $data = (object)$request->contract;
        $data = (object)$data->payment;

        $result = PaymentsAccept::accept($contract, $payment, $data);
        if($result->state == true && $contract->statys_id != 4){
            //$contract->statys_id = 4;
            //$contract->save();
        }

        return response()->json($result);
    }


    public function payment_check_status($id, $payment_id, Request $request)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = 'Платеж не оплачен!';

        $contract = Contracts::getContractId($id);
        $payment = $contract->payments()->find($payment_id);
        if($payment->payment_method->key_type == 4 && $payment->payment_method->control_type == 0)
        {
            $result->state = IntegrationPaymentAgent::checkBillingPage($payment->invoice);
        }

        if($payment->statys_id == 1){
            $result->state = true;
            $result->msg = '';
        }

        return response()->json($result);
    }


    public function copy($id, Request $request)
    {
        return OnlineContractSave::copy(Contracts::getContractId($id));
    }

    public function prolongation($id, Request $request)
    {
        $is_contract_id = null;
        if(isset($request->is_id) && $request->is_id > 0) $is_contract_id = $request->is_id;

        return OnlineContractSave::prolongation(Contracts::getContractId($id), $is_contract_id);
    }



    public function editStatus($id, Request $request)
    {
        $contract = Contracts::getContractId($id);

        $res = ['state'=> false, 'msg' => 'Не удалось изменить договор.', 'id' => ''];

        if($contract->statys_id == 2 && $contract->calculation){

            if(MatchingsContract::checkStatus($contract) == true){
                $contract->statys_id = 0;
                $contract->save();
                ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Cогласования', 'Редактирование договора');

                $res = ['state'=> true, 'msg' => '', 'id' => $contract->id];
            }


        }
        if($contract->statys_id == 3){
            $payment = $contract->getPaymentsFirstInvoiceLink();
            if($payment)
            {
                if($payment->payment_method){
                    if($payment->payment_method->key_type == 4 && $payment->payment_method->control_type == 1){
                        IntegrationPaymentAgent::checkBillingPage($payment->invoice);
                    }

                }
                if($payment->statys_id != 1){

                    $payment->invoice->delete();
                    $payment->delete();
                    $contract->calculation->delete();
                    $contract->statys_id = 0;
                    $contract->save();
                    ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Изменения договора', 'Редактирование договора');
                    $res = ['state'=> true, 'msg' => '', 'id' => $contract->id];
                }

            }else{
                $contract->statys_id = 0;
                $contract->save();
                ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Изменения договора', 'Редактирование договора');
                $res = ['state'=> true, 'msg' => '', 'id' => $contract->id];
            }


        }

        return response()->json($res);
    }




    public function cancel_contract($id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $check = ContractCancel::check($contract);

        if($check->state == false){
            abort(303, $check->msg);
        }



        return view('contracts.online.cancel', [
            'contract' => $contract,
        ]);
    }

    public function delete_contract($id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $result = ContractCancel::delete($contract, (array)$request->bso_states);
        if($result->state == false){
            abort(303, $result->msg);
        }
        return parentReload();
    }


    public function refreshMask($id){
        $contract = Contracts::getContractId($id);

        ContractMasks::contract($contract);

        return response('', 200);
    }


    public function getDraftTable(Request $request)
    {

        $drafts = Contracts::getContracts([0])->orderBy('created_at', 'desc')->orderBy('updated_at', 'desc')
            ->where('agent_id', auth()->id());

        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = 25;

        $result = PaginationHelper::paginate($drafts, $page, $page_count);
        $drafts = $result['builder']->get();


        return [
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'html' => view('contracts.online.draft_table', [
                'drafts' => $drafts,
            ])->render(),
        ];

    }


    public function deleteDraft(Request $request)
    {

        $items = $request->items;

        foreach ($items as $contract_id){
            ContractCancel::deleteDraft($contract_id);
        }



        return response('', 200);
    }



}
