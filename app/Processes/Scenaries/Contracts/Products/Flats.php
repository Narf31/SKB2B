<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsCalculation;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcFlats;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class Flats {

    public static function save(Contracts $contract, $data){


        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' '.$data->begin_time);
        $contract->end_date = setDateTimeFormat($data->end_date);

        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, 'insurer', $contract->insurer_id)->id;
        }

        if(isset($data->beneficiar)){
            if(isset($data->beneficiar['is_insurer']) && (int)$data->beneficiar['is_insurer'] == 1){
                $contract->beneficiar_id = $contract->insurer_id;
            }else{
                $beneficiar_id = $contract->beneficiar_id;
                if($beneficiar_id == $contract->insurer_id){
                    $beneficiar_id = 0;
                }
                $contract->beneficiar_id = Subjects::saveOrCreateOnlineSubject((object)$data->beneficiar, 'beneficiar', $beneficiar_id)->id;
            }
        }

        if(isset($data->object)){
            $object = ContractObject::update_or_create_flats($contract->object_insurer_id, (object)$data->object);
            $contract->object_insurer_id = $object->id;
        }

        if(isset($data->risks)){
            $programs = $data->risks['programs'];
            $risks = [];
            foreach ($programs as $key => $val){
                if($val == 1){
                    $risks[] = "$key";
                }
            }

            $calculation = $contract->calculation;
            if(!$calculation){
                $calculation = ContractsCalculation::create(['contract_id' => $contract->id]);
            }

            $calculation->risks = \GuzzleHttp\json_encode($risks);
            $calculation->save();

        }



        if($contract->save()){
            return true;
        }

        return false;

    }


    public static function calc(Contracts $contract)
    {
        return CalcFlats::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {
        $insurer = $contract->insurer;
        $insurer_doc = '';
        if($insurer->type == 0){
            $insurer_doc = Subjects::DOC_TYPE[$insurer->get_info()->doc_type]." серия {$insurer->get_info()->doc_serie} номер {$insurer->get_info()->doc_number}";
        }else{
            $insurer_doc = "ИНН {$insurer->get_info()->inn} КПП {$insurer->get_info()->kpp}";
        }


        $data = [
            'sign_date' => setDateTimeFormatRu($contract->sign_date, 1),
            'begin_date' => setDateTimeFormatRu($contract->begin_date, 1),
            'end_date' => setDateTimeFormatRu($contract->end_date, 1),
            'payment_total' => titleFloatFormat($contract->payment_total),
            'insurance_amount' => titleFloatFormat($contract->insurance_amount),
            'bso_title' => $contract->bso->bso_title,
            'product_title' => $contract->product->title,

        ];

        return $data;
    }

    public static function copy(Contracts $contract){

        $new_contract = ContractCreate::create($contract->product, $contract->agent_id);

        $subject = Subjects::getSubjectContract($new_contract, 'insurer');
        GeneralSubjectsSearch::clone_general($contract->insurer->general_subject_id, $subject, $contract->insurer->doc_type_id);
        $object = ContractObject::update_or_create_flats($new_contract->object_insurer_id, (object)$contract->object_insurer_flats);
        ContractsCalculation::create(['contract_id' => $new_contract->id, 'risks'=>$contract->calculation->risks]);

        $new_contract->update([
            'is_prolongation' => $contract->is_prolongation,
            'installment_algorithms_id' => $contract->installment_algorithms_id,
            'object_insurer_id' => $object->id,
        ]);


        return $new_contract;

    }

}