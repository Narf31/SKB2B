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
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;

class Vzr {

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


        $vzr = (object)$data->vzr;

        $сountry_json = null;
        $сountry_id = 0;
        if(isset($vzr->сountry_json) && is_array($vzr->сountry_json)){
            $сountry_json = \GuzzleHttp\json_encode($vzr->сountry_json);
        }


        $amount = $vzr->amount[$vzr->type_agr_id];

        if(isset($vzr->is_schengen) && $vzr->is_schengen == 1){
            $amount = $vzr->amount[3];
        }

        $dates = [];
        if(isset($vzr->dates)){
            $dates =  $vzr->dates;
        }


        $contract->data()->update([
            'count_day' => $vzr->count_day,
            'type_agr_id' => $vzr->type_agr_id,
            'сountry_id' => $сountry_id,
            'сountry_json' => $сountry_json,
            'day_to' => $vzr->day_to,
            'programs_id' => $vzr->programs[$vzr->type_agr_id],
            'amount' => $amount,
            'currency_id' => $vzr->currency_id,

            'flight_delay_program' => $vzr->flight_delay_program,
            'flight_delay_amount' => $vzr->flight_delay_amount,
            'missed_flight_program' => $vzr->missed_flight_program,
            'missed_flight_amount' => $vzr->missed_flight_amount,
            'baggage_program' => $vzr->baggage_program,
            'baggage_amount' => $vzr->baggage_amount,
            'сivil_responsibility_program' => $vzr->сivil_responsibility_program,
            'сivil_responsibility_amount' => $vzr->сivil_responsibility_amount,
            'legal_aid_program' => $vzr->legal_aid_program,
            'legal_aid_amount' => $vzr->legal_aid_amount,
            'cancel_tour_program' => $vzr->cancel_tour_program,
            'cancel_tour_amount' => $vzr->cancel_tour_amount,
            'ns_program' => $vzr->ns_program,
            'ns_amount' => $vzr->ns_amount,
            'cancel_trip_program' => $vzr->cancel_trip_program,

            'sport_id' => $vzr->sport_id,
            'profession_id' => $vzr->profession_id,

            'dates' => \GuzzleHttp\json_encode($dates),

            'is_leisure' => (isset($vzr->is_leisure)?$vzr->is_leisure:0),
            'is_chronic_diseases' => (isset($vzr->is_chronic_diseases)?$vzr->is_chronic_diseases:0),
            'is_pregnancy' => (isset($vzr->is_pregnancy)?$vzr->is_pregnancy:0),
            'is_science' => (isset($vzr->is_science)?$vzr->is_science:0),
            'is_children' => (isset($vzr->is_children)?$vzr->is_children:0),
            'is_alcohol' => (isset($vzr->is_alcohol)?$vzr->is_alcohol:0),
            'is_schengen' => (isset($vzr->is_schengen)?$vzr->is_schengen:0),
            'is_covid19' => (isset($vzr->is_covid19)?$vzr->is_covid19:0),

            'franchise_id' => $vzr->franchise_id,


        ]);


        $contract->contracts_insurers()->delete();

