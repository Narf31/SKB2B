<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsInsurer;
use App\Models\Settings\Country;
use App\Models\Settings\CurrencyValue;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcOsago;

class Osago {

    public static function save(Contracts $contract, $data){

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }

        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');

        $end_date = date('Y-m-d 00:00:00', strtotime("+1 year {$contract->begin_date}"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));

        $contract->end_date = $end_date;

        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
            $contract->beneficiar_id = $contract->insurer_id;
        }

        if(isset($data->owner)){
            if(isset($data->owner['is_insurer']) && (int)$data->owner['is_insurer'] == 1){
                $contract->owner_id = $contract->insurer_id;
            }else{
                $owner_id = 0;
                if((int)$contract->owner_id > 0 && $contract->owner_id!=$contract->insurer_id){
                    $owner_id = $contract->owner_id;
                }
                $contract->owner_id = Subjects::saveOrCreateOnlineSubject((object)$data->owner, $owner_id, $contract->agent_id)->id;
            }
        }

        $is_multidriver = 0;
        if(isset($data->insurers['is_multidriver']) && $data->insurers['is_multidriver'] == 1){
            $is_multidriver = 1;
        }

        $osago = (object)$data->osago;
        $contract->data()->update([
            'is_epolicy' => $osago->is_epolicy,
            'is_multidriver' => $is_multidriver,
            'period_beg1' => getDateFormatEn($osago->period_beg1),
            'period_end1' => getDateFormatEn($osago->period_end1),
            'period_beg2' => getDateFormatEn($osago->period_beg2),
            'period_end2' => getDateFormatEn($osago->period_end2),
            'period_beg3' => getDateFormatEn($osago->period_beg3),
            'period_end3' => getDateFormatEn($osago->period_end3),

        ]);

        $contract->contracts_insurers()->delete();

        if(isset($data->insurers)){
            foreach ($data->insurers as $key => $insurer){

                if(is_int($key)){

                    $subject_id = 0;
                    if(isset($insurer['is_insurer']) && $insurer['is_insurer'] == 1){
                        $subject_id = $contract->insurer_id;
                    }
                    if(isset($insurer['is_owner']) && $insurer['is_owner'] == 1){
                        $subject_id = $contract->owner_id;
                    }


                    ContractsInsurer::create([
                        'contract_id' => $contract->id,
                        'title' => $insurer['title'],
                        'birthdate' => getDateFormatEn($insurer['birthdate']),
                        'sex' => $insurer['sex'],
                        'birthyear' => (date("Y")-date("Y", strtotime($insurer['birthdate']))),
                        'doc_type' => $insurer['doc_type'],
                        'doc_serie' => $insurer['doc_serie'],
                        'doc_number' => $insurer['doc_number'],
                        'doc_date' => getDateFormatEn($insurer['doc_date']),
                        'exp_date' => getDateFormatEn($insurer['exp_date']),
                        'expyear' => (date("Y")-date("Y", strtotime($insurer['exp_date']))),
                        'subject_id' => $subject_id,
                    ]);
                }
            }
        }


        $object = (object)$data->object;
        $object_insurer = $contract->object_insurer;
        $auto = $object_insurer->data();
        $auto->ts_category = (int)$object->ts_category;
        $auto->mark_id = (int)$object->mark_id;
        $auto->model_id = (int)$object->model_id;
        $auto->model_classification_code = $object->model_classification_code;
        $auto->car_year = (int)$object->car_year;
        $auto->purpose_id = (int)$object->purpose_id;
        $auto->vin = $object->vin;
        $auto->body_number = $object->body_number;
        $auto->body_chassis = $object->body_chassis;
        $auto->country_id = (int)$object->country_id;
        $auto->type_reg_number = (int)$object->type_reg_number;
        $auto->reg_number = $object->reg_number;
        $auto->power = getFloatFormat($object->power);
        $auto->powerkw = getFloatFormat($object->powerkw);
        $auto->weight = getFloatFormat($object->weight);
        $auto->capacity = getFloatFormat($object->capacity);
        $auto->passengers_count = (int)$object->passengers_count;
        $auto->is_trailer = (isset($object->is_trailer))?(int)$object->is_trailer:0;
        $auto->doc_type = (int)$object->doc_type;
        $auto->docserie = $object->docserie;
        $auto->docnumber = $object->docnumber;
        $auto->docdate = getDateFormatEn($object->docdate);
        $auto->dk_number = $object->dk_number;
        $auto->dk_date_from = getDateFormatEn($object->dk_date_from);
        $auto->dk_date_to = getDateFormatEn($object->dk_date_to);
        $auto->save();

        $object_insurer->title = '';

        if($auto->mark){
            $object_insurer->title .= "{$auto->mark->title} ";
        }
        if($auto->model){
            $object_insurer->title .= "{$auto->model->title} ";
        }
        $object_insurer->title .= "{$auto->car_year}";
        $object_insurer->save();

        if($contract->save()){
            return true;
        }

        return false;

    }


    public static function calc(Contracts $contract)
    {
        return CalcOsago::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {


        return null;
    }



    public static function getInsurersVal($insurers, $key, $val){

        if(isset($insurers[$key])){

            if($val == 'birthdate'){
                return setDateTimeFormatRu($insurers[$key][$val], 1);
            }

            return $insurers[$key][$val];
        }

        return '';
    }


    public static function copy(Contracts $contract){
        $new_contract = ContractCreate::create($contract->product, $contract->program, $contract->agent_id);

        $subject = Subjects::cloneSubject($contract->insurer);
        $new_contract->insurer_id = $subject->id;

        $subject_o = Subjects::cloneSubject($contract->owner);
        $new_contract->owner_id = $subject_o->id;


        $data = $contract->data->replicate();
        $data->contract_id = $new_contract->id;


        $new_contract->data->update($data->toArray());

        $object_insurer = $contract->object_insurer;

        $new_object_insurer = $contract->object_insurer->replicate();
        $new_object_insurer->save();

        $auto = $object_insurer->data()->replicate();
        $auto->object_insurer_id = $new_object_insurer->id;
        $auto->save();


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
            'object_insurer_id' => $new_object_insurer->id,
        ]);


        return $new_contract;
    }

}