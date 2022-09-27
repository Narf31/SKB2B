<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsInsurer;
use App\Models\Contracts\ObjectInsurer\ObjectEquipmentAuto;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Contracts\SubjectsFlDocType;
use App\Models\Directories\Products\Data\Kasko\Drive;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\Directories\Products\ProductsSpecialSsettingsFiles;
use App\Models\File;
use App\Models\Settings\Bank;
use App\Models\Settings\Country;
use App\Models\Settings\CurrencyValue;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcDgo;
use App\Processes\Operations\Contracts\Products\CalcGap;
use App\Processes\Operations\Contracts\Products\CalcKasko;
use App\Services\Integration\VernaControllers\Auxiliary\Car;
use App\Services\Integration\VernaControllers\VernaSend;
use App\Services\Integration\VernaControllers\VernaSubjects;

class Dgo {

    public static function save(Contracts $contract, $data){



        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));

        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');

        $end_date = date('Y-m-d 00:00:00', strtotime("+1 year {$contract->begin_date}"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));

        $contract->end_date = $end_date;


        if(isset($data->is_prolongation)){
            $contract->is_prolongation = $data->is_prolongation;
        }

        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';


        if(isset($data->insurer)){
            $contract->insurer_id = Subjects::saveOrCreateOnlineSubject((object)$data->insurer, $contract->insurer_id, $contract->agent_id)->id;
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

        $contract->beneficiar_id = $contract->insurer_id;

        $calc_data = [];
        $is_multidriver = 0;

        if(isset($data->insurers['is_multidriver']) && $data->insurers['is_multidriver'] == 1){
            $is_multidriver = 1;
            $type_multidriver = (int)$data->insurers['type_multidriver'];


            $calc_data = ['type_multidriver'=>$type_multidriver, 'birthdate_year'=>$data->insurers['birthdate_year'], 'birthdate_year_l'=>$data->insurers['birthdate_year_l'], 'exp_year'=>$data->insurers['exp_year'], 'exp_year_l'=>$data->insurers['exp_year_l']];

        }

        $is_only_spouses = 0;
        if(isset($data->insurers['is_only_spouses']) && $data->insurers['is_only_spouses'] == 1){
            $is_only_spouses = 1;
        }


        $contract->data()->update([
            'territory_id' => $data->data['territory_id'],
            'is_multidriver' => $is_multidriver,
            'is_only_spouses' => $is_only_spouses,
            'calc_data' => \GuzzleHttp\json_encode($calc_data),
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
        $auto->mark_id = $object->mark_id;
        $auto->model_id = $object->model_id;
        $auto->model_classification_code = $object->model_classification_code;
        $auto->car_year = (int)$object->car_year;
        $auto->vin = $object->vin;
        $auto->country_id = (int)$object->country_id;
        $auto->type_reg_number = (int)$object->type_reg_number;
        $auto->reg_number = $object->reg_number;
        $auto->doc_type = (int)$object->doc_type;
        $auto->docserie = $object->docserie;
        $auto->docnumber = $object->docnumber;
        $auto->docdate = getDateFormatEn($object->docdate);

        $auto->power = getFloatFormat($object->power);
        $auto->mileage = getFloatFormat($object->mileage);

        $auto->is_duplicate = (isset($object->is_duplicate))?1:0;

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

        $contract->insurance_amount = getFloatFormat($data->insurance_amount);




        if($contract->save()){






            return true;
        }

        return false;

    }



    public static function calc(Contracts $contract)
    {
        return CalcDgo::calc($contract);

    }



    public static function getPrintData(Contracts $contract)
    {

        $templates = [];

        $auto = $contract->object_insurer_auto()->first();
        if($contract->is_prolongation){
            $main_contract = $contract->mainContract;
        }
        $bso = $contract->bso;

        if($contract->product->template_contract_id > 0) {
            $special_file = File::find($contract->product->template_contract_id);
            $original_name = $special_file->original_name;
            $original_name = explode('.', $original_name);
            $original_name = $original_name[0];
            $templates[] = ['path' => $special_file, 'title' => $original_name, 'info' => $contract->product];
        }

        if(!sizeof($templates)){
            return [];
        }

        $quantity = isset($contract->payments)? sizeof($contract->payments) : 1;

        $payment_procedure = $quantity == 1 ? 'Единовременно' : "С рассрочкой";

        $drivers = [];


        if($contract->data->is_multidriver == 1){

            $driver_fio = '-';
            $driver_bday = '-';
            $driver_expyear = '-';

            if(isset($contract->data->calc_data) && strlen($contract->data->calc_data) > 5){
                $calc_data = json_decode($contract->data->calc_data);
                if($calc_data->type_multidriver == 0){
                    $driver_fio = 'Минимальный стаж и возраст';
                    $driver_bday = $calc_data->birthdate_year;
                    $driver_expyear = $calc_data->exp_year;
                }
            }

            $drivers[] = [
                "driver_fio" => $driver_fio,
                "driver_sex" => '-',
                "driver_bday" => $driver_bday,
                "driver_doc_serie" => '-',
                "driver_doc_number" => '',
                "driver_doc_date" => '',
                "driver_exp_date" => '-',
                "driver_expyear" => $driver_expyear,
            ];
        }else{
            $drivers = self::getDriversPrint($contract->contracts_insurers);
        }


        $data = [

            'settings' => [
                'templates'=> $templates,
            ],
            'info' => [
                'policy_series' => isset($bso->bso_serie) ? $bso->bso_serie->bso_serie : '',
                'policy_number' => isset($bso->bso_number) ? $bso->bso_number : '',
                'policy_date_create' => setDateTimeFormatRu($contract->sign_date, 1),
                'policy_date_start_insurance' => setDateTimeFormatRu($contract->begin_date, 1),
                'policy_date_end_insurance' => setDateTimeFormatRu($contract->end_date, 1),

                'car_mark' => $auto->mark->title,
                'car_model' => $auto->model->title,
                'car_year' => $auto->car_year,
                'car_reg_num' => !empty($auto->reg_number)? $auto->reg_number : '',
                'car_vin' => $auto->vin,
                'car_pts_num' => in_array($auto->doc_type, ["220219", "220224","220222","220227","225346"]) ? $auto->docserie."№".$auto->docnumber : '',
                'car_sts_num' => in_array($auto->doc_type, ["220220", "220225"]) ? $auto->docserie."№".$auto->docnumber : '',

                'insurance_amount' => titleFloatFormat($contract->insurance_amount),
                'sum' => titleFloatFormat($contract->payment_total),
                'franchise' => isset($contract->data->franchise_id) && $contract->data->franchise_id != 0 ? titleFloatFormat($contract->data->franchise_id) :'Нет',

                'drivers' =>  $drivers,

                'risk_territory' => 'Россия',
                'payment_procedure' => $payment_procedure,

                'insurance_dir_name' => 'Бирюков Д.А.',

            ],
        ];

        $data['info'] = array_merge($data['info'], self::getInsurersPrint($contract->insurer, 'insurant'));
//dd($data);
        return $data;

    }

    public static function getInsurersPrint($subject, $key){

        $country = '';
        $document = '';
        if($subject->type == 0){
            $fio = self::getShortFioFromFullName($subject->title);
            $country = $subject->citizenship_id == 51 ? 'Россия' : Country::find($subject->citizenship_id)->title_ru;
            $document = $subject->data()->doc_type == 1165 ? 'Паспорт РФ' : SubjectsFlDocType::where('isn', $subject->data()->doc_type)->first()->title;
            $document .= isset($subject->doc_serie) ? ' '. $subject->doc_serie : '';
            $document .= isset($subject->doc_number) ? ' '. $subject->doc_number : '';
            $document .= ', выдан '. $subject->data()->doc_info . ', дата выдачи '.setDateTimeFormatRu($subject->data()->doc_date,1) ;
        }

        $res = [
            "{$key}_name" => $subject->title,
            "{$key}_name_short" => $subject->type == 0 ? $fio : $subject->title,
            "{$key}_citizenship" => $country,
            "{$key}_document" => $subject->type == 0 ? $document : '',
            "{$key}_birthdate" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->birthdate, 1) : '',
            "{$key}_birth_place" => $subject->type == 0 ? $subject->data()->address_born : '',
            "{$key}_inn" => isset($subject->inn) ? $subject->inn : '',
            "{$key}_city" => $subject->type == 0 ? $subject->data()->address_register : '',
            "{$key}_phone" => isset($subject->phone) ? $subject->phone : '',
            "{$key}_email" => isset($subject->email) ? $subject->email : '',
        ];

        return $res;

    }
    public static function getShortFioFromFullName($name){
        $temp = explode(' ',$name);
        $fio = $temp[0];
        if(isset($temp[1])){
            $fio .= ' '.mb_substr($temp[1],0, 1).'.';
        }
        if(isset($temp[2])){
            $fio .= mb_substr($temp[2],0, 1).'.';
        }
        return $fio;
    }

    public static function getDriversPrint($drivers){
        $res = [];

        foreach($drivers as $key => $driver){
            $res[] = [
                "driver_fio" => $driver->title,
                "driver_sex" => $driver->sex ? 'жен' : 'муж',
                "driver_bday" => setDateTimeFormatRu($driver->birthdate, 1),
                "driver_doc_serie" => isset($driver->doc_serie) ? $driver->doc_serie : '',
                "driver_doc_number" => isset($driver->doc_number) ? '№'.$driver->doc_number : '',
                "driver_doc_date" => isset($driver->doc_date) ? setDateTimeFormatRu($driver->doc_date, 1) : '',
                "driver_exp_date" => "C " . setDateTimeFormatRu($driver->exp_date, 1),
                "driver_expyear" => $driver->expyear,
            ];
        }

        if(!sizeof($res)){
            $res[] = [
                "driver_fio" => '-',
                "driver_sex" => '-',
                "driver_bday" => '-',
                "driver_doc_serie" => '-',
                "driver_doc_number" => '',
                "driver_doc_date" => '',
                "driver_exp_date" => '-',
                "driver_expyear" => '-',
            ];
        }
        return $res;

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

        if($contract->insurer_id == $contract->owner_id ){
            $new_contract->owner_id = $new_contract->insurer_id;
        }else{
            $subject_o = Subjects::cloneSubject($contract->owner);
            $new_contract->owner_id = $subject_o->id;
        }

        if($contract->insurer_id == $contract->beneficiar_id ){
            $new_contract->beneficiar_id = $new_contract->insurer_id;
        }elseif($contract->owner_id == $contract->beneficiar_id ){
            $new_contract->beneficiar_id = $new_contract->owner_id;
        }else{
            $subject_b = Subjects::cloneSubject($contract->beneficiar);
            $new_contract->beneficiar_id = $subject_b->id;
        }


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