        if(isset($data->insurers)){
            foreach ($data->insurers as $key => $insurer){

                if(is_int($key)){
                    ContractsInsurer::create([
                        'contract_id' => $contract->id,
                        'title_lat' => $insurer['title_lat'],
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
        return CalcVzr::calc($contract);
    }


    public static function getPrintData(Contracts $contract)
    {

        $insurer = $contract->insurer;
        $insurer_data = $insurer->get_info();
        $insurer_lat = '';
        if(isset($insurer_data->fio_lat)){
            $insurer_lat = mb_strtoupper($insurer_data->fio_lat);
        }

        $currency_title = $contract->data->currency->title;
        $currency_val = CurrencyValue::getActualValue($contract->data->currency->id, $contract->sign_date);
        if($currency_val){
            $currency_total = titleFloatFormat($currency_val->amount);
        }

        $result = \GuzzleHttp\json_decode($contract->calculation->json);



        if($contract->bso){
            $bso = $contract->bso;
        }else{
            $bso = new \stdClass();
            $bso->bso_title = '';
            $bso->bso_serie = new \stdClass();
            $bso->bso_serie->bso_serie = '';
            $bso->bso_number = '';
        }

        $country = '';
        if(strlen($contract->data->сountry_json) > 0){
            $сountry_json = \GuzzleHttp\json_decode($contract->data->сountry_json);
            foreach ($сountry_json as $сountry_id){
                $_сountry = Country::findOrFail($сountry_id);
                $schengen = '';
                if($_сountry->is_schengen == 1){
                    $schengen = 'SCHENGEN, ';
                }
                $country .= $schengen.mb_strtoupper($_сountry->title_en).'; ';
            }
        }




        $insurers = $contract->contracts_insurers->toArray();


        $assist = $contract->product->assistance_info()
            ->whereIn('country_id', [0, $contract->data->сountry_id])
            ->orderBy('country_id', 'desc')
        ->get()->first();


        $assistance = '';
        $assistance_coments = '';
        if($assist){
            $assistance = "{$assist->title}, {$assist->phone}";
            $assistance_coments = $assist->comments;
        }


        $vzr_fran = '';
        if($contract->data->franchise_id > 0){
            $vzr_fran = \App\Models\Directories\Products\Data\VZR::FRANCHISE[$contract->data->franchise_id];
        }



        if($contract->data->type_agr_id == 1){
            $_vzr = self::getDataProgram($result->info, 'Однократный', $contract->data, $currency_title);
        }else{
            $_vzr = self::getDataProgram($result->info, 'Годовой', $contract->data, $currency_title);
        }


        $_ns = self::getDataProgram($result->info, 'Несчастный случай', $contract->data, $currency_title);
        $_ct = self::getDataProgram($result->info, 'Отмена поездки', $contract->data, $currency_title);
        $_cr = self::getDataProgram($result->info, 'Гражданская ответственность', $contract->data, $currency_title);
        $_la = self::getDataProgram($result->info, 'Юридическая помощь', $contract->data, $currency_title);
        $_b = self::getDataProgram($result->info, 'Багаж', $contract->data, $currency_title);
        $_fd = self::getDataProgram($result->info, 'Задержка рейса', $contract->data, $currency_title);
        $_mf = self::getDataProgram($result->info, 'Опоздание на рейс', $contract->data, $currency_title);
        $_ctr = self::getDataProgram($result->info, 'Отмена экскурсии', $contract->data, $currency_title);





        $data = [
            'settings' => [
                'template' => 0,
                'template_contract' => 1,
                'template_statement' => 0,
            ],
            'info' => [
                'sign_date' => setDateTimeFormatRu($contract->sign_date, 1),
                'begin_date' => setDateTimeFormatRu($contract->begin_date, 1),
                'end_date' => setDateTimeFormatRu($contract->end_date, 1),
                'payment_total' => titleFloatFormat($contract->payment_total),
                'insurance_amount' => titleFloatFormat($contract->insurance_amount),
                'bso_title' => $bso->bso_title,

                'currency_title' => $currency_title,
                'currency_total' => $currency_total,
                'payment_curr_total' => titleFloatFormat($result->payment_curr_total),

                'insurer_lat' => $insurer_lat,
                'insurer_birthdate' => setDateTimeFormatRu($insurer_data->birthdate, 1),
                'insurer_citizenship' => $insurer_data->citizenship->title_en,
                'insurer_phone' => $insurer->phone,
                'country' => $country,
                'count_day' => $contract->data->count_day,

                'assistance' => $assistance,
                'assistance_coments' => $assistance_coments,

                'insurers_lat_1' => self::getInsurersVal($insurers, 0, 'title_lat'),
                'ins_birth_1' => self::getInsurersVal($insurers, 0, 'birthdate'),

                'insurers_lat_2' => self::getInsurersVal($insurers, 1, 'title_lat'),
                'ins_birth_2' => self::getInsurersVal($insurers, 1, 'birthdate'),

                'insurers_lat_3' => self::getInsurersVal($insurers, 2, 'title_lat'),
                'ins_birth_3' => self::getInsurersVal($insurers, 2, 'birthdate'),

                'insurers_lat_4' => self::getInsurersVal($insurers, 3, 'title_lat'),
                'ins_birth_4' => self::getInsurersVal($insurers, 3, 'birthdate'),

                'insurers_lat_5' => self::getInsurersVal($insurers, 4, 'title_lat'),
                'ins_birth_5' => self::getInsurersVal($insurers, 4, 'birthdate'),

                'insurers_lat_6' => self::getInsurersVal($insurers, 5, 'title_lat'),
                'ins_birth_6' => self::getInsurersVal($insurers, 5, 'birthdate'),

                'insurers_lat_7' => self::getInsurersVal($insurers, 6, 'title_lat'),
                'ins_birth_7' => self::getInsurersVal($insurers, 6, 'birthdate'),
                'insurers_lat_8' => self::getInsurersVal($insurers, 7, 'title_lat'),
                'ins_birth_8' => self::getInsurersVal($insurers, 7, 'birthdate'),
                'insurers_lat_9' => self::getInsurersVal($insurers, 8, 'title_lat'),
                'ins_birth_9' => self::getInsurersVal($insurers, 8, 'birthdate'),

                'special_terms' => '',

                'vzr_amount' => $_vzr->amount,
                'vzr_payment' => $_vzr->payment,
                'vzr_prog' => $_vzr->prog,
                'vzr_fran' => $vzr_fran,

                //Несчастный случай
                'ns_amount' => $_ns->amount,
                'ns_payment' => $_ns->payment,

                //Отмена поездки
                'ct_amount' => $_ct->amount,
                'ct_payment' => $_ct->payment,
                'ct_prog' => $_ct->prog,

                //Гражданская ответственность
                'cr_amount' => $_cr->amount,
                'cr_payment' => $_cr->payment,

                //Юридическая помощь
                'la_amount' => $_la->amount,
                'la_payment' => $_la->payment,

                //Багаж
                'b_amount' => $_b->amount,
                'b_payment' => $_b->payment,

                //Задержка рейса
                'fd_amount' => $_fd->amount,
                'fd_payment' => $_fd->payment,
                'fd_prog' => $_fd->prog,

                //Опоздание на рейс
                'mf_amount' => $_mf->amount,
                'mf_payment' => $_mf->payment,

                //Отмена экскурсии
                'ctr_amount' => $_ctr->amount,
                'ctr_payment' => $_ctr->payment,
            ],
            'dop' => [],
        ];

        return $data;
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