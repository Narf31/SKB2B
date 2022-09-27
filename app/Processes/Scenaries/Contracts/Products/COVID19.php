<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsInsurer;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Products\CalcFlats;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class COVID19 {

    public static function save(Contracts $contract, $data){


        $sign_date = getDateTime();
        $begin_date = date('Y-m-d 00:00:00', strtotime("+1 day"));
        $end_date = date('Y-m-d 00:00:00', strtotime("+6 month $begin_date"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));

        $contract->sign_date = $sign_date;
        $contract->begin_date = $begin_date;
        $contract->end_date = $end_date;


        $contract->is_prolongation = 0;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id)->id;
        }

        $contract->beneficiar_id = $contract->insurer_id;


        $contract->contracts_insurers()->delete();

        $insurers = (object)$data->insurers;
        ContractsInsurer::create([
            'contract_id' => $contract->id,
            'title' => $insurers->title,
            'birthdate' => getDateFormatEn($insurers->birthdate),
            'sex' => $insurers->sex,
        ]);

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = ContractsCalculation::create(['contract_id' => $contract->id]);
        }

        $calculation->save();


        if($contract->save()){
            return true;
        }

        return false;

    }



    public static function calc(Contracts $contract){

        $calculation = $contract->calculation;

        $insurance_amount = 50000;
        $payment_total = 50;

        $contract->insurance_amount = $insurance_amount;
        $contract->payment_total = $payment_total;

        $contract->save();

        return (boolean)$calculation->createPaymentCalc();
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

        $insurers = $contract->contracts_insurers()->first();


        $data = [
            'sign_date' => setDateTimeFormatRu($contract->sign_date, 1),
            'begin_date' => setDateTimeFormatRu($contract->begin_date, 1),
            'end_date' => setDateTimeFormatRu($contract->end_date, 1),
            'payment_total' => titleFloatFormat($contract->payment_total),
            'insurance_amount' => titleFloatFormat($contract->insurance_amount),
            'bso_title' => $contract->bso->bso_title,
            'product_title' => $contract->product->title,
            'insurer_title' => $insurer->title,
            'insurer_doc' => $insurer_doc,
            'insurer_address' => $insurer->get_info()->address_register,
            'insurers_title' => $insurers->title,
            'insurers_birthdate' => setDateTimeFormatRu($insurers->birthdate, 1),
        ];

        return $data;
    }


    public static function copy(Contracts $contract){

        $new_contract = ContractCreate::create($contract->product, $contract->agent_id);

        $subject = Subjects::getSubjectContract($new_contract, 'insurer');
        GeneralSubjectsSearch::clone_general($contract->insurer->general_subject_id, $subject, $contract->insurer->doc_type_id);

        $insurers = $contract->contracts_insurers()->first();
        ContractsInsurer::create([
            'contract_id' => $new_contract->id,
            'title' => $insurers->title,
            'birthdate' => getDateFormatEn($insurers->birthdate),
            'sex' => $insurers->sex,
        ]);

        $new_contract->update([
            'is_prolongation' => $contract->is_prolongation,
            'installment_algorithms_id' => $contract->installment_algorithms_id,
        ]);

        return $new_contract;

    }

}