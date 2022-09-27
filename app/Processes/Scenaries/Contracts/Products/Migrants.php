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
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class Migrants {

    public static function save(Contracts $contract, $data){

        $migrants = (object)$data->migrants;

        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $contract->end_date = date('Y-m-d 23:59:59', strtotime("+{$migrants->date_month} month {$contract->begin_date}"));

        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
            $contract->beneficiar_id = $contract->insurer_id;
        }



        $contract->data()->update([
            'address_register' => $migrants->address_register,
            'address_register_kladr' => $migrants->address_register_kladr,
            'address_register_fias_code' => $migrants->address_register_fias_code,
            'address_register_fias_id' => $migrants->address_register_fias_id,
            'date_register' => $migrants->date_register,
            'date_month' => $migrants->date_month,
            'programs_id' => $migrants->programs_id,
            'ns' => (isset($migrants->ns)?$migrants->ns:0),
            'pregnancy' => (isset($migrants->pregnancy)?$migrants->pregnancy:0),
            'clinical_examination' => (isset($migrants->clinical_examination)?$migrants->clinical_examination:0),
            'dental_care' => (isset($migrants->dental_care)?$migrants->dental_care:0),
            'interment' => (isset($migrants->interment)?$migrants->interment:0),
            'transportation' => (isset($migrants->transportation)?$migrants->transportation:0),
        ]);


        $contract->contracts_insurers()->delete();

        if(isset($data->insurers)){
            $insurer = $data->insurers;

            ContractsInsurer::create([
                'contract_id' => $contract->id,
                'title' => $insurer['title'],
                'birthdate' => getDateFormatEn($insurer['birthdate']),
                'sex' => $insurer['sex'],
                'phone' => $insurer['phone'],
                'email' => $insurer['email'],
                'doc_type' => $insurer['doc_type'],
                'doc_serie' => $insurer['doc_serie'],
                'doc_number' => $insurer['doc_number'],
                'doc_date' => getDateFormatEn($insurer['doc_date']),
                'doc_info' => $insurer['doc_info'],
                'citizenship_id' => (isset($insurer['citizenship_id'])?(int)$insurer['citizenship_id']:0),
                'birthyear' => (date("Y")-date("Y", strtotime($insurer['birthdate']))),
            ]);
        }


        if($contract->save()){
            return true;
        }

        return false;

    }


    public static function calc(Contracts $contract)
    {
        return CalcMigrants::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {

        $subject = $contract->insurer;
        $subject_data = $subject->get_info();
        $insurer_lat = '';
        if(isset($subject_data->fio_lat)){
            $insurer_lat = mb_strtoupper($subject_data->fio_lat);
        }

        $result = \GuzzleHttp\json_decode($contract->calculation->json);

        $data = [
            'sign_date' => setDateTimeFormatRu($contract->sign_date, 1),
            'begin_date' => setDateTimeFormatRu($contract->begin_date, 1),
            'end_date' => setDateTimeFormatRu($contract->end_date, 1),
            'payment_total' => titleFloatFormat($contract->payment_total),
            'bso_title' => $contract->bso->bso_title,
        ];

        return $data;
    }

    public static function copy(Contracts $contract){

        $new_contract = ContractCreate::create($contract->product, $contract->agent_id);


        $subject = Subjects::getSubjectContract($new_contract, 'insurer');
        GeneralSubjectsSearch::clone_general($contract->insurer->general_subject_id, $subject, $contract->insurer->doc_type_id);

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