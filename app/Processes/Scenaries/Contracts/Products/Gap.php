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
use App\Processes\Operations\Contracts\Products\CalcGap;
use App\Processes\Operations\Contracts\Products\CalcKasko;
use App\Services\Integration\VernaControllers\Auxiliary\Car;
use App\Services\Integration\VernaControllers\VernaSend;
use App\Services\Integration\VernaControllers\VernaSubjects;

class Gap {

    public static function save(Contracts $contract, $data){



        $contract->sign_date = setDateTimeFormat(date("Y-m-d H:i:s"));

        $contract->begin_date = setDateTimeFormat($data->begin_date.' 00:00:00');

        $type_end = 1;
        if((int)$data->data['insurance_option'] == 1) $type_end = 1;
        if((int)$data->data['insurance_option'] == 2) $type_end = 2;
        if((int)$data->data['insurance_option'] == 3) $type_end = 3;
        if((int)$data->data['insurance_option'] == 4) $type_end = 1;
        if((int)$data->data['insurance_option'] == 5) $type_end = 2;
        if((int)$data->data['insurance_option'] == 6) $type_end = 3;
        if((int)$data->data['insurance_option'] == 7) $type_end = 4;
        if((int)$data->data['insurance_option'] == 8) $type_end = 5;
        if((int)$data->data['insurance_option'] == 9) $type_end = 1;
        if((int)$data->data['insurance_option'] == 10) $type_end = 1;


        $end_date = date('Y-m-d 00:00:00', strtotime("+{$type_end} year {$contract->begin_date}"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));

        $contract->end_date = $end_date;


        if(isset($data->is_prolongation)){
            $contract->is_prolongation = $data->is_prolongation;
        }

        $contract->installment_algorithms_id = isset($data->installment_algorithms_id) ? $data->installment_algorithms_id : '';

        $contract->financial_policy_manually_set = 1;
        $contract->financial_policy_kv_bordereau = getFloatFormat($data->financial_policy_kv_bordereau);


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

        if(isset($data->beneficiar)){
            $beneficiar = (object)$data->beneficiar;

            if($beneficiar->is_subject == 0){
                if($contract->beneficiar_id == $contract->insurer_id){
                    $contract->beneficiar_id = 0;
                }
                $contract->beneficiar_id = Subjects::saveOrCreateOnlineSubject($beneficiar, $contract->beneficiar_id, $contract->agent_id)->id;
            }

            if($beneficiar->is_subject == 1){
                $contract->beneficiar_id = $contract->insurer_id;
            }

            if($beneficiar->is_subject == 2){
                $contract->beneficiar_id = $contract->owner_id;
            }


        }




        $contract->data()->update([
            'insurance_option' => (int)$data->data['insurance_option'],
            'sk_title' => $data->data['sk_title'],
            'kasko_number' => $data->data['kasko_number'],
            'kasko_date' => getDateFormat($data->data['kasko_date']),

            'is_auto_credit' => (int)$data->data['is_auto_credit'],

        ]);




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

        $auto->mileage = getFloatFormat($object->mileage);

        $auto->car_price = getFloatFormat($object->car_price);

        $auto->is_credit = (isset($object->is_credit))?1:0;
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

        //$contract->insurance_amount = getFloatFormat($auto->car_price);



        if($contract->save()){


            return true;
        }

        return false;

    }



    public static function calc(Contracts $contract)
    {
        return CalcGap::calc($contract);

    }



