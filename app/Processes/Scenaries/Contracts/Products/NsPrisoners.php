<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsInsurer;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcNSPrisoners;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class NsPrisoners {

    public static function save(Contracts $contract, $data){

        $ns_prisoners = (object)$data->ns_prisoners;

        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));
        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');
        $insurance_term = (int)$ns_prisoners->insurance_term;

        $end_date = date('Y-m-d 00:00:00', strtotime("+{$insurance_term} month {$contract->begin_date}"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));

        $contract->end_date = $end_date;



        $contract->is_prolongation = $data->is_prolongation;
        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';
        $contract->prolongation_bso_title = $data->prolongation_bso_title;

        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
            $contract->beneficiar_id = $contract->insurer_id;
        }


        $contract->data()->update([
            'address_born' => $ns_prisoners->address_born,
            'address_born_kladr' => $ns_prisoners->address_born_kladr,
            'address_born_fias_code' => $ns_prisoners->address_born_fias_code,
            'address_born_fias_id' => $ns_prisoners->address_born_fias_id,


            'address_location' => $ns_prisoners->address_location,
            'address_location_kladr' => $ns_prisoners->address_location_kladr,
            'address_location_fias_code' => $ns_prisoners->address_location_fias_code,
            'address_location_fias_id' => $ns_prisoners->address_location_fias_id,

            'convicted_under_articles' => $ns_prisoners->convicted_under_articles,

            'convicted_term' => $ns_prisoners->convicted_term,
            'convicted_term_contract' => $ns_prisoners->convicted_term_contract,

            'is_chronic_diseases' => (isset($ns_prisoners->is_chronic_diseases)?1:0),
            'chronic_diseases' => (isset($ns_prisoners->is_chronic_diseases)?$ns_prisoners->chronic_diseases:''),

            'is_disabilities' => (isset($ns_prisoners->is_disabilities)?1:0),
            'disabilities' => (isset($ns_prisoners->is_disabilities)?$ns_prisoners->disabilities:''),

            'insurance_term' => (int)$ns_prisoners->insurance_term,
            'insurance_amount_ns' => (int)$ns_prisoners->insurance_amount_ns,

            'is_tuberculosis' => (isset($ns_prisoners->is_tuberculosis)?1:0),
            'insurance_amount_tuberculosis' => (isset($ns_prisoners->is_tuberculosis)?(int)$ns_prisoners->insurance_amount_tuberculosis:0),

            'official_discount' => getFloatFormat($data->data['official_discount']),

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
        return CalcNSPrisoners::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {

        return null;

        $subject = $contract->insurer;
        $subject_data = $subject->get_info();

        $result = \GuzzleHttp\json_decode($contract->calculation->json);

        $insurer = $contract->insurer;
        $insurer_data = $insurer->get_info();

        $insurers = $contract->contracts_insurers[0];

        $data = $contract->data;



        $chronic_diseases = '';
        if((int)$data->is_chronic_diseases == 1){
            $chronic_diseases = $data->chronic_diseases;
        }

        $disabilities = '';
        if((int)$data->is_disabilities == 1){
            $disabilities = $data->disabilities;
        }


        $ns_amount = titleFloatFormat($result->info[0]->insurance_amount);
        $ns_payment_total = titleFloatFormat($result->info[0]->payment_total);

        $tb_amount = '';
        $tb_payment_total = '';

        if(isset($result->info[1])){
            $tb_amount = titleFloatFormat($result->info[1]->insurance_amount);
            $tb_payment_total = titleFloatFormat($result->info[1]->payment_total);
        }


        if($contract->bso){
            $bso = $contract->bso;
        }else{
            $bso = new \stdClass();
            $bso->bso_title = '';
            $bso->bso_serie = new \stdClass();
            $bso->bso_serie->bso_serie = '';
            $bso->bso_number = '';
        }


        $insurers_documents = 'Паспорт в деле';

        $insurers_pasport_number = "";
        $insurers_pasport_date = '';
        $insurers_pasport_info = "";

        if(strlen($insurers->doc_serie) > 0 && strlen($insurers->doc_number) > 0){
            $insurers_documents = "{$insurers->doc_serie} {$insurers->doc_number}";

            $insurers_pasport_number = "{$insurers->doc_serie} {$insurers->doc_number}";
            $insurers_pasport_date = setDateTimeFormatRu($insurers->doc_date, 1);
            $insurers_pasport_info = "{$insurers->doc_info}";

        }

        $insurer_pasport_number = "{$insurer_data->doc_serie} {$insurer_data->doc_number}";
        $insurer_pasport_date = setDateTimeFormatRu($contract->doc_date, 1);
        $insurer_pasport_info = "{$insurer_data->doc_info}";

        $data = [

            'settings' => [
                'template' => 0,
                'template_contract' => 1,
                'template_statement' => 1,
            ],
            'info' => [
                'sign_date' => setDateTimeFormatRu($contract->sign_date, 1),
                'begin_date' => setDateTimeFormatRu($contract->begin_date, 1),
                'end_date' => setDateTimeFormatRu($contract->end_date, 1),
                'payment_total' => titleFloatFormat($contract->payment_total),
                'insurance_amount' => titleFloatFormat($contract->insurance_amount),
                'bso_title' => $bso->bso_title,
                'bso_serie' => $bso->bso_serie->bso_serie,
                'bso_number' => $bso->bso_number,

                'insurer_name' => $insurer->title,
                'insurer_birthdate' => setDateTimeFormatRu($insurer_data->birthdate, 1),
                'insurer_citizenship' => $insurer_data->citizenship->title_ru,
                'insurer_address_born' => $insurer_data->address_born,
                'insurer_documents' => "{$insurer_data->doc_serie} {$insurer_data->doc_number}, {$insurer_data->doc_info}, ".setDateTimeFormatRu($contract->doc_date, 1),

                'insurer_pasport_number' => $insurer_pasport_number,
                'insurer_pasport_date' => $insurer_pasport_date,
                'insurer_pasport_info' => $insurer_pasport_info,

                'insurer_phone' => $insurer->phone,
                'insurer_email' => $insurer->email,


                'insurer_address_register' => $insurer_data->address_register,

                'insurers_name' => $insurers->title,
                'insurers_birthdate' => setDateTimeFormatRu($insurers->birthdate, 1),
                'insurers_citizenship' => $insurers->citizenship->title_ru,
                'insurers_address_born' => $data->address_born,
                'insurers_documents' => $insurers_documents,

                'insurers_pasport_number' => $insurers_pasport_number,
                'insurers_pasport_date' => $insurers_pasport_date,
                'insurers_pasport_info' => $insurers_pasport_info,


                'convicted_under_articles' => $data->convicted_under_articles,
                'convicted_term' => $data->convicted_term,
                'address_location' => $data->address_location,

                'convicted_term_contract' => \App\Models\Directories\Products\Data\NSPrisoners::CONVICTED_TERM_CONTRSCT[$data->convicted_term_contract],
                'is_tuberculosis' => ($data->is_tuberculosis == 1)?"Да. ":"Нет. ",

                'is_chronic_diseases' => ($data->is_chronic_diseases == 1)?"Да. ":"Нет. ",
                'chronic_diseases' => ($data->is_chronic_diseases == 1)?$data->chronic_diseases:"",
                'is_disabilities' => ($data->is_disabilities == 1)?"Да. ":"Нет. ",
                'disabilities' => ($data->is_disabilities == 1)?$data->disabilities:"",

                'count_month' => $data->count_month,


                'ns_amount' => $ns_amount,
                'ns_payment_total' => $ns_payment_total,

                'tb_amount' => $tb_amount,
                'tb_payment_total' => $tb_payment_total,
            ],



        ];

        return $data;
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