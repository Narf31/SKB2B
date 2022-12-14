<?php

namespace App\Processes\Scenaries\Contracts\Products;


use App\Models\Contracts\ContractsInsurer;
use App\Models\Contracts\ObjectInsurer\ObjectEquipmentAuto;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Contracts\SubjectsFlDocType;
use App\Models\Directories\Products\Data\Kasko\Drive;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\Directories\Products\ProductsSpecialSsettingsFiles;
use App\Models\Settings\Bank;
use App\Models\Settings\Country;
use App\Models\Settings\CurrencyValue;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Products\CalcKasko;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Services\Integration\VernaControllers\Auxiliary\Car;
use App\Services\Integration\VernaControllers\VernaSend;
use App\Services\Integration\VernaControllers\VernaSubjects;

class Kasko {

    public static function save(Contracts $contract, $data){

        if($contract->program->slug == 'calculator'){
            return self::saveCalculator($contract, $data);
        }

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
                if(isset($beneficiar->bank_general_subject_id) && (int)$beneficiar->bank_general_subject_id > 0){
                    if($contract->beneficiar_id == 0){
                        $beneficiarS = new Subjects();
                    }else{
                        $beneficiarS = $contract->beneficiar;
                        $beneficiarS->type = 1;
                    }

                    if(isset($beneficiar->bank_comments)){
                        $beneficiarS->comments = $beneficiar->bank_comments;
                    }

                    $beneficiarS->save();

                    if($beneficiarS->general_subject_id != (int)$beneficiar->bank_general_subject_id){
                        $res_clone = GeneralSubjectsSearch::clone_general($beneficiar->bank_general_subject_id, $beneficiarS, 0);
                        if($res_clone->state == true){
                            $contract->beneficiar_id = $beneficiarS->id;
                        }
                    }else{
                        if($beneficiarS->general_subject_id == (int)$beneficiar->bank_general_subject_id){
                            if($beneficiarS->general){
                                $beneficiarS->title = $beneficiarS->general->title;
                                $beneficiarS->save();
                            }
                        }
                    }

                }else{
                    $contract->beneficiar_id = Subjects::saveOrCreateOnlineSubject($beneficiar, $contract->beneficiar_id, $contract->agent_id)->id;
                }

            }

            if($beneficiar->is_subject == 1){
                $contract->beneficiar_id = $contract->insurer_id;
            }

            if($beneficiar->is_subject == 2){
                $contract->beneficiar_id = $contract->owner_id;
            }


        }


        $is_multidriver = 0;
        if(isset($data->insurers['is_multidriver']) && $data->insurers['is_multidriver'] == 1){
            $is_multidriver = 1;
        }

        $is_only_spouses = 0;
        if(isset($data->insurers['is_only_spouses']) && $data->insurers['is_only_spouses'] == 1){
            $is_only_spouses = 1;
        }

        $calc_data = [];
        if($is_multidriver == 1){

            if(isset($data->insurers['type_multidriver']) && $data->insurers['type_multidriver'] == 0){
                $calc_data = ['type_multidriver'=>$data->insurers['type_multidriver'], 'birthdate_year'=>$data->insurers['birthdate_year'], 'birthdate_year_l'=>$data->insurers['birthdate_year_l'], 'exp_year'=>$data->insurers['exp_year'], 'exp_year_l'=>$data->insurers['exp_year_l']];

            }

        }



        $contract->data()->update([
            'is_multidriver' => $is_multidriver,
            'is_only_spouses' => $is_only_spouses,
            'is_auto_credit' => (int)$data->data['is_auto_credit'],
            'is_transition' => $data->data['is_transition'],
            'official_discount' => getFloatFormat($data->data['official_discount']),
            'limit_indemnity_id' => (int)$data->data['limit_indemnity_id'],
            'insurance_term' => (int)$data->data['insurance_term'],
            'calc_data' => \GuzzleHttp\json_encode($calc_data),
        ]);

        $contract->contracts_insurers()->delete();
        if($is_multidriver == 0){
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
        }





        $object = (object)$data->object;
        $object_insurer = $contract->object_insurer;
        $auto = $object_insurer->data();
        $auto->ts_category = (int)$object->ts_category;
        $auto->mark_id = $object->mark_id;
        $auto->model_id = $object->model_id;
        $auto->model_classification_code = $object->model_classification_code;
        $auto->car_year = (int)$object->car_year;
        $auto->purpose_id = (int)$object->purpose_id;
        $auto->vin = $object->vin;
        $auto->body_number = $object->body_number;
        $auto->transmission_type = $object->transmission_type;
        $auto->body_chassis = $object->body_chassis;
        $auto->country_id = (int)$object->country_id;
        $auto->type_reg_number = (int)$object->type_reg_number;
        $auto->reg_number = $object->reg_number;
        $auto->power = getFloatFormat($object->power);
        $auto->powerkw = getFloatFormat($object->powerkw);
        $auto->weight = getFloatFormat($object->weight);
        $auto->capacity = getFloatFormat($object->capacity);
        $auto->is_trailer = (isset($object->is_trailer))?(int)$object->is_trailer:0;
        $auto->doc_type = (int)$object->doc_type;
        $auto->docserie = $object->docserie;
        $auto->docnumber = $object->docnumber;
        $auto->docdate = getDateFormatEn($object->docdate);
        $auto->dk_number = $object->dk_number;
        $auto->dk_date_from = getDateFormatEn($object->dk_date_from);
        $auto->dk_date_to = getDateFormatEn($object->dk_date_to);


        $auto->count_key = (int)getFloatFormat($object->count_key);
        $auto->anti_theft_system_id = (int)$object->anti_theft_system_id;
        $auto->color_id = (int)$object->color_id;
        $auto->engine_type_id = (int)$object->engine_type_id;
        $auto->volume = getFloatFormat($object->volume);

        $auto->mileage = getFloatFormat($object->mileage);
        $auto->car_price = getFloatFormat($object->car_price);

        $auto->number_owners = (int)$object->number_owners;
        $auto->source_acquisition_id = (int)$object->source_acquisition_id;

        $auto->is_credit = (isset($object->is_credit))?1:0;
        $auto->is_autostart = (isset($object->is_autostart))?1:0;
        $auto->is_right_drive = (isset($object->is_right_drive))?1:0;
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



        $contract->object_equipment()->delete();

        if(isset($data->equipment)){
            foreach ($data->equipment as $key => $equipment){

                if(is_int($key)){

                    ObjectEquipmentAuto::create([
                        'contract_id' => $contract->id,
                        'title' => $equipment['title'],
                        'payment_total' => getFloatFormat($equipment['payment_total']),
                    ]);
                }
            }
        }

        if(isset($data->insurance_amount)){
            $contract->insurance_amount = getFloatFormat($data->insurance_amount);
        }else{
            $contract->insurance_amount = getFloatFormat($auto->car_price);
        }



        if($contract->save()){

            if($contract->program->slug == 'standard'){
                return self::saveStandard($contract, $data);
            }

            if($contract->program->slug == 'drive'){
                return self::saveDrive($contract, $data);
            }

            return true;
        }

        return false;

    }



    public static function saveCalculator($contract, $data)
    {

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

        $calc_data = [];
        $is_multidriver = 0;

        if(isset($data->insurers['is_multidriver']) && $data->insurers['is_multidriver'] == 1){
            $is_multidriver = 1;
        }else{
            $type_multidriver = 1;
            if((int)$data->insurers['is_multidriver'] == 2){
                $is_multidriver = 1;
                $type_multidriver = 0;
            }

            $calc_data = ['type_multidriver'=>$type_multidriver, 'birthdate_year'=>$data->insurers['birthdate_year'], 'birthdate_year_l'=>$data->insurers['birthdate_year_l'], 'exp_year'=>$data->insurers['exp_year'], 'exp_year_l'=>$data->insurers['exp_year_l']];
        }

        $is_only_spouses = 0;
        if(isset($data->insurers['is_only_spouses']) && $data->insurers['is_only_spouses'] == 1){
            $is_only_spouses = 1;
        }


        $contract->data()->update([
            'is_multidriver' => $is_multidriver,
            'is_only_spouses' => $is_only_spouses,
            'calc_data' => \GuzzleHttp\json_encode($calc_data),
            'is_auto_credit' => (int)$data->data['is_auto_credit'],
            'is_transition' => $data->data['is_transition'],
            'official_discount' => getFloatFormat($data->data['official_discount']),
            'limit_indemnity_id' => (int)$data->data['limit_indemnity_id'],
            'insurance_term' => (int)$data->data['insurance_term'],
        ]);

        $object = (object)$data->object;
        $object_insurer = $contract->object_insurer;
        $auto = $object_insurer->data();
        $auto->mark_id = $object->mark_id;
        $auto->model_id = $object->model_id;
        $auto->model_classification_code = $object->model_classification_code;

        $auto->car_year = (int)$object->car_year;
        $auto->purpose_id = (int)$object->purpose_id;
        $auto->vin = $object->vin;
        $auto->transmission_type = $object->transmission_type;
        $auto->country_id = (int)$object->country_id;
        $auto->power = getFloatFormat($object->power);
        $auto->powerkw = getFloatFormat($object->powerkw);
        $auto->weight = getFloatFormat($object->weight);
        $auto->capacity = getFloatFormat($object->capacity);
        $auto->is_trailer = (isset($object->is_trailer))?(int)$object->is_trailer:0;

        $auto->count_key = (int)getFloatFormat($object->count_key);
        $auto->anti_theft_system_id = (int)$object->anti_theft_system_id;
        $auto->color_id = (int)$object->color_id;
        $auto->engine_type_id = (int)$object->engine_type_id;
        $auto->volume = getFloatFormat($object->volume);

        $auto->mileage = getFloatFormat($object->mileage);
        $auto->car_price = getFloatFormat($object->car_price);

        $auto->number_owners = (int)$object->number_owners;

        $auto->is_credit = (isset($object->is_credit))?1:0;
        $auto->is_autostart = (isset($object->is_autostart))?1:0;
        $auto->is_right_drive = (isset($object->is_right_drive))?1:0;
        $auto->is_duplicate = (isset($object->is_duplicate))?1:0;

        $auto->save();

        $object_insurer->title = '';

        if($auto->mark_kasko){
            $object_insurer->title .= "{$auto->mark_kasko->title} ";
        }
        if($model_kasko = $auto->model_kasko()){
            $object_insurer->title .= "{$model_kasko->NAME} ";
        }
        $object_insurer->title .= "{$auto->car_year}";
        $object_insurer->save();


        $contract->object_equipment()->delete();

        if(isset($data->equipment)){
            foreach ($data->equipment as $key => $equipment){

                if(is_int($key)){

                    ObjectEquipmentAuto::create([
                        'contract_id' => $contract->id,
                        'title' => $equipment['title'],
                        'payment_total' => getFloatFormat($equipment['payment_total']),
                    ]);
                }
            }
        }


        $contract->insurance_amount = getFloatFormat($data->insurance_amount);
        $contract->save();

        return self::saveStandard($contract, $data);
    }


    public static function saveStandard($contract, $data)
    {
        $term = (object)$data->data;
        $program = $contract->data;

        $program->insurance_term = (int)$term->insurance_term;
        $program->coatings_risks_id = (int)$term->coatings_risks_id;
        $program->territory_id = (int)$term->territory_id;
        $program->tenure_id = (isset($term->tenure_id)?(int)$term->tenure_id:null);
        $program->repair_options_id = (int)$term->repair_options_id;
        $program->franchise_id = (int)$term->franchise_id;
        $program->franchise_number_id = ((int)$term->franchise_id>0)?(int)$term->franchise_number_id:null;

        $program->is_gap = (int)$term->is_gap;
        $program->is_emergency_commissioner = (int)$term->is_emergency_commissioner;
        $program->is_collection_certificates = (int)$term->is_collection_certificates;
        $program->is_evacuation = (int)$term->is_evacuation;
        $program->civil_responsibility_sum = (int)$term->civil_responsibility_sum;

        $program->ns_type = (int)$term->ns_type;
        $program->ns_count = (int)$term->ns_count;
        $program->ns_sum = getFloatFormat($term->ns_sum);




        $program->save();

        return true;
    }

    public static function saveDrive($contract, $data)
    {
        //???????????????????? ?????????????????? ??????????
        //

        $term = (object)$data->data;
        $program = $contract->data;

        $spec = \App\Models\Directories\Products\ProductsSpecialSsettings::where('product_id', $contract->product->id)->where('program_id', $contract->program->id)->get()->first();
        $spec_info = null;
        if($spec && $spec->json && strlen($spec->json) > 0){
            $spec_info = json_decode($spec->json);


            $program->coatings_risks_id = (int)$spec_info->terms->coatings_risks_id;
            $program->territory_id = (int)$spec_info->terms->territory_id;
            $program->repair_options_id = (int)$spec_info->terms->repair_options_id;


            $program->save();
            return true;
        }




        return false;
    }



    public static function calc(Contracts $contract)
    {
        return CalcKasko::calc($contract);

    }



    public static function getPrintData(Contracts $contract)
    {

        $templates = [];

        $auto = $contract->object_insurer_auto()->first();
       /* if($contract->is_prolongation){
            $main_contract = $contract->mainContract;
        }*/

        $special_settings = $contract->program->special_settings;
        $info = ProductsSpecialSsettingsFiles::where('special_settings_id',$special_settings->id)
            ->whereIn('type_name',['contract','agreement'])
            ->get();

        if($special_settings && $info) {
            foreach($info as $data){
                $special_file = $special_settings->files->where('id',$data->file_id)->first();
                $original_name = $special_file->original_name;
                $original_name = explode('.', $original_name);
                $original_name = $original_name[0];
                $templates[] = ['path' => $special_file, 'title' => $original_name, 'info' => $data];
            }
        }

        if(!sizeof($templates)){
            return [];
        }

        $insurer = $contract->insurer;
        $beneficiary_is_insurant = 0;
        if($contract->beneficiar_id == $contract->insurer_id) $beneficiary_is_insurant = 1;

        $owner_is_insurant = (!$contract->owner_id || $contract->owner_id == $contract->insurer_id ? 1 : 0 );
        $owner_is_beneficiary = (!$beneficiary_is_insurant && !$owner_is_insurant && $contract->owner_id > 0 && $contract->owner_id == $contract->beneficiar_id ? 1 : 0 );
        $owner_company = '';
        $bank_info = '';
        if(!$owner_is_insurant && !$owner_is_beneficiary && $contract->owner ){
            if($contract->owner->type == 0){
                $owner_company = $contract->owner->title;
            }else{
                $owner_company = $contract->owner->title .' ?? ???????????????????????? ?? ??????????????????, ???????????????????? ?? ???????????????????? ???1 ?? ???????????????????? ????????????';
                $bank_info = '???? ???????????? "??????????" (?? ???????????? ???????????? ?????????????????????? ?????? ???????????????????????????? ???????????? ????) 
                ?? "????????/??????????????"??? ?????????????????????????????????????? '.$contract->owner->title.' ?? ?????????????? ?????????????????????????? 
                ???????????????????????? ???????????????????????? ?????????????????????????? ???????????????? ?????????? ???????????? ???? ???????????????????? ????????????????, 
                ???????????????????????? ???? ???????????? ?????????????? ???????????????????? ????????????????????, ?? ???????????????????? ?????????? ???????????????????? ???????????????????? 
                ?????????????????????????????????????? - ??????????????????????. ???? ?????????? "??????????" (?? ???????????? ?????????????????????? ????) 
                ?????????????????????????????????????? ??? ??????????????????????.';
            }
        }
        $staff_drivers = 0; //???????????? ???????????
        $prof_drivers = 1; //???????????? ???????????
        $min_age = '';
        $min_exp = '';

        $dtos = ((int)$contract->data->is_only_spouses == 1) ? '??' : ''; //- ???????????? ??????????????
        $dtm = ((int)$contract->data->is_multidriver == 1) ? '??' : ''; // - ??????????????????????
        $dtl = ''; // - ?? ???????????????????????? ???? ?????????? ?? ????????????????

        $dtod = ((int)$contract->data->is_multidriver == 0) ? '??' : ''; //- ???????????? ??????????????????

        if($contract->data->calc_data && strlen($contract->data->calc_data) > 5){
            $calc_data = json_decode($contract->data->calc_data);
            if($calc_data->type_multidriver == 0){
                $min_age = $calc_data->birthdate_year;
                $min_exp = $calc_data->exp_year;
                $dtl = '??';
            }elseif($calc_data->type_multidriver == 0){
                $dtm = '??';
            }
        }

        $drivers = [];

        if((int)$contract->data->is_multidriver == 0){

            /*
            $_d_min_birthyear = $contract->contracts_insurers()->orderBy('birthyear', 'asc')->first();
            $_d_min_expyear = $contract->contracts_insurers()->orderBy('expyear', 'asc')->first();

            if($_d_min_birthyear) $min_age = $_d_min_birthyear->birthyear;
            if($_d_min_expyear) $min_exp = $_d_min_expyear->expyear;
            */




            $drivers = self::getDriversPrint($contract->contracts_insurers);

        }else{

            $drivers[] = [
                'driver_num' => '-',
                "driver_fio" => '-',
                "driver_sex" => '-',
                "driver_bday" => '-',
                "driver_doc_serie" => '',
                "driver_doc_number" => '-',
                "driver_doc_date" => '',
                "driver_exp_date" => '-',
                "driver_expyear" => '-',
            ];

        }


        $official_discount_total = 0;
        $quantity = 1;
        if($pays = $contract->payments){
            $quantity = sizeof($pays);
            $payment_info = '';
            foreach ($pays as $key => $pay_item){
                if($key == 0){
                    $payment_type = $pay_item->payment_type;
                }
                $payment_info .="\n";
                $payment_info .= '???????????? '. ($key+1) . ': '. titleFloatFormat($pay_item->invoice_payment_total) . ' ???? ';
                $payment_info .= $key ? setDateTimeFormatRu($pay_item->payment_data,1) : setDateTimeFormatRu($pay_item->payment_data . ' +3 days',1);
                $payment_info .="\n";


                $official_discount_total += getFloatFormat($pay_item->official_discount_total);

            }
        }
        $payment_procedure = $quantity == 1 ? '???????????????????????????? ????????????' : "?? ???????????????????? ??????????????: ???? {$quantity} ??????????????";



        $purpose = VehiclePurpose::PURPOSE[$auto->purpose_id];

        if($contract->program->slug == 'standard'){
            $payment_not_certificates = Standard::PAYMENT_NOT_CERTIFICATES;
            $agregated = Standard::LIMIT_INDEMNITY[$contract->data->limit_indemnity_id];
            $risks = Standard::COATINGS_RISKS;
        }elseif($contract->program->slug == 'drive'){
            $risks = Drive::COATINGS_RISKS;
        }else{
            $risks = Standard::COATINGS_RISKS;
        }

        $risks_data = [];
        $risk_comment = '';
        if($contract->data->franchise_id){
            $franchise = titleFloatFormat($contract->data->franchise_id);
            $franchise .= $contract->data->franchise_number_id == 1 ? ' (??????????????????????)' : ' (c '. $contract->data->franchise_number_id.' ????????????)';
            $risk_comment = $contract->data->franchise_number_id == 2 ? '???? ???????????????? ???????????????? ?????????????????????? ?????????????????????? ?????????????????????? ???????????????? ???? ?????????????? ???????????????????? ???????????? ?? ?????????????? '.titleFloatFormat($contract->data->franchise_id) . '.' : '';
        }else{
            $franchise = '(??????)';
        }

        $calculation_data = \GuzzleHttp\json_decode($contract->calculation->json);
        $sum = $contract->payment_total - $official_discount_total;
        $sum -= ($contract->data->civil_responsibility_sum > 0) ? $calculation_data->info->go->payment_total:0;
        $sum -= ($contract->object_equipment)?$calculation_data->equipment->summ:0;
        $sum -= ($contract->data->ns_type > 0) ? $calculation_data->info->ns->payment_total:0;
        $sum -= ($contract->data->is_gap > 0) ? $calculation_data->info->gap->payment_total:0;

        $risks_data[] = [
            'risk' => '?????????? ('.$risks[$contract->data->coatings_risks_id].')',
            'risk_sum' => titleFloatFormat($contract->insurance_amount),
            'risk_limit' => explode(' ',$agregated)[0],
            'risk_franchise' => $franchise,
            'risk_franchise_size' => titleFloatFormat($sum),
        ];

        if($risks[$contract->data->coatings_risks_id] == '?????????? ?????? - ?????????????????? ??????????????'){
            $risk_comment .= mb_strlen($risk_comment)>0 ? '<w:br/>': '';
            $risk_comment .= '?? ???????????? ???????????????? ???????????????????????? ???????????????????????? ?????? ?? ?????????????????????? ???????????????????????? ???????????????????????? ?? ???????????????? ???????????? ???? ???????????? ?????????????????????????????? ?????????????????????? ???????????????? ?? ?????????????? 10% ???? ?????????????????? ??????????.';
        }

        if($contract->data->civil_responsibility_sum > 0){
            $risks_data[] = [
                'risk' => $calculation_data->info->go->title,
                'risk_sum' => titleFloatFormat($calculation_data->info->go->insurance_amount),
                'risk_limit' => '',
                'risk_franchise' => '?? ?????????????? ???????????? ?????????????? ???? ??????????',
                'risk_franchise_size' => titleFloatFormat($calculation_data->info->go->payment_total),
            ];
        }
        if(sizeof($contract->object_equipment)){
            $pay = 0;
            foreach ($contract->object_equipment as $equipment){
                $pay += getFloatFormat($equipment->payment_total);
            }
            $risks_data[] = [
                'risk' => '???????????????????????????? ????????????????????????',
                'risk_sum' => titleFloatFormat($pay),
                'risk_limit' => '',
                'risk_franchise' => '(??????)',
                'risk_franchise_size' => titleFloatFormat($calculation_data->equipment->summ),
            ];
        }
        if($contract->data->ns_type > 0){
            $risks_data[] = [
                'risk' => '???????????????????? ????????????',
                'risk_sum' => titleFloatFormat($calculation_data->info->ns->insurance_amount),
                'risk_limit' => mb_substr($calculation_data->info->ns->title,3),
                'risk_franchise' => '(??????)',
                'risk_franchise_size' => titleFloatFormat($calculation_data->info->ns->payment_total),
            ];
        }
        if($contract->data->is_gap > 0){
            $risks_data[] = [
                'risk' => $calculation_data->info->gap->title,
                'risk_sum' => titleFloatFormat($calculation_data->info->gap->insurance_amount),
                'risk_limit' => '',
                'risk_franchise' => '(??????)',
                'risk_franchise_size' => titleFloatFormat($calculation_data->info->gap->payment_total),
            ];
        }




        if(isset($contract->agent) && $agent_organization = $contract->agent->organization){
            if(isset($agent_organization->title)){
                $pos = mb_stripos($agent_organization->title,'???????????????????????????? ??????????????????????????????');
                if($pos !== false){
                    $agent_fio = self::getShortFioFromFullName(mb_substr($agent_organization->title,31));
                    $agent_fio_position = '???? '.$agent_fio;
                    $agent_job_position = '???????????????????????????? ??????????????????????????????';
                    if($agent_organization->agent_contract_title && $agent_organization->agent_contract_begin_date)
                        $agent_contract = '???????????????????? ???????????????? ???'.$agent_organization->agent_contract_title.' ???? '.setDateTimeFormatRu($agent_organization->agent_contract_begin_date, 1);
                }
            }
        }

        $car_antitheft_system = '';

        if($auto->anti_theft_system_id){
            $_antitheft_system =  VehicleAntiTheftSystem::query()->where('id', $auto->anti_theft_system_id)->first();
            if($_antitheft_system){
                $car_antitheft_system = $_antitheft_system->title;

            }
        }


        if($contract->id == 6810){
            $car_antitheft_system = '???????? ???????? ?????????????????????? ?????? ?????????????? , ?????? ???? ???? ?????????????????????? ?? ???????????????????????? ?????????????????????? ???????????????????????????? ?????????????? ???????????? ???????????????? ("Platinum")';
        }


        if($contract->id == 7256){
            $car_antitheft_system = '???????? ???????? ?????????????????????? ?????? ?????????????? , ?????? ???? ???? ?????????????????????? ?? ???????????????????????? ?????????????????????? ???????????????????????????? ?????????????? ???????????? ???????????????? "Platinum"';
        }

        $_info_tarif = json_decode($contract->calculation->json);

        if(isset($_info_tarif->text_anti_theft_system) && strlen($_info_tarif->text_anti_theft_system) > 0){
            $car_antitheft_system = $_info_tarif->text_anti_theft_system;
        }

        $pso = '';//?? ????????????????
        $pson = '??';//?????? ??????????????

        if(isset($_info_tarif->is_set_pso)){
            if($_info_tarif->is_set_pso == true){
                $pso = '??';//?? ????????????????
                $pson = '';//?????? ??????????????
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
                'insurance_program_date' => '17.04.2019',
                'insurance_name_full' => '?????? ?????????????????? ???????????????? ??????????',
                'insurance_rekvizit' => '115088, ????????????, 2-?? ???????????????????????? ????????????, ??.18, ??????.8',

                'bi' => ((int)$beneficiary_is_insurant == 0) ? '' : '??',
                'b' => ((int)$beneficiary_is_insurant == 1) ? '' : '??',


                'oi' => $owner_is_insurant ? '??' : '',
                'ob' => $owner_is_beneficiary ? '??' : '',
                'o' => (!$owner_is_insurant && !$owner_is_beneficiary) ? '??' : '',
                'leasing_company' => (!$owner_is_insurant && !$owner_is_beneficiary) ? $owner_company : '',

                'dts' => ($insurer->type == 1 && $staff_drivers) ? '??' : '',
                'dtm' => $dtm,
                'dtp' => ($insurer->type == 1 && $prof_drivers) ? '??' : '',
                'dtl' => $dtl,
                'dtos' => $dtos,
                'dtod' => $dtod,

                'drivers' =>  $drivers,

                'dt_age' => ($insurer->type == 0 || ($insurer->type == 1 && $prof_drivers)) ? $min_age : '',
                'dt_experience' => ($insurer->type == 0 || ($insurer->type == 1 && $prof_drivers)) ? $min_exp : '',

                'car_mark' => $auto->mark->title,
                'car_model' => $auto->model->title,
                'car_year' => $auto->car_year,
                'car_reg_num' => !empty($auto->reg_number)? $auto->reg_number : '',
                'car_vin' => $auto->vin,
                'car_pts_num' => $auto->docserie." ".$auto->docnumber,
                'car_color' => $auto->color_id ? VehicleColor::find($auto->color_id)->title:'',
                'cark' => $auto->count_key ? $auto->count_key : '1',
                'car_antitheft_system' => $car_antitheft_system,
                'pp' => ($purpose == '????????????') ? '??' : '',
                'pc' => ($purpose != '????????????') ? '??' : '',
                'element_equipment_imei' => '',//?????????? ?????????????????????????????? ????????????????????
                'ym' => '',//X ???????? ???????? ?????????????????????? ??????????????
                'year_mileage' => ((int)$auto->mileage > 0)? (int)$auto->mileage : '',//???? ?????????????? ?? ??????
                'ss' => '',//X ???????? ?????????????????????? ???? ?????????????? ????????????
                'pso' => $pso,//?? ????????????????
                'pson' => $pson,//?????? ??????????????
                'risks' => $risks_data,
                'risk_ec' => $contract->data->is_emergency_commissioner ? '??' : '-',
                'risk_ect' => $contract->data->is_emergency_commissioner ? '1' : '-',
                'risk_rc' => $contract->data->is_collection_certificates ? '??' : '-',
                'risk_rct' => $contract->data->is_collection_certificates ? '1' : '-',
                'risk_ra' => $contract->data->is_evacuation ? '??' : '-',
                'risk_rat' => $contract->data->is_evacuation ? '1' : '-',
                'risk_br' => false ? '??' : '-',
                'risk_brt' => false ? '1' : '-',
                'risk_ts' => false ? '??' : '-',
                'risk_tst' => false ? '1' : '-',
                'risk_total' => titleFloatFormat($contract->payment_total-$official_discount_total),
                'risk_comment' => $risk_comment,
                'repair_type' => $contract->data->repair_options_id == 1 ? '???????????? ???? ???????? ???????????????????????? ????????????':'???????????? ???? ???????? ???????????????????????? ???????????? ???? ?????????????????????? ??????????????????????',
                'payment_procedure' => $payment_procedure,
                'pwh' =>  $payment_type != 0 ? '??': '',
                'pwc' => $payment_type == 0 ? '??': '',
                'insurance_dir_name' => '?????????????? ??.??.',
                'insurance_dir_type' => '',
                'capture_insurant_name' => $contract->insurer->title,
                'partner_info' => '?????????????????? ???????????????? ????????????????????????, ???????????????????????????? ?????????????????????????????? ?????????????? ???????????? ????????????????????????, ????????. ??????????: ????????????, ?????????????????????? ????????????????????, 32/2, 1, 1, ?????? 773373800048, ????????. ??????. +7 (000) 000-00-00',
                'payments_info' => $payment_info,
                'payments_comment' => $quantity > 1 ? '?????????????????????? ?????????????????? ?????????????? ?????????????? ?????? ???????????? ??????????????????????' : '' ,
                'bank_information' => $bank_info,

            ],
        ];


        $data['info'] = array_merge($data['info'], self::getInsurersPrint($contract->insurer, 'insurant'));
        $data['info'] = array_merge($data['info'], self::getInsurersPrint($contract->beneficiar, 'beneficiary',$beneficiary_is_insurant));

//dd($data);

        return $data;
    }

    public static function getBeneficiarFullInfoPrint($subject){
        if($subject->type){
            $title = $subject->title.", ?????? ".$subject->inn.", ???????? ".$subject->ogrn;
            if(!empty($subject->data()->bank_id)){
                $title.=", ".Bank::find($subject->data()->bank_id)->title.', ??/?? '.$subject->data()->rs.', ??/?? '.$subject->data()->ks.', ?????? '.$subject->data()->bik;
            }
            $title.= ', '.$subject->data()->address_fact;

        }else{
            $title = $subject->title.", ".$subject->data()->birthdate.", ".$subject->data()->address_register;
        }
        $title.= isset($subject->phone) ? ', '.$subject->phone : '';
        $title.= isset($subject->email) ? ', '.$subject->email : '';
        return $title;
    }

    public static function getInsurersPrint($subject, $key, $needNull = 0){

        if($subject->type){
            $title_ul = $subject->title.", ?????? ".$subject->inn.", ???????? ".$subject->ogrn;
            if(!empty($subject->data()->bank_id)){
                $title_ul.=", ".Bank::find($subject->data()->bank_id)->title.', ??/?? '.$subject->data()->rs.', ??/?? '.$subject->data()->ks.', ?????? '.$subject->data()->bik;
            }
            $title_ul.= ', '.$subject->data()->address_fact;
        }
        $fio = '';
        $country = '';
        $document = '';
       if($subject->type == 0){
            $fio = self::getShortFioFromFullName($subject->title);
            $country = $subject->citizenship_id == 51 ? '????????????' : Country::find($subject->citizenship_id)->title_ru;
            $document = $subject->data()->doc_type == 1165 ? '?????????????? ????' : SubjectsFlDocType::where('isn', $subject->data()->doc_type)->first()->title;
            $document .= isset($subject->doc_serie) ? ' '. $subject->doc_serie : '';
            $document .= isset($subject->doc_number) ? ' '. $subject->doc_number : '';
            $document .= ', ?????????? '. $subject->data()->doc_info . ', ???????? ???????????? '.setDateTimeFormatRu($subject->data()->doc_date,1) ;
       }

       $title = $subject->title;
       if(strlen($subject->comments) > 0){
           $title .= " - {$subject->comments}";
       }

       if(!$needNull) {
           $res = [
               "{$key}_name" => $title,
               "{$key}_citizenship" => $country,
               "{$key}_document" => $subject->type == 0 ? $document : '',
               "{$key}_birthdate" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->birthdate, 1) : '',
               "{$key}_birth_place" => $subject->type == 0 ? $subject->data()->address_born : '',
               "{$key}_inn" => isset($subject->inn) ? $subject->inn : '',
               "{$key}_city" => $subject->type == 0 ? $subject->data()->address_register : '',
               "{$key}_phone" => isset($subject->phone) ? $subject->phone : '',
               "{$key}_email" => isset($subject->email) ? $subject->email : '',

               /*"{$key}_doc_serie" => isset($subject->doc_serie) ? $subject->doc_serie : '',
               "{$key}_doc_number" => isset($subject->doc_number) ? $subject->doc_number : '',
               "{$key}_inn" => isset($subject->inn) ? $subject->inn : '',
               "{$key}_phone" => isset($subject->phone) ? $subject->phone : '',
               "{$key}_email" => isset($subject->email) ? $subject->email : '',
               "{$key}_birthdate" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->birthdate,1) : '',
               "{$key}_address_born" => $subject->type == 0 ? $subject->data()->address_born : '',
               "{$key}_is_resident" => $subject->is_resident == 1 ? '????????????????' : '????????????????????',
               "{$key}_address_register" => $subject->type == 0 ? $subject->data()->address_register : '',
               "{$key}_address_fact" => $subject->type == 0 ? $subject->data()->address_fact : '',
               "{$key}_doc_info" => $subject->type == 0 ? setDateTimeFormatRu($subject->data()->doc_date,1).', '.$subject->data()->doc_info.', '.$subject->data()->doc_office : '',
           */];
       }else{
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
                "driver_num" => ($key+1).'.',
                "driver_fio" => $driver->title,
                "driver_sex" => $driver->sex ? '??????' : '??????',
                "driver_bday" => setDateTimeFormatRu($driver->birthdate, 1),
                "driver_doc_serie" => isset($driver->doc_serie) ? $driver->doc_serie : '',
                "driver_doc_number" => isset($driver->doc_number) ? '???'.$driver->doc_number : '',
                "driver_doc_date" => isset($driver->doc_date) ? setDateTimeFormatRu($driver->doc_date, 1) : '',
                "driver_exp_date" => "C " . setDateTimeFormatRu($driver->exp_date, 1),
                'driver_expyear' => (int)$driver->expyear,
            ];
        }

        return $res;

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


    public static function copy(Contracts $contract, $is_contract_id = null){

        if($is_contract_id && (int)$is_contract_id > 0){
            $new_contract = Contracts::find($is_contract_id);
        }else{
            $new_contract = ContractCreate::create($contract->product, $contract->program, $contract->agent_id);
        }


        $new_contract->insurance_amount = $contract->insurance_amount;

        $subject = Subjects::cloneSubject($contract->insurer);
        $new_contract->insurer_id = $subject->id;

        if($contract->insurer_id == $contract->owner_id){
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