<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsInsurer;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcFlats;
use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Operations\Contracts\Products\CalcMortgage;
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class Mortgage {

    public static function save(Contracts $contract, $data){


        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $insurance_term = (int)$data->data['insurance_term'];

        $end_date = date('Y-m-d 00:00:00', strtotime("+{$insurance_term} month {$contract->begin_date}"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));

        $contract->end_date = $end_date;


        if(isset($data->is_prolongation)){
            $contract->is_prolongation = $data->is_prolongation;
        }

        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
            $contract->beneficiar_id = $contract->insurer_id;
        }

        $object = (object)$data->object;

        $contract->data()->update([
            'address' => $object->address,
            'address_kladr' => $object->address_kladr,
            'address_fias' => $object->address_fias,
            'address_region' => $object->address_region,
            'address_city' => $object->address_city,
            'address_city_kladr_id' => $object->address_city_kladr_id,
            'address_street' => $object->address_street,
            'address_house' => $object->address_house,
            'address_block' => $object->address_block,
            'address_flat' => $object->address_flat,
            'address_latitude' => $object->address_latitude,
            'address_longitude' => $object->address_longitude,

            'official_discount' => getFloatFormat($data->data['official_discount']),
            'is_transition' => $data->data['is_transition'],
            'bank_id' => (int)$data->data['bank_id'],
            'type_realty' => (int)$data->data['type_realty'],
            'class_realty' => (int)$data->data['class_realty'],

            'credit_term' => getFloatFormat($data->data['credit_term']),
            'loan_rate' => getFloatFormat($data->data['loan_rate']),

            'is_life' => isset($data->data['is_life']) ? 1 : 0,


            'profession' => $data->data['profession'],
            'type_health_deviation' => $data->data['type_health_deviation'],
            'health_deviation' => isset($data->data['health_deviation']) ? $data->data['health_deviation'] : '',

            'type_sport' => $data->data['type_sport'],
            'sport' => isset($data->data['sport']) ? $data->data['sport'] : '',

            'is_property' => isset($data->data['is_property']) ? 1 : 0,

            'area' => getFloatFormat($data->data['area']),
            'year_construction' => (int)$data->data['year_construction'],

            'is_combustible_material' => isset($data->data['is_combustible_material']) ? 1 : 0,
            'is_availability_repair' => isset($data->data['is_availability_repair']) ? 1 : 0,
            'is_repair_work_progress' => isset($data->data['is_repair_work_progress']) ? 1 : 0,


            'is_title' => isset($data->data['is_title']) ? 1 : 0,
            'document_owner' => $data->data['document_owner'],

            'type_ownership_restriction' => $data->data['type_ownership_restriction'],
            'ownership_restriction' => isset($data->data['ownership_restriction']) ? $data->data['ownership_restriction'] : '',

            'price' => getFloatFormat($data->data['price']),
            'title_period' => (int)getFloatFormat($data->data['title_period']),

            'is_deal_proxy' => isset($data->data['is_deal_proxy']) ? 1 : 0,
            'is_owners_age' => isset($data->data['is_owners_age']) ? 1 : 0,
            'is_object_owner_age' => isset($data->data['is_object_owner_age']) ? 1 : 0,
            'is_owner_ul' => isset($data->data['is_owner_ul']) ? 1 : 0,
            'is_owner_payment' => isset($data->data['is_owner_payment']) ? 1 : 0,
            'is_not_agreement' => isset($data->data['is_not_agreement']) ? 1 : 0,


        ]);


        $contract->contracts_insurers()->delete();

        if(isset($data->insurers)){
            foreach ($data->insurers as $insurer){
                if(isset($insurer['title'])){
                    ContractsInsurer::create([
                        'contract_id' => $contract->id,
                        'title' => $insurer['title'],
                        'birthdate' => getDateFormatEn($insurer['birthdate']),
                        'sex' => $insurer['sex'],
                        'share' => getFloatFormat($insurer['share']),
                        'citizenship_id' => 51,
                        'birthyear' => (date("Y")-date("Y", strtotime($insurer['birthdate']))),
                    ]);
                }
            }

        }

        if(isset($data->insurance_amount)){
            $contract->insurance_amount = getFloatFormat($data->insurance_amount);
        }

        if($contract->save()){
            return true;
        }

        return false;

    }


    public static function calc(Contracts $contract)
    {
        return CalcMortgage::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {



        return null;
    }

    public static function copy(Contracts $contract, $is_contract_id = null){

        if($is_contract_id && (int)$is_contract_id > 0){
            $new_contract = Contracts::find($is_contract_id);
        }else{
            $new_contract = ContractCreate::create($contract->product, $contract->program, $contract->agent_id);
        }

        $new_contract->insurance_amount = $contract->insurance_amount;


        $subject = Subjects::cloneSubject($contract->insurer);
        $new_contract->insurer_id = $subject->id;
        $new_contract->owner_id = $new_contract->insurer_id;
        $new_contract->beneficiar_id = $new_contract->insurer_id;


        $data = $contract->data->replicate();
        $data->contract_id = $new_contract->id;

        $new_contract->data->update($data->toArray());

        foreach ($contract->contracts_insurers as $insurer)
        {
            $insurer = $insurer->replicate();
            $insurer->contract_id = $new_contract->id;
            $new_contract->contracts_insurers()->create($insurer->toArray());
        }


        $new_contract->update([
            'begin_date' => $contract->begin_date,
            'end_date' => $contract->end_date,
            'is_prolongation' => $contract->is_prolongation,
            'installment_algorithms_id' => $contract->installment_algorithms_id,
        ]);


        return $new_contract;

    }

}