<?php

namespace App\Processes\Operations\GeneralSubjects;



use App\Http\Controllers\General\Subjects\GeneralSubjectsController;
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
use App\Models\Settings\Country;
use App\Services\Integration\VernaControllers\VernaDirectories;
use App\Services\Integration\VernaControllers\VernaSubjects;
use App\Services\Scorings\ContourPrism;
use function GuzzleHttp\Psr7\str;

class GeneralSubjectsInfo {



    public static function getGeneralSubject($subject_id){

        $subject = Subjects::findOrFail($subject_id);

        /*
        if($subject->general_subject_id > 0){
            return $subject->general_subject_id;
        }
        */

        $info = $subject->get_info();

        $data = new \stdClass();
        $data->title = $subject->title;

        if($subject->type == 0){
            $data->birthdate = $info->birthdate;
            $data->sex = $info->sex;
        }

        if($subject->type == 1){
            $data->title = $subject->title;
            $data->inn = $info->inn;
            $data->ogrn = $info->ogrn;
        }

        $hash = self::getHash($subject->type, $data);
        $general = GeneralSubjectsSearch::search_hash($subject->type, $hash);
        if(!$general){
            $general = GeneralSubjectsInfo::createGeneralSubjectHash($subject->type, $hash, $subject->user, $data);
            $general->is_resident = $subject->is_resident;
            $general->citizenship_id = $subject->citizenship_id;
        }

        if($subject->type == 0) {
            $general->lat = (isset($info->fio_lat) ? $info->fio_lat : '');
        }

        $general->email = $subject->email;
        $general->phone = $subject->phone;
        $general->save();

        $up_doc = 0;
        if($info->doc_type){
            $doc = $general->getDocumentsType($info->doc_type,1);
            if($doc && $doc->id > 0){
                if(getDocumentNumberFormat($doc->number) != getDocumentNumberFormat($info->doc_number) || getDocumentNumberFormat($doc->serie) != getDocumentNumberFormat($info->doc_serie)){
                    $doc->is_actual = 0;
                    $doc->is_main = 0;
                    $doc->save();
                    $up_doc = 1;

                }else{
                    $doc->date_issue = getDateFormatEn($info->doc_date);
                    $doc->unit_code = $info->doc_office;
                    $doc->issued = $info->doc_info;
                    $doc->save();
                }
            }else{
                $up_doc = 1;
            }

            if($up_doc == 1){

                $new_doc = $general->checkOrCreateDocumentsType($info->doc_type,$info->doc_serie,$info->doc_number);
                if((int)$new_doc->id <= 0){
                    $new_doc->save();
                }
                $new_doc->update([
                    'general_subject_id' => $general->id,
                    'type_id' => $info->doc_type,
                    'serie' => $info->doc_serie,
                    'number' => $info->doc_number,
                    'date_issue' => $info->doc_date,
                    'unit_code' => $info->doc_office,
                    'issued' => $info->doc_info,
                    'is_main' => 1,
                    'is_actual' => 1,
                ]);

                $gsc = new GeneralSubjectsController();
                $gsc->switchMainDocument($new_doc,$general->documents());
            }
        }


        GeneralSubjectsInfoAddress::createGeneralSubjectAddress($info->toArray(), $general, 0);
        GeneralSubjectsInfoAddress::createGeneralSubjectAddress($info->toArray(), $general, 1);
        GeneralSubjectsInfoAddress::createGeneralSubjectAddress($info->toArray(), $general, 2);

        $subject->general_subject_id = $general->id;
        $subject->save();

        if($subject->type == 1) {
            //ЮЛ



            $general_data = $general->data;
            $general_data->kpp = $info->kpp;
            $general_data->bik = $info->bik;
            $general_data->bank_id = $info->bank_id;
            $general_data->rs = $info->rs;
            $general_data->ks = $info->ks;
            $general_data->full_title = $info->title_full;
            $general_data->okpo = $info->okpo;
            $general_data->okato = $info->okato;
            $general_data->oktmo = $info->oktmo;
            $general_data->okogy = $info->okogy;
            $general_data->okved_code = $info->okved_code;
            $general_data->okfs = $info->okfs;

            $of = GeneralUlOf::where('code', $info->of_code)->get()->first();
            if(!$of){
                GeneralUlOf::create([
                    'code' => $info->of_code,
                    'title' => $info->of_title,
                    'full_title' => $info->of_full_title,
                ]);
            }
            $general_data->of_id = $info->of_code;
            $general_data->save();

            if(strlen($info->manager_fio) > 0 && strlen($info->manager_birthdate) > 0){

                $m_data = new \stdClass();
                $m_data->title = $info->manager_fio;
                $m_data->sex = getSexClient($info->manager_fio);
                $m_data->birthdate = $info->manager_birthdate;
                $m_data->phone = $info->manager_phone;
                $m_data->email = $info->manager_email;

                $manager_hash = GeneralSubjectsInfo::getHash(0, $m_data);
                $manager = GeneralSubjectsSearch::search_hash(0, $manager_hash);

                if(!$manager){
                    $manager = GeneralSubjectsInfo::createGeneralSubjectHash(0, $manager_hash, $subject->user, $m_data);
                    $manager->is_resident = $subject->is_resident;
                    $manager->citizenship_id = $subject->citizenship_id;
                    $manager->save();
                }



                $podft = $manager->podft;
                $podft->main_type_employment_id = 1;
                $podft->general_organization_id = $general->id;
                $podft->job_position = $info->manager_position;
                $podft->job_credentials_id = 3;
                $podft->job_type_activity_id = 1;
                $podft->save();


            }


        }





        if($subject->type == 0) {

            //Проверка из Контр.Призмы на ПОД/ФТ
            //$general
            //VernaSubjects::getKIASGeneralId($general);
        }


        return $subject->general_subject_id;
    }



    public static function createGeneralSubjectPassword($max=8)
    {

        $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $size=StrLen($chars)-1;

        $password=null;

        while($max--) $password.=$chars[rand(0,$size)];

        return $password;
    }

    public static function checkGeneralSubjectEmail($email)
    {
        //

        return true;
    }




    /*
     *
     * Новый функционал
     */


    public static function getHash($type, $data){

        $title = str_replace(' ','', $data->title);
        $title = mb_strtoupper($title, 'UTF-8');

        if($type == 0){
            // FL
            $hash = "$title|".getDateFormatEn($data->birthdate);
        }else{
            //UL
            $hash = "$title|$data->inn";
        }

        return md5($hash);
    }

    public static function createGeneralSubjectHash($type, $hash, $user, $data)
    {

        $password = GeneralSubjectsInfo::createGeneralSubjectPassword();
        $general = new GeneralSubjects();
        $general->title = $data->title;
        $general->label = $data->title;
        if($type == 0){
            $general->status_work_id = 0;
        }
        $general->hash = $hash;
        $general->type_id = $type;
        $general->password = bcrypt(trim($password));

        if(isset($user) && $user){
            $general->user_id = $user->id;
            $general->user_organization_id = $user->organization_id;
            $general->user_parent_id = ($user->parent_id > 0) ? $user->parent_id : $user->id;
            $general->user_curator_id = ($user->curator_id > 0) ? $user->curator_id : $user->id;
        }

        $general->save();

        GeneralSubjectsLogs::setLogs($general->id, 'Создан');

        if($type == 0){ //'fl'

            $general->label = "{$data->title} - ".getDateFormatRu($data->birthdate);

            GeneralSubjectsFl::create([
                'general_subject_id' => $general->id,
                'birthdate' => getDateFormatEn($data->birthdate),
                'sex' => $data->sex,
            ]);

            GeneralPodftFl::create([
                'general_subject_id' => $general->id,
            ]);

            $general->save();
            //GeneralSubjectsInfo::getCheckContourPrism($general);

        }

        if($type == 1){ // 'ul'

            $general->label = "{$data->title} - {$data->inn}";

            GeneralSubjectsUl::create([
                'general_subject_id' => $general->id,
                'inn' => $data->inn,
                'ogrn' => $data->ogrn,
            ]);

            GeneralPodftUl::create([
                'general_subject_id' => $general->id,
            ]);

            $general->save();

            //GeneralSubjectsInfo::getGeneralSubjectInfoOnline($general);

        }

        return $general;
    }


    public static function checkAccessGeneralSubject($general, $user)
    {

        $rolesVisibility = $user->role->rolesVisibility(13);

        if ($rolesVisibility) {

            $visibility = $rolesVisibility->visibility;

            if ($visibility == 0) {//Все
                return true;

            } elseif ($visibility == 1) {//Все в рамках организации

                if($general->user_id == $user->id) return true;
                if($general->user_organization_id == $user->user_organization_id) return true;
                if($general->user_parent_id == $user->id) return true;
                if($general->user_curator_id == $user->id) return true;

            } elseif ($visibility == 2) {//Только свои

                if($general->user_id == $user->id) return true;

            } elseif ($visibility == 3) {//Только свои и своих подчиненных

                if($general->user_id == $user->id) return true;
                if($general->user_parent_id == $user->id) return true;
                if($general->user_curator_id == $user->id) return true;
            }
        } else {
            if($general->user_id == $user->id) return true;
        }

        return false;
    }


    public static function getGeneralSubjectInfoOnline($general)
    {

        //$prism = new ContourPrism();
        //$json_data = $prism->getCompanies($general->data->inn, $general->data->ogrn);
        //$general->json_data = \GuzzleHttp\json_encode($json_data);
        //$general->save();
        //GeneralSubjectsUpdateContourPrism::updateGeneralSubject($general, $json_data);
        //GeneralSubjectsLogs::setLogs($general->id, 'Получение данных из Контур.Призма');

        return true;
    }

    public static function getCheckContourPrism($general)
    {
        $res = (object)['state'=> false, 'msg' => 'Не удалось проверить данные.'];

        if($general->type_id == 0){
            //self::checkContourPrismIndividuals($general);
            $res = (object)['state'=> true, 'msg' => ''];
        }


        return $res;
    }


    public static function checkContourPrismIndividuals($general)
    {


        /*
        $prism = new ContourPrism();

        $json_data = $prism->getIndividuals($general->title, $general->data->birthdate);
        if(isset($json_data) && isset($json_data[0])){
            $json_data = $json_data[0];
        }

        if(isset($json_data->terroristIndividualEntries) && count($json_data->terroristIndividualEntries) > 0){
            $general->status_work_id = 2;
        }


        $general->json_data = \GuzzleHttp\json_encode($json_data);
        $passport = $general->getDocumentsType(1165);
        if($passport && strlen($passport->number) == 6){
            $valid = $prism->getIndividualPassport("{$passport->serie} {$passport->number}");

            if($valid && isset($valid->isInvalid) && $valid->isInvalid == false){
                if($general->status_work_id != 2){
                    $general->status_work_id = 0;
                }
                $passport->is_check = 1;
            }else{
                if($general->status_work_id != 2){
                    $general->status_work_id = 0;//1;
                }
                $passport->is_check = 2;
            }

            $passport->save();
        }


        $general->save();

        GeneralSubjectsLogs::setLogs($general->id, 'Получение данных из Контур.Призма');
        */

        return $general;
    }

    public static function updateGeneralToInsurer($insurer, $doc_type = null)
    {

        $hash = self::getHash(0, $insurer);
        $general = GeneralSubjectsSearch::search_hash(0, $hash);
        if(!$general){
            $general = GeneralSubjectsInfo::createGeneralSubjectHash(0, $hash, null, $insurer);
            $general->is_resident = ($insurer->citizenship_id == 51)?1:0;
            $general->citizenship_id = $insurer->citizenship_id;
        }

        if(strlen($insurer->title_lat) > 0) {
            $general->lat = $insurer->title_lat;
            $general->save();
        }

        if($doc_type && $doc_type > 0){


            $up_doc = 0;
            $doc = $general->getDocumentsType($doc_type);
            if($doc && $doc->id > 0){
                if($doc->number != $insurer->doc_number){
                    $doc->is_actual = 0;
                    $doc->save();
                    $up_doc = 1;
                }
            }else{
                $up_doc = 1;
            }

            if($up_doc == 1){

                GeneralSubjectsDocuments::create([
                    'general_subject_id' => $general->id,
                    'type_id' => $doc_type,
                    'serie' => $insurer->doc_serie,
                    'number' => $insurer->doc_number,
                    'date_issue' => $insurer->doc_date,
                    'unit_code' => '',
                    'issued' => $insurer->doc_info,
                    'is_main' => 0,
                    'is_actual' => 1,
                    'driver_exp_date' => $insurer->exp_date,
                ]);
            }


        }

        $insurer->general_id = $general->id;
        $insurer->save();

        //VernaSubjects::getKIASGeneralId($general);

        return true;
    }

}