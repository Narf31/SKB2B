<?php

namespace App\Processes\Scenaries\Contracts\Scorings\Defaults;


use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsScorings;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Services\Scorings\SpectrumData;

class General {


    public static function is_ban($contract)
    {
        $result = new \stdClass();
        $result->is_ban = true;
        $result->text = '';


        //Страхователь
        if($contract->insurer_id > 0){
            $r = self::checkGeneral($contract->insurer->general);
            if($result->is_ban == true){
                $result->is_ban = $r->is_ban;
            }

            if($r->is_ban == false) {
                $result->text .= "Страхователь {$r->text};<br/>";
            }
            $r = null;
        }
        //Собственник
        if((int)$contract->owner_id > 0 && $contract->owner_id!=$contract->insurer_id){

            $r = self::checkGeneral($contract->owner->general);
            if($result->is_ban == true){
                $result->is_ban = $r->is_ban;
            }
            if($r->is_ban == false) {
                $result->text .= "Собственник {$r->text};<br/>";
            }
            $r = null;

        }
        //Выгодоприобретатель
        if((int)$contract->beneficiar_id > 0 && $contract->beneficiar_id!=$contract->insurer_id){
            if($contract->beneficiar_id!=$contract->owner_id){
                $r = self::checkGeneral($contract->beneficiar->general);
                if($result->is_ban == true){
                    $result->is_ban = $r->is_ban;
                }
                if($r->is_ban == false) {
                    $result->text .= "Выгодоприобретатель {$r->text};<br/>";
                }
                $r = null;
            }
        }

        foreach ($contract->contracts_insurers as $insurer){


            if($insurer->general){
                $r = self::checkGeneral($insurer->general);
                if($result->is_ban == true){
                    $result->is_ban = $r->is_ban;
                }
                if($r->is_ban == false) {
                    $result->text .= "Водитель {$r->text};<br/>";
                }
                $r = null;
            }
        }


        return $result;
    }

    public static function checkGeneral($general)
    {
        $result = new \stdClass();
        $result->is_ban = true;
        $result->text = '';

        if(!$general) {
            return $result;
        }


        if($general->type_id == 0){
            $general = GeneralSubjectsInfo::checkContourPrismIndividuals($general);
        }


        if($general->status_work_id == 2){
            $result->is_ban = false;
            $comments = "данный контрагент не прошел проверку, обратитесь к куратору";
            if(strlen($general->comments) > 0){
                $comments = $general->comments;
            }

            $result->text = "{$general->title} - {$comments}";
        }

        if($general->status_work_id == 1){
            $result->is_ban = false;
            $result->text = "{$general->title} - данный контрагент не прошел проверку, обратитесь к куратору";
        }


        return $result;
    }


    public static function checkDrivers($contract)
    {
        $result = new \stdClass();
        $result->scoring = true;
        $result->text = '';

        foreach ($contract->contracts_insurers as $insurer){
            if($insurer->subject){
                if($insurer->subject->general){
                    $r = self::checkDriver($contract, $insurer->subject->general);
                    if($r && isset($r->scoring)){
                        $result->scoring = $r->scoring;
                        if($r->scoring == false) {
                            $result->text .= "Водитель {$r->text};<br/>";
                        }
                    }
                    $r = null;
                }
            }
        }

        return $result;

    }

    public static function checkDriver($contract, $general)
    {
        $result = new \stdClass();
        $result->scoring = true;
        $result->text = '';

        $query_type_id = 'FIO';
        $query = $general->label;

        /*
        $scoringsData = self::getScoringData($contract, $query_type_id, $query, $general);

        if($scoringsData) {
            if ($scoringsData->state_id == 1 && strlen($scoringsData->json_response) > 0) {
                $spectrumData = \GuzzleHttp\json_decode($scoringsData->json_response);
                if(isset($spectrumData->content) && isset($spectrumData->content->check_person)){
                    if((int)$spectrumData->content->check_person->driver_rating->scoring->invert_index != 100){
                        $result->scoring = false;
                        $result->text = "{$general->title} - не прошел скоренговый бал";
                    }
                }

            }
        }
        */


        return $result;
    }


    public static function getScoringData($contract, $query_type_id, $query, $general)
    {
        $scoring = ContractsScorings::getContractsScorings($contract, 3, $query_type_id, $query);
        if($scoring->state_id == 1 && $query_type_id == $scoring->query_type_id && $query == $scoring->query){
            $scoring->is_actual = 1;
        }else{
            $data = new SpectrumData();
            if($data->STATE == true){

                $scoring->state_id = 1;
                $fio_arr = explode(' ', $general->title);
                $patronymic = str_replace(($fio_arr[0].' '.$fio_arr[1].' '), '', $general->title);

                $json_send =[
                    'last_name' => $fio_arr[0],
                    'first_name' => $fio_arr[1],
                    'patronymic' => $patronymic,
                    'birth' => setDateTimeFormatRu($general->data->birthdate, 1)
                ];


                $scoring->json_send = \GuzzleHttp\json_encode($json_send);
                $json_response = $data->getDataInfo($json_send);
                $scoring->json_response = \GuzzleHttp\json_encode($json_response);
                $scoring->is_actual = 1;
            }else{
                $scoring->state_id = 0;
            }
        }

        $scoring->save();

        return $scoring;
    }


    public static function checkSpectrumData($contract)
    {
        $result = [];
        if($contract->insurer->type == 0){
            $result[] = self::getArrFL($contract->insurer->data());
        }

        if($contract->owner->type == 0){
            $result[] = self::getArrFL($contract->owner->data());
        }

        if($contract->beneficiar->type == 0){
            $result[] = self::getArrFL($contract->beneficiar->data());
        }

        foreach ($contract->contracts_insurers as $insurer){
            $result[] = self::getArrFL($insurer);
        }
        $result = unique_multidim_array($result, 'title');


        foreach ($result as $_res){
            self::getInfoScoringData($contract, (object)$_res);
        }



        return \GuzzleHttp\json_encode($result);
    }

    public static function getInfoScoringData($contract, $general, $query_type_id='FIO')
    {

        $scoring = ContractsScorings::getContractsScorings($contract, 3, $query_type_id, $general->title);
        if($scoring->state_id == 1 && $query_type_id == $scoring->query_type_id && $general->title == $scoring->query){
            $scoring->is_actual = 1;
        }else{

            $data = new SpectrumData();
            if($data->STATE == true){

                $scoring->state_id = 0;
                $scoring->info_color = 'white';
                $scoring->info_val = 'Нет данных';
                $fio_arr = explode(' ', $general->title);
                $patronymic = str_replace(($fio_arr[0].' '.$fio_arr[1].' '), '', $general->title);

                $json_send =[
                    'last_name' => $fio_arr[0],
                    'first_name' => $fio_arr[1],
                    'patronymic' => $patronymic,
                    'birth' => setDateTimeFormatRu($general->birthdate, 1)
                ];


                $scoring->json_send = \GuzzleHttp\json_encode($json_send);
                $json_response = $data->getDataInfo($json_send);
                if(isset($json_response) && isset($json_response->content)
                    && isset($json_response->content->check_person)
                    && isset($json_response->content->check_person->driver_rating)
                    && isset($json_response->content->check_person->driver_rating->scoring)
                    && isset($json_response->content->check_person->driver_rating->scoring->index)){
                    $scoring->state_id = 1;
                    $scoring->info_color = mb_strtolower($json_response->content->check_person->driver_rating->scoring->index_color);
                    $scoring->info_val = $json_response->content->check_person->driver_rating->scoring->invert_index;

                }

                $scoring->json_response = \GuzzleHttp\json_encode($json_response);
                $scoring->is_actual = 1;
            }else{
                $scoring->state_id = 0;
            }


        }

        $scoring->save();

        return $scoring;
    }


    public static function getArrFL($subject){

        return ['title' => (isset($subject->fio)) ? $subject->fio : $subject->title, 'birthdate' => setDateTimeFormatRu($subject->birthdate, 1), 'scoring' => '', 'color' => ''];
    }




}