    public static function getPrintData(Contracts $contract)
    {

        $templates = [];

        $auto = $contract->object_insurer_auto()->first();
        if($contract->is_prolongation){
            $main_contract = $contract->mainContract;
        }

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

        $beneficiary_is_insurant = (!$contract->beneficiar_id || $contract->beneficiar_id == $contract->insurer_id ? 1 : 0 );

        $quantity = 1;
        $payment_type = 0;
        if($pays = $contract->payments){
            $quantity = sizeof($pays);
            $payment_type = $pays[0]->payment_type;
        }
        $payment_procedure = $quantity == 1 ? 'Единовременный платеж' : "С рассрочкой платежа: на {$quantity} платежа";

        $option = \App\Models\Directories\Products\Data\GAP\Gap::OPTION[$contract->data->insurance_option];

        $max_limit = $auto->car_price <= 4500000 ? '1 000 000,00' : '1 500 000,00';
        if($contract->calculation){
            if(strlen($contract->calculation->json) > 0){
                $info = json_decode($contract->calculation->json);
                $max_limit = titleFloatFormat($info->max_amount, 0, 1);
            }
        }



        $data = [

            'settings' => [
                'templates'=> $templates,
            ],
            'info' => [
                'phone_rf_for_insurance_case' => '+7 (495) 287-27-00, +7 (495) 274-01-00',
                'email_for_claim' => 'info@titins.ru',
                'policy_number' => $contract->bso_title,
                'policy_date_create' => setDateTimeFormatRu($contract->sign_date, 1),
                'policy_date_start_insurance' => setDateTimeFormatRu($contract->begin_date, 1),
                'policy_date_end_insurance' => setDateTimeFormatRu($contract->end_date, 1),
                'insurance_program_num' => '162',//
                'insurance_program_date' => '15.08.2019',
                'insurance_name_full' => 'ООО Страховая компания «ТИТ»',
                'insurance_rekvizit' => '115088, Москва, 2-й Южнопортовый проезд, д.18, стр.8 Лицензии СИ №7-077-005/2019-01 от 15.08.2019',

                'bi' => $beneficiary_is_insurant ? 'Х' : '',
                'b' => $beneficiary_is_insurant ? '' : 'Х',

                'car_mark' => ($auto->mark) ? $auto->mark->title : '',
                'car_model' => ($auto->model) ? $auto->model->title : '',
                'car_year' => $auto->car_year,
                'car_reg_num' => !empty($auto->reg_number)? $auto->reg_number : '',
                'car_vin' => $auto->vin,
                'car_pts_num' => in_array($auto->doc_type, ["220219", "220224","220222","220227","225346"]) ? $auto->docserie." ".$auto->docnumber : '',
                'car_sts_num' => in_array($auto->doc_type, ["220220", "220225"]) ? $auto->docserie." ".$auto->docnumber : '',
                'car_first_reg_date' => in_array($auto->doc_type, ["220220", "220225"]) ? setDateTimeFormatRu($auto->docdate, 1):'',
                'car_probeg' => $auto->mileage,
                'buy_date' => '',
                'kasko_cost' => titleFloatFormat($auto->car_price),
                'prolongation_policy_num' => $contract->data->kasko_number,
                'kasko_start_date' => setDateTimeFormatRu($contract->data->kasko_date,1),
                'ct' => $contract->data->is_auto_credit != 0 ? 'Х': '',
                'credit_date' => '',
                'start_credit' => '',
                'end_credit' => '',
                'acg' => mb_strpos($option,'АВТО ССТ GAP') !== false ? 'Х': '',
                'fcg' => mb_strpos($option,'ФИНАНС ССТ GAP') !== false ? 'Х': '',
                'car_cost' => titleFloatFormat($contract->insurance_amount),
                'max_limit' => $max_limit,
                'sum' => titleFloatFormat($contract->payment_total),

                'payment_procedure' => $payment_procedure,
                'pwh' =>  $payment_type != 0 ? 'Х': '',
                'pwc' => $payment_type == 0 ? 'Х': '',
                'insurance_dir_name' => 'Бирюков Д.А.',
                'insurance_dir_type' => '',
                'capture_ins_name' => $contract->insurer->title,
                'partner_info' => 'Криворотов Валерий Алексеевич, Общество с ограниченной ответственностью "Новый Страховой Стандарт", юрид. адрес: Москва, б-р Дмитрия Донского, 18/4, ИНН 7727805071, конт. тел. +7 (965) 392-43-23',
                ],
        ];

        $data['info'] = array_merge($data['info'], self::getInsurersPrint($contract->insurer, 'insurant'));
        $data['info'] = array_merge($data['info'], self::getInsurersPrint($contract->beneficiar, 'beneficiary',$beneficiary_is_insurant));
//dd($data);
        return $data;
    }

    public static function getInsurersPrint($subject, $key, $needNull = 0){

        $country = '';
        $document = '';
        if($subject->type == 0){
            $country = $subject->citizenship_id == 51 ? 'Россия' : Country::find($subject->citizenship_id)->title_ru;
            $document = $subject->data()->doc_type == 1165 ? 'Паспорт РФ' : SubjectsFlDocType::where('isn', $subject->data()->doc_type)->first()->title;
            $document .= isset($subject->doc_serie) ? ' '. $subject->doc_serie : '';
            $document .= isset($subject->doc_number) ? ' '. $subject->doc_number : '';
            $document .= ', выдан '. $subject->data()->doc_info . ', дата выдачи '.setDateTimeFormatRu($subject->data()->doc_date,1) ;
        }
        if($needNull == 0) {
            $res = [
                "{$key}_name" => $subject->title,
                "{$key}_citizenship" => $country,
                "{$key}_document" => $subject->type == 0 ? $document : '',
                "{$key}_birthdate" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->birthdate, 1) : '',
                "{$key}_birth_place" => $subject->type == 0 ? $subject->data()->address_born : '',
                "{$key}_inn" => isset($subject->inn) ? $subject->inn : '',
                "{$key}_city" => $subject->type == 0 ? $subject->data()->address_register : '',
                "{$key}_phone" => isset($subject->phone) ? $subject->phone : '',
                "{$key}_email" => isset($subject->email) ? $subject->email : '',
            ];
        } else{
            $res = [
               "{$key}_name" => '',
               "{$key}_citizenship" => '',
               "{$key}_document" => '',
               "{$key}_birthdate" =>  '',
               "{$key}_birth_place" =>  '',
               "{$key}_inn" =>  '',
               "{$key}_city" =>  '',
               "{$key}_phone" =>  '',
               "{$key}_email" =>  '',
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