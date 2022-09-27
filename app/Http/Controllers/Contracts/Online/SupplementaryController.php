<?php

namespace App\Http\Controllers\Contracts\Online;

use App\Domain\Process;
use App\Domain\Processes\Operations\Contracts\Online\ContractCalculationOperations;
use App\Domain\Samplers\Segments\SegmentsOnRequest;
use App\Interfaces\Services\SK\ProductServiceInterface;
use App\Models\Api\SK\ApiSetting;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Driver;
use App\Models\Contracts\KbmCalculation;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\ProductsPrograms;
use App\Processes\Operations\Contracts\Contract\ContractMatching;
use App\Processes\Operations\Contracts\Payments\PaymentDiscounts;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Processes\Scenaries\Contracts\OnlineContractSave;
use App\Services\Kbm\Alfa\Calc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\TypeBso;
use App\Models\Directories\ProductsCategory;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\Contracts;
use Illuminate\Support\Str;
use Mockery\Exception;

class SupplementaryController extends Controller {

    public function __construct() {
        $this->breadcrumbs[] = [
            'label' => 'Оформление',
            'url' => 'contracts/online'
        ];
    }


    public function create($id, Request $request)
    {

        $contract = Contracts::getContractId($id);

        $result = OnlineContractSave::createSupplementary($contract);
        //Если ошибка
        if($result->state == false){
            return back()->withInput()->withErrors($result->errors);
        }

        return redirect("/contracts/online/{$contract->id}/supplementary/{$result->id}");
    }

    public function edit($id, $number, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $supplementary = $contract->getSupplementaryNumber($number);

        $this->breadcrumbs[] = [
            'url' => $contract->id,
            'label' => $contract->bso->bso_title,
        ];

        $this->breadcrumbs[] = [
            'label' => "Доп. соглашение {$supplementary->title}",
        ];


        return view('contracts.online.supplementary.edit', [
            'contract' => $contract,
            'supplementary' => $supplementary,
            'type' => $supplementary->getViewStateOnline(),
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function save($id, $number, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $supplementary = $contract->getSupplementaryNumber($number);
        $data = (object)$request->contract;

        return OnlineContractSave::saveSupplementary($contract, $supplementary, $data);

    }

    public function delete($id, $number, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $supplementary = $contract->getSupplementaryNumber($number);
        if($supplementary && $supplementary->status_id == 0){
            if($supplementary->data){
                $supplementary->data->delete();
            }
            $supplementary->delete();
        }


        return response('', 200);

    }

    public function setEdit($id, $number, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $supplementary = $contract->getSupplementaryNumber($number);
        if($supplementary && $supplementary->status_id == 2){
            if($supplementary->matching && $supplementary->matching->status_id == 2){
                $supplementary->status_id = 0;
                $supplementary->save();
            }
        }


        return redirect("/contracts/online/{$contract->id}/supplementary/{$number}");
    }


    public function release($id, $number, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $supplementary = $contract->getSupplementaryNumber($number);

        if($supplementary && $supplementary->status_id == 2 && $supplementary->matching && $supplementary->matching->status_id == 4){
            //СОЗДАЕМ ПЛАТЕЖ
            $payment_first = $contract->payments()->where('statys_id', 1)->where('is_deleted', 0)->get()->first();
            if($payment_first){

                $payment = Payments::create([
                    'statys_id' => 0,
                    'type_id' => 0,
                    'bso_id' => $contract->bso_id,
                    'payment_number' => $contract->payments()->where('statys_id', 1)->where('is_deleted', 0)->count()+1,
                    'payment_data' => date('Y-m-d'),
                    'payment_total' => $supplementary->payment_total,
                    'installment_algorithms_payment' => $payment_first->installment_algorithms_payment,

                    'contract_id' => $contract->id,

                    'agent_organization_id' => $contract->agent_organization_id,
                    'agent_id' => $contract->agent_id,
                    'agent_parent_id' => $contract->agent_parent_id,
                    'agent_curator_id' => $contract->agent_curator_id,

                    'official_discount' => 0,
                    'informal_discount' => 0,
                    'bank_kv' => 0,

                    'supplementary_id' => $supplementary->id,

                    'financial_policy_id' => $supplementary->financial_policy_id,
                    'financial_policy_manually_set' => $supplementary->financial_policy_manually_set,
                    'financial_policy_kv_bordereau' => $supplementary->financial_policy_kv_bordereau,
                    'financial_policy_kv_dvoy' => $supplementary->financial_policy_kv_dvoy,

                ]);

                PaymentDiscounts::recount($payment);

                $supplementary->status_id = 3;
                $supplementary->save();

                return redirect("/contracts/online/{$contract->id}/payment/{$payment->id}/");

            }
        }

        return abort(303);
    }





}
