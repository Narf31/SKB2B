<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsInsurer;
use App\Models\Settings\Country;
use App\Models\Settings\CurrencyValue;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcFlats;
use App\Processes\Operations\Contracts\Products\CalcPrf;
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class Prf {

    public static function save(Contracts $contract, $data){


        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $contract->end_date = setDateTimeFormat($data->end_date);

        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
            $contract->beneficiar_id = $contract->insurer_id;
        }


        $prf = (object)$data->prf;
        $amount = $prf->amount;
        


        $contract->data()->update([
            'count_day' => $prf->count_day,
            'programs_id' => $prf->programs,
            'amount' => $amount,

            'ns_program' => $prf->ns_program,
            'ns_amount' => $prf->ns_amount,

            'is_leisure' => (isset($prf->is_leisure)?$prf->is_leisure:0),
            'is_chronic_diseases' => (isset($prf->is_chronic_diseases)?$prf->is_chronic_diseases:0),
            'is_pregnancy' => (isset($prf->is_pregnancy)?$prf->is_pregnancy:0),
            'is_science' => (isset($prf->is_science)?$prf->is_science:0),
            'is_children' => (isset($prf->is_children)?$prf->is_children:0),
            'is_alcohol' => (isset($prf->is_alcohol)?$prf->is_alcohol:0),
            'is_covid19' => (isset($prf->is_covid19)?$prf->is_covid19:0),

        ]);


        $contract->contracts_insurers()->delete();

        if(isset($data->insurers)){
            foreach ($data->insurers as $key => $insurer){

                if(is_int($key)){
                    ContractsInsurer::create([
                        'contract_id' => $contract->id,
                        'title' => $insurer['title'],
                        'citizenship_id' => (int)$insurer['citizenship_id'],
                        'birthdate' => getDateFormatEn($insurer['birthdate']),
                        'sex' => $insurer['sex'],
                        'birthyear' => (date("Y")-date("Y", strtotime($insurer['birthdate']))),
                    ]);
                }
            }
        }


        if($contract->save()){
            return true;
        }

        return false;

    }


    public static function calc(Contracts $contract)
    {
        return CalcPrf::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {

        return null;

    }


    public static function getDataProgram($calcs, $program, $data, $currency_title){
        $result = new \stdClass();
        $result->amount = '';
        $result->payment = '';
        $result->prog = '';

        foreach ($calcs as $calc){
            if($calc->title == $program){
                $result->amount = titleFloatFormat($calc->insurance_curr_amount).' '.$currency_title;
                $result->payment = titleFloatFormat($calc->payment_curr_total).' '.$currency_title;

                if($program == 'Однократный'){
                    $result->prog = \App\Models\Directories\Products\Data\VZR::PROGRAMS[1][$data->programs_id];
                }
                if($program == 'Годовой'){
                    $result->prog = \App\Models\Directories\Products\Data\VZR::PROGRAMS[2][$data->programs_id];
                }

                if($program == 'Отмена поездки'){
                    $result->prog = \App\Models\Directories\Products\Data\VZR::CANCEL_TRIP_PROGRAM[$data->cancel_trip_program];
                }

                if($program == 'Задержка рейса'){
                    $result->prog = \App\Models\Directories\Products\Data\VZR::FLIGHT_DELAY_PROGRAM[$data->flight_delay_program];
                }
            }
        }

        return $result;

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

        $new_contract = ContractCreate::create($contract->product, $contract->agent_id);

        $subject = Subjects::cloneSubject($contract->insurer);
        $new_contract->insurer_id = $subject->id;

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