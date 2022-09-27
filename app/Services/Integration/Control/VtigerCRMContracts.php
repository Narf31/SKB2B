<?php

namespace App\Services\Integration\Control;

use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Clients\GeneralPodftFl;
use App\Models\Clients\GeneralPodftUl;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Clients\GeneralSubjectsUl;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsSupplementary;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\Products;
use App\Models\Finance\Invoice;
use App\Models\Settings\Bank;
use App\Models\Settings\PaymentMethods;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Processes\Scenaries\Contracts\Products\LiabilityArbitrationManager;
use function GuzzleHttp\Psr7\str;
use Mockery\Exception;

class VtigerCRMContracts
{

    const product_id = 3;


    public function getDataInfo($start, $request)
    {
        if(isset($request) && isset($request->delete) && $request->delete == 1){

            BsoItem::where('product_id', self::product_id)->delete();
            Contracts::where('product_id', self::product_id)->delete();
            ContractsSupplementary::where('product_id', self::product_id)->delete();
            Payments::where('is_export', 1)->delete();
            Products\Data\LiabilityArbitrationManager::query()->delete();
            \DB::table('products_supplementary_liability_arbitration_manager')->truncate();


        }

        return VtigerCRMSend::getDataInfo('contracts', "view=info");
    }

    public function updateDataInfo($start, $counts, $request, $count_all)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "VtigerCRM не настроен!";

        $response = VtigerCRMSend::getDataInfo('contracts', "view=updata&start={$start}&counts={$counts}&count_all={$count_all}");
        if($response->result){

            $this->setData($response->result);
            $res->state = $response->state;
            $res->msg = $response->msg;
            $res->progressbar = $response->progressbar;
            $res->start = $response->start;

        }

        return $res;
    }

    public function connectionDataInfo()
    {
        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "Не связаны";



        return $res;
    }


    private function setData($datas)
    {

        $product = Products::find(self::product_id);



        foreach ($datas as $data){


            $user = VtigerCRMUsers::getExportUserId($data->user_id);
            $agent_id = 1;
            if($user){
                $agent_id = $user->id;
            }

            $bso_title = $data->bso_title;

            $_exp_bso = explode('_', $bso_title);
            $_t_bso = $_exp_bso[0];
            $bso_title = $_t_bso;

            $bso_dop_serie = mb_stristr($_t_bso,'-');
            if($bso_dop_serie && strlen($bso_dop_serie) > 0){
                $_t_bso = str_replace($bso_dop_serie, '', $_t_bso);
                $bso_dop_serie = substr($bso_dop_serie, 1);
            }

            $bso_number = preg_replace('/[^0-9]/', '', $_t_bso);
            $bso_serie = str_replace($bso_number, '', $_t_bso);


            $bso = self::getBSO($bso_title, $bso_serie, $bso_number, $bso_dop_serie, $user);

            if(!$bso->contract){
                //Создаем договор
                $contract = ContractCreate::create($product, $agent_id);
                $contract->bso_id = $bso->id;
                $contract->bso_title = $bso->bso_title;
                $contract->statys_id = 4;
                $contract->sign_date = ($data->sign_date?$data->sign_date:$data->begin_date);
                $contract->begin_date = $data->begin_date;
                $contract->end_date = $data->end_date;
                $contract->insurance_amount = getFloatFormat($data->insurance_amount);
                $contract->payment_total = getFloatFormat($data->contract_payment_total);
                if($data->contract_type == 'Пролонгация'){
                    $contract->is_prolongation = 1;
                }


                $contract->financial_policy_id = 0;
                $contract->financial_policy_manually_set = 1;
                $contract->financial_policy_kv_dvoy = 0;
                $contract->financial_policy_kv_bordereau = (
                    getFloatFormat($data->manager_kv_1)+
                    getFloatFormat($data->manager_kv_2)+
                    getFloatFormat($data->manager_kv_3)+
                    getFloatFormat($data->manager_kv_4)+
                    getFloatFormat($data->manager_kv_5)+
                    getFloatFormat($data->agent_kv_1)+
                    getFloatFormat($data->agent_kv_2)+
                    getFloatFormat($data->agent_kv_3)+
                    getFloatFormat($data->agent_kv_4)+
                    getFloatFormat($data->agent_kv_5)+
                    getFloatFormat($data->agent_kv_6)
                );





                $arbitration = $contract->data;

                //1 => 'Годовой',
                //2 => 'Процедурный',

                $arbitration->type_agr_id = 1;
                $arbitration->sign_date = $contract->sign_date;
                $arbitration->begin_date = $contract->begin_date;
                $arbitration->end_date = $contract->end_date;
                $arbitration->export_data = \GuzzleHttp\json_encode($data);
                $arbitration->count_current_procedures = 1;

                $arbitration->manager_payment_total = $contract->payment_total;
                $arbitration->manager_tariff = getPriceToTotalSum($contract->insurance_amount, $contract->payment_total);

                $general_insurer = VtigerCRMGeneralFL::getExportGeneralId($data->general_insurer_id);
                if($general_insurer && $general_insurer->id){
                    $arbitration->general_insurer_id = $general_insurer->id;
                }else{
                    $_tG = new \stdClass();
                    $_tG->title = "{$data->general_f_name} {$data->general_i_name} {$data->general_o_name}";
                    $_tG->birthdate = $data->general_birthday;
                    $_tG->sex = 0;
                    if(mb_substr($data->general_f_name, -1) == 'а') $_tG->sex = 1;
                    $hash = GeneralSubjectsInfo::getHash(0, $_tG);
                    $general = GeneralSubjectsSearch::search_hash(0, $hash);
                    if($general){
                        $arbitration->general_insurer_id = $general->id;
                    }
                }

                if(strlen($data->cro_title) > 0){
                    $general_cro = GeneralSubjects::where("title", $data->cro_title)->get()->first();
                    if($general_cro){
                        $arbitration->cro_id = $general_cro->id;
                    }
                }

                if($arbitration->general_insurer_id > 0){
                    $subjects = Subjects::where('general_subject_id', $arbitration->general_insurer_id)->get()->first();
                    if(!$subjects){
                        $subjects = GeneralSubjectsSearch::get_general_to_subject($contract->data->general_insurer);
                    }
                    $contract->insurer_id = $subjects->id;
                }
                $arbitration->save();
                $contract->save();

                $calculation_res = $contract->calculation;
                if(!$calculation_res){
                    $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
                }

            }else{
                $contract = $bso->contract;
                $arbitration = $contract->data;
            }

            $payment_data = getDateFormatEn($data->payment_data);
            $payment_total = getFloatFormat($data->payment_total);
            $insurance_amount = getFloatFormat($data->insurance_amount);

            $supplementary = null;
            //доп соглашение
            if(isset($_exp_bso[1]) || $data->contract_type == 'Доп.соглашение'){
                //Доп соглашение
                $arbitration->type_agr_id = 2;
                $arbitration->save();
                $supplementary = LiabilityArbitrationManager::createSupplementary($contract);
                $supplementary->sign_date = setDateTimeFormat($data->sign_date.' 00:00:00');
                $supplementary->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
                $supplementary->end_date = setDateTimeFormat($data->end_date.' 23:59:59');
                $supplementary->status_id = 4;
                $supplementary->data()->update([

                    'manager_tariff' => getPriceToTotalSum($insurance_amount, $payment_total),
                    'manager_payment_total' => getFloatFormat($payment_total),
                    'export_data' =>  \GuzzleHttp\json_encode($data),

                ]);

                $supplementary->insurance_amount = 0;
                $supplementary->payment_total = getFloatFormat($payment_total);

                $supplementary->financial_policy_id = 0;
                $supplementary->financial_policy_manually_set = 1;
                $supplementary->financial_policy_kv_dvoy = 0;
                $supplementary->financial_policy_kv_bordereau = (
                    getFloatFormat($data->manager_kv_1)+
                    getFloatFormat($data->manager_kv_2)+
                    getFloatFormat($data->manager_kv_3)+
                    getFloatFormat($data->manager_kv_4)+
                    getFloatFormat($data->manager_kv_5)+
                    getFloatFormat($data->agent_kv_1)+
                    getFloatFormat($data->agent_kv_2)+
                    getFloatFormat($data->agent_kv_3)+
                    getFloatFormat($data->agent_kv_4)+
                    getFloatFormat($data->agent_kv_5)+
                    getFloatFormat($data->agent_kv_6)
                );

                $supplementary->save();

            }




            $payment = $contract->payments()->where('payment_data', $payment_data)->where('payment_total', $payment_total)->get()->first();
            if(!$payment){
                $payment_number = $contract->payments()->count()+1;
                $payment = PaymentsCreate::create($payment_number, $contract, $payment_total, $payment_data, ["month" => 0, "payment" => "100"], true);
                $payment->bso_id = $bso->id;
                $payment->is_export = 1;
                if($supplementary){
                    $payment->supplementary_id = $supplementary->id;
                }
            }

            $method = PaymentMethods::findOrFail(11);
            $payment->payment_method_id = $method->id;
            $payment->payment_type = $method->payment_type;
            $payment->payment_flow = $method->payment_flow;
            $payment->save();

            $i_type = Invoice::searchType($payment->payment_type, $payment->payment_flow);

            $invoice = Invoice::create([
                'user_id' => $payment->agent_id,
                'status_id' => 1,
                'type' => $i_type->TYPES,
                'create_type' => 1,
                'org_id' => $payment->bso->supplier->purpose_org_id,
                'agent_id' => $payment->agent_id,
                'type_invoice_payment_id' => $i_type->TYPE_INVOICE_PAYMENT,
                'invoice_payment_total' => $payment->invoice_payment_total,
                'invoice_payment_date' => getDateTime(),
                'payment_method_id' => $payment->payment_method_id,
                'md5_token' => $payment->contract->md5_token,
                'client_email' => null,
                'client_info' => null,
                'client_type' => null,
            ]);

            $payment->invoice_id = $invoice->id;
            $payment->save();

            InvoiceAutomatic::closeInvoice($invoice, $payment_data, $payment_total);


            //dd($contract);








            //dd($data);



        }
        return true;
    }



    private function getBSO($bso_title, $bso_serie, $bso_number, $bso_dop_serie, $user)
    {

        //Базовые данные
        $type_bso_id = 3;
        $insurance_companies_id = 1;
        $bso_class_id = 1;
        $product_id = self::product_id;
        $bso_count_number = 9;

        $serie = BsoSerie::where('insurance_companies_id', $insurance_companies_id)
            ->where('bso_class_id', $bso_class_id)
            ->where('product_id', $product_id)
            ->where('type_bso_id', $type_bso_id)
            ->where('bso_serie', $bso_serie)->get()->first();

        if(!$serie){
            $serie = BsoSerie::create([
                'type_bso_id' => $type_bso_id,
                'bso_class_id' => $bso_class_id,
                'insurance_companies_id' => $insurance_companies_id,
                'product_id' => $product_id,
                'bso_serie' => $bso_serie,
                'bso_count_number' => $bso_count_number,
            ]);
        }


        $dop_serie = null;

        if($bso_dop_serie && strlen($bso_dop_serie) > 0){
            //Создаем доп серию

            $dop_serie = BsoDopSerie::where('insurance_companies_id', $insurance_companies_id)
                ->where('bso_serie_id', $serie->id)
                ->where('type_bso_id', $type_bso_id)
                ->where('bso_dop_serie', $bso_dop_serie)->get()->first();
            if(!$dop_serie){
                $dop_serie = BsoDopSerie::create([
                    'type_bso_id' => $type_bso_id,
                    'insurance_companies_id' => $insurance_companies_id,
                    'bso_serie_id' => $serie->id,
                    'bso_dop_serie' => $bso_dop_serie,
                ]);
            }

        }


        if($user){
            $agent = $user;
            $organization = $user->organization;
            if($organization->parent_user) $agent = $organization->parent_user;
        }else{
            $agent = User::find(1);
        }

        $bso_supplier = BsoSuppliers::findOrFail(1);

        $bso = BsoItem::where('bso_supplier_id', $bso_supplier->id)
                        ->where('bso_serie_id', $serie->id)
                        ->where('bso_number', $bso_number);
        if($dop_serie){
            $bso->where('bso_dop_serie_id', $dop_serie->id);
        }


        $bso = $bso->get()->first();
        if($bso){
            return $bso;
        }

        $bso_serie = $serie;


        //Создаем БСО и вешаем на Агента
        $bso = BsoItem::create([
            'bso_supplier_id' => $bso_supplier->id,
            'org_id' => $bso_supplier->id,
            'insurance_companies_id' => $bso_supplier->insurance_companies_id,
            'bso_class_id' => $bso_serie->bso_class_id,
            'type_bso_id' => $bso_serie->type_bso_id,
            'product_id' => $bso_serie->product_id,
            'bso_serie_id' => $bso_serie->id,
            'bso_number' => $bso_number,
            'bso_title' => $bso_title,
            'location_id' => 4,
            'state_id' => 0,
            'agent_id' => $agent->id,
            'agent_organization_id' => $agent->organization->id,
            'user_id' => 0,
            'user_org_id' => $agent->organization->id,
            'time_create' => date('Y-m-d H:i:s'),
            'time_target' => date('Y-m-d H:i:s'),
            'last_operation_time' => date('Y-m-d H:i:s'),
            'transfer_to_agent_time' => date('Y-m-d H:i:s'),
            'transfer_to_org_time' => date('Y-m-d H:i:s'),
            'bso_manager_id' => (int)auth()->id(),
            'point_sale_id' => (int)$agent->point_sale_id,
            'bso_dop_serie_id' => ($dop_serie?$dop_serie->id:0),
        ]);



        BsoLogs::setLogs(
            $bso->id,
            $bso->state_id,
            $bso->location_id,
            0,
            0,
            0,
            0,
            0,
            0
        );



        return $bso;
    }



}