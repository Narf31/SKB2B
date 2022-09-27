<?php

namespace App\Processes\Operations\GeneralSubjects;



use App\Models\Clients\GeneralFounders;
use App\Models\Clients\GeneralPodftFl;
use App\Models\Clients\GeneralPodftUl;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Clients\GeneralSubjectsUl;
use App\Models\Clients\GeneralUlOf;
use App\Models\Contracts\Subjects;
use App\Services\DaData\DaDataGetInfo;
use App\Services\Scorings\ContourPrism;

class GeneralSubjectsUpdateContourPrism {



    public static function updateGeneralSubject($general, $data){



        $dadata = new DaDataGetInfo();

        if(isset($data) && isset($data[0])){
            $data = $data[0];
        }

        $general->title = $data->basicName;
        $general->inn = $data->inn;

        $info = $general->data;
        $info->inn = $data->inn;
        $info->ogrn = $data->ogrn;

        if(isset($data->okpo)) $info->okpo = $data->okpo;
        if(isset($data->okato)) $info->okato = $data->okato;
        if(isset($data->oktmo)) $info->oktmo = $data->oktmo;



        if(isset($data->phones) && count($data->phones) > 0){
            $phone = $data->phones[(count($data->phones)-1)];
            $general->phone = setPhoneNumberFormat($phone, '+7 (ddd) ddd-dd-dd');
        }

        $info->date_orgn = getDateFormatEn($data->registrationDate);
        $info->place_registration = $data->nalogName;

        if(isset($data->principalOkved) && isset($data->principalOkved->code)){
            $info->okved_code = $data->principalOkved->code;
            $info->okved_title = $data->principalOkved->name;
        }

        if(isset($data->complementaryOkveds) && count($data->complementaryOkveds) > 0){
            $info->okved_complementary = \GuzzleHttp\json_encode($data->complementaryOkveds);
        }

        if(isset($data->legalForm) && isset($data->legalForm->code)){

            $of = GeneralUlOf::where('code', $data->legalForm->code)->get()->first();
            if(!$of){
                $hash = GeneralUlOf::getHesh($data->legalForm->name);
                $_g = GeneralUlOf::getFindHesh($hash);
                if($_g){
                    $_g->code = $data->legalForm->code;
                    $_g->save();
                    $of = $_g;

                }else{
                    $of = GeneralUlOf::create([
                        'code' => $data->legalForm->code,
                        'full_title' => $data->legalForm->name,
                    ]);
                }
            }
            $info->of_id = $of->code;
        }

        if(isset($data->ul)){
            $data = $data->ul;
            $info->full_title = $data->legalName->full;
            $info->full_title_en = '';
            $info->kpp = $data->kpp;



            if(isset($data->statedCapital) && isset($data->statedCapital->sum)){
                $info->share_capital = getFloatFormat($data->statedCapital->sum);
            }else{
                $info->share_capital = 0;
            }

        }
        $info->save();



        if(isset($data->address))
        {
            $address = "{$data->address->zipCode} {$data->address->regionName} {$data->address->city} {$data->address->settlement} {$data->address->street} {$data->address->house} {$data->address->bulk} {$data->address->flat}";


            $res = $dadata->getAddress($address, 1);
            if(isset($res) && isset($res->suggestions) && isset($res->suggestions[0])){
                $general_address_reg = $general->getAddressType(1);
                $general_address_fact = $general->getAddressType(2);
                $suggestions = $res->suggestions[0];

                $_address = [
                    'address' => $suggestions->value,
                    'kladr' => $suggestions->data->kladr_id,
                    'fias_code' => $suggestions->data->fias_code,
                    'fias_id' => $suggestions->data->fias_id,
                    'okato' => $suggestions->data->okato,
                    'zip' => $suggestions->data->postal_code,
                    'region' => $suggestions->data->region,
                    'city' => $suggestions->data->city,
                    'city_kladr_id' => $suggestions->data->city_kladr_id,
                    'street' => $suggestions->data->street,
                    'house' => $suggestions->data->house,
                    'block' => $suggestions->data->block,
                    'flat' => $suggestions->data->flat,
                ];

                if((int)$general_address_reg->id <= 0){
                    $general_address_reg->save();
                    $general_address_reg->update($_address);
                }
                if((int)$general_address_fact->id <= 0){
                    $general_address_fact->save();
                    $general_address_fact->update($_address);
                }
            }

        }

        if($info->of_id == 50102){

            $general->title = "ИП {$general->title}";

            GeneralFounders::where('general_subject_id', $general->id)->where('type_id', 1)->delete();
            GeneralFounders::where('general_subject_id', $general->id)->where('type_id', 2)->delete();
            $general_founders_id = self::searchGeneral($data->basicName, '', '', 0, $general->user);
            if($general_founders_id > 0){
                GeneralFounders::create([
                    'type_id' => 1,
                    'general_subject_id' => $general->id,
                    'general_founders_id' => $general_founders_id,
                    'share' => 100,
                    'share_sum' => $info->share_capital,
                ]);
                GeneralFounders::create([
                    'type_id' => 2,
                    'general_subject_id' => $general->id,
                    'general_founders_id' => $general_founders_id,
                    'share' => 100,
                    'share_sum' => $info->share_capital,
                ]);
            }

            $general_head = self::searchGeneral($data->basicName, '', '',0, $general->user, 0);
            if($general_head){
                $podft = $general_head->podft;
                $podft->main_type_employment_id = 2;
                $podft->general_organization_id = $general->id;
                $podft->job_position = 'Генеральный Директор';
                $podft->job_credentials_id = 3;
                $podft->job_type_activity_id = 1;
                $podft->save();
            }
        }


        if(isset($data->ulFounders)){

            GeneralFounders::where('general_subject_id', $general->id)->where('type_id', 1)->delete();


            if(isset($data->ulFounders->ulFlFounders) && count($data->ulFounders->ulFlFounders) > 0)
            {

                $ulFlFounders = $data->ulFounders->ulFlFounders;
                foreach ($ulFlFounders as $founder){
                    $inn = isset($founder->inn)?$founder->inn:'';
                    $general_founders_id = self::searchGeneral($founder->fio, $inn, '', 0, $general->user);
                    if($general_founders_id > 0){
                        GeneralFounders::create([
                            'type_id' => 1,
                            'general_subject_id' => $general->id,
                            'general_founders_id' => $general_founders_id,
                            'share' => getFloatFormat($founder->share),
                            'share_sum' => getFloatFormat($founder->shareSum),
                        ]);
                    }
                }


            }

            if(isset($data->ulFounders->ulUlFounders) && count($data->ulFounders->ulUlFounders) > 0)
            {
                $ulUlFounders = $data->ulFounders->ulUlFounders;

                foreach ($ulUlFounders as $founder){

                    $inn = isset($founder->inn)?$founder->inn:'';
                    $ogrn = isset($founder->ogrn)?$founder->ogrn:'';

                    $general_founders_id = self::searchGeneral($founder->fullName, $inn, $ogrn, 1, $general->user);
                    GeneralFounders::create([
                        'type_id' => 1,
                        'general_subject_id' => $general->id,
                        'general_founders_id' => $general_founders_id,
                        'share' => getFloatFormat($founder->share),
                        'share_sum' => getFloatFormat($founder->shareSum),
                    ]);




                }


            }




        }



        if(isset($data->ulBeneficials)){

            GeneralFounders::where('general_subject_id', $general->id)->where('type_id', 2)->delete();

            if(isset($data->ulBeneficials->ulFlBeneficials) && count($data->ulBeneficials->ulFlBeneficials) > 0)
            {
                $ulFlFounders = $data->ulBeneficials->ulFlBeneficials;
                foreach ($ulFlFounders as $founder){
                    $inn = isset($founder->inn)?$founder->inn:'';
                    $general_founders_id = self::searchGeneral($founder->fio, $inn, '',0, $general->user);
                    if($general_founders_id > 0){
                        GeneralFounders::create([
                            'type_id' => 2,
                            'general_subject_id' => $general->id,
                            'general_founders_id' => $general_founders_id,
                            'share' => getFloatFormat($founder->share),
                            'share_sum' => getFloatFormat($founder->shareSum),
                        ]);
                    }
                }
            }

            if(isset($data->ulBeneficials->ulUlBeneficials) && count($data->ulBeneficials->ulUlBeneficials) > 0)
            {
                $ulFlFounders = $data->ulBeneficials->ulUlBeneficials;
                foreach ($ulFlFounders as $founder){
                    $inn = isset($founder->inn)?$founder->inn:'';
                    $ogrn = isset($founder->ogrn)?$founder->ogrn:'';
                    $general_founders_id = self::searchGeneral($founder->fio, $inn, $ogrn,1, $general->user);
                    if($general_founders_id > 0){
                        GeneralFounders::create([
                            'type_id' => 2,
                            'general_subject_id' => $general->id,
                            'general_founders_id' => $general_founders_id,
                            'share' => getFloatFormat($founder->share),
                            'share_sum' => getFloatFormat($founder->shareSum),
                        ]);
                    }
                }
            }


        }


        if(isset($data->heads)){
            foreach($data->heads as $heads){
                $inn = isset($heads->inn)?$heads->inn:'';
                $general_head = self::searchGeneral($heads->fio, $inn, '',0, $general->user, 0);
                if($general_head){
                    $podft = $general_head->podft;
                    $podft->main_type_employment_id = 1;
                    $podft->general_organization_id = $general->id;
                    $podft->job_position = $heads->position;
                    $podft->save();
                }
            }

        }


        $general->save();




        return true;
    }



    public static function searchGeneral($title, $inn, $ogrn, $type, $user, $is_id = 1)
    {
        $data = new \stdClass();
        $data->title = $title;
        $data->inn = $inn;

        if($type == 0){
            $data->birthdate = '0000-00-00';
            $data->sex = 0;
            $_a = mb_substr($title, -1);
            if($_a == 'а') $data->sex = 1;
        }else{
            $data->ogrn = $ogrn;
        }

        $general = GeneralSubjects::where('type_id', $type)->where('title', $title)->where('inn', $inn)->get()->first();
        if(!$general){
            $hash = GeneralSubjectsInfo::getHash($type, $data);
            $general = GeneralSubjectsInfo::createGeneralSubjectHash($type, $hash, $user, $data);
            $general->inn = $inn;
            $general->save();
            $info = $general->data;
        }

        if($is_id == 1){
            return $general->id;
        }

        return $general;
    }

}