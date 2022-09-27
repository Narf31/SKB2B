<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsInsurer;
use App\Models\Contracts\ContractsSupplementary;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Products\CalcFlats;
use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class LiabilityArbitrationManager {

    public static function save(Contracts $contract, $data){

        $arbitration = (object)$data->liability_arbitration_manager;

        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $contract->end_date = setDateTimeFormat($data->end_date.' 23:59:59');

        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';
        $contract->prolongation_bso_title = $data->prolongation_bso_title;


        $contract->insurance_amount = getFloatFormat($data->insurance_amount);



        $contract->data()->update([
            'cro_id' => $arbitration->cro_id,
            'general_insurer_id' => $arbitration->general_insurer_id,
            'procedure_id' => (isset($arbitration->procedure_id)?$arbitration->procedure_id:null),
            'type_agr_id' => $arbitration->type_agr_id,
            'count_current_procedures' => $arbitration->count_current_procedures,
            'base_tariff' => getFloatFormat($arbitration->base_tariff),

            'base_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($arbitration->base_tariff))),

            'original_tariff' => getFloatFormat($arbitration->original_tariff),
            'original_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($arbitration->original_tariff))),

            'manager_tariff' => getFloatFormat($arbitration->manager_tariff),
            'manager_payment_total' => getFloatFormat($arbitration->manager_payment_total),

            'kv_agent_id' => (int)$arbitration->kv_agent_id,
            'kv_agent' => getFloatFormat($arbitration->kv_agent),

            'kv_agent2_id' => (int)$arbitration->kv_agent2_id,
            'kv_agent2' => getFloatFormat($arbitration->kv_agent2),

            'kv_manager_id' => (int)$arbitration->kv_manager_id,
            'kv_manager' => getFloatFormat($arbitration->kv_manager),

            'kv_manager2_id' => (int)$arbitration->kv_manager2_id,
            'kv_manager2' => getFloatFormat($arbitration->kv_manager2),

            'kv_manager3_id' => (int)$arbitration->kv_manager3_id,
            'kv_manager3' => getFloatFormat($arbitration->kv_manager3),

            'sign_date' => $contract->sign_date,
            'begin_date' => $contract->begin_date,
            'end_date' => $contract->end_date,

        ]);


        if($arbitration->general_insurer_id > 0){

            $subjects = Subjects::where('general_subject_id', $arbitration->general_insurer_id)->get()->first();
            if(!$subjects){
                $subjects = GeneralSubjectsSearch::get_general_to_subject($contract->data->general_insurer);
            }
            $contract->insurer_id = $subjects->id;
        }


        $contract->payment_total = getFloatFormat($arbitration->manager_payment_total);

        $contract->financial_policy_id = 0;
        $contract->financial_policy_manually_set = 1;
        $contract->financial_policy_kv_dvoy = 0;
        $contract->financial_policy_kv_bordereau = (
            getFloatFormat($arbitration->kv_agent)+
            getFloatFormat($arbitration->kv_agent2)+
            getFloatFormat($arbitration->kv_manager)+
            getFloatFormat($arbitration->kv_manager2)+
            getFloatFormat($arbitration->kv_manager3)
        );

        if($contract->save()){
            //Создаем платежи
            if(PaymentsCreate::save($contract)){
                return true;
            }

        }

        return false;

    }


    public static function calc(Contracts $contract)
    {
        return CalcMigrants::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {

        /*

        $subject = $contract->insurer;
        $subject_data = $subject->get_info();
        $insurer_lat = '';
        if(isset($subject_data->fio_lat)){
            $insurer_lat = mb_strtoupper($subject_data->fio_lat);
        }

        */


        return null;
    }

    public static function copy(Contracts $contract){

        $new_contract = ContractCreate::create($contract->product, $contract->agent_id);

        $subject = Subjects::cloneSubject($contract->insurer);
        $new_contract->insurer_id = $subject->id;
        $new_contract->insurance_amount = getFloatFormat($contract->insurance_amount);
        $new_contract->save();

        $data = $contract->data->replicate();
        $data->contract_id = $new_contract->id;
        $new_contract->data->update($data->toArray());

        return $new_contract;

    }

    public static function createSupplementary(Contracts $contract){

        $number = $contract->supplementary()->count()+1;

        $supplementary = ContractsSupplementary::create([
            'contract_id' => $contract->id,
            'product_id' => $contract->product_id,
            'status_id' => 0,
            'number_id' => $number,
            'title' => "{$contract->bso->bso_title}-$number",
            'insurance_amount' => 0,
            'payment_total' => 0,
            'sign_date' => getDateTime(),
            'begin_date' => $contract->end_date,
        ]);

        $data = $supplementary->data()->create(['supplementary_id'=>$supplementary->id]);
        $data->update([
            'kv_agent_id' => $contract->data->kv_agent_id,
            'kv_agent2_id' => $contract->data->kv_agent2_id,
            'kv_manager_id' => $contract->data->kv_manager_id,
            'kv_manager2_id' => $contract->data->kv_manager2_id,
            'kv_manager3_id' => $contract->data->kv_manager3_id,
            'kv_agent' => $contract->data->kv_agent,
            'kv_agent2' => $contract->data->kv_agent2,
            'kv_manager' => $contract->data->kv_manager,
            'kv_manager2' => $contract->data->kv_manager2,
            'kv_manager3' => $contract->data->kv_manager3,
        ]);

        return $supplementary;
    }

    public static function saveSupplementary(Contracts $contract, $supplementary, $data){

        $arbitration = (object)$data->liability_arbitration_manager;

        $supplementary->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $supplementary->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $supplementary->end_date = setDateTimeFormat($data->end_date.' 23:59:59');
        $supplementary->save();

        $supplementary->data()->update([
            'base_tariff' => getFloatFormat($arbitration->base_tariff),
            'base_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($arbitration->base_tariff))),

            'original_tariff' => getFloatFormat($arbitration->original_tariff),
            'original_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($arbitration->original_tariff))),

            'manager_tariff' => getFloatFormat($arbitration->manager_tariff),
            'manager_payment_total' => getFloatFormat($arbitration->manager_payment_total),

            'kv_agent_id' => (int)$arbitration->kv_agent_id,
            'kv_agent' => getFloatFormat($arbitration->kv_agent),

            'kv_agent2_id' => (int)$arbitration->kv_agent2_id,
            'kv_agent2' => getFloatFormat($arbitration->kv_agent2),

            'kv_manager_id' => (int)$arbitration->kv_manager_id,
            'kv_manager' => getFloatFormat($arbitration->kv_manager),

            'kv_manager2_id' => (int)$arbitration->kv_manager2_id,
            'kv_manager2' => getFloatFormat($arbitration->kv_manager2),

            'kv_manager3_id' => (int)$arbitration->kv_manager3_id,
            'kv_manager3' => getFloatFormat($arbitration->kv_manager3),


        ]);

        $supplementary->insurance_amount = 0;
        $supplementary->payment_total = getFloatFormat($arbitration->manager_payment_total);

        $supplementary->financial_policy_id = 0;
        $supplementary->financial_policy_manually_set = 1;
        $supplementary->financial_policy_kv_dvoy = 0;
        $supplementary->financial_policy_kv_bordereau = (
            getFloatFormat($arbitration->kv_agent)+
            getFloatFormat($arbitration->kv_agent2)+
            getFloatFormat($arbitration->kv_manager)+
            getFloatFormat($arbitration->kv_manager2)+
            getFloatFormat($arbitration->kv_manager3)
        );

        $supplementary->save();


        return true;

    }




}