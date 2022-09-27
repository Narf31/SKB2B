<?php

namespace App\Processes\Scenaries\Contracts\Scorings\Defaults;


use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsScorings;
use App\Services\Scorings\AudaTex;
use App\Services\Scorings\AutoCod;

class Auto {


    public static function is_ban($contract)
    {
        $result = new \stdClass();
        $result->is_ban = true;
        $result->text = '';
        return $result;


        $auto = $contract->object_insurer->data();
        $scoringsData = self::getDataInfo($contract, $auto);

        if($scoringsData){
            if($scoringsData->state_id == 1 && strlen($scoringsData->json_response) > 0){
                $autoCod = \GuzzleHttp\json_decode($scoringsData->json_response);

                if($autoCod && isset($autoCod->content)) {

                    /*
                    //Проверка на Такси
                    if ($autoCod->content->taxi->used_in_taxi == true) {
                        $result->is_ban = false;
                        $result->text .= "Такси - запрет на страхования;<br/>";
                    }
                    */

                    //Проверка года выпуска
                    if (isset($autoCod->content->tech_data) && (int)$autoCod->content->tech_data->year != (int)$auto->car_year) {
                        $result->is_ban = false;
                        $result->text .= "Некоректный год выпуска {$autoCod->content->tech_data->year};<br/>";
                    }

                    if (isset($autoCod->content->tech_data) && self::comparisonVal($autoCod->content->tech_data->engine->volume, $auto->volume) == false) {
                        $result->is_ban = false;
                        $result->text .= "Неверна указана Объём (см3) - " . titleFloatFormat($autoCod->content->tech_data->engine->volume) . ";<br/>";
                    }

                    if (isset($autoCod->content->tech_data) && self::comparisonVal($autoCod->content->tech_data->engine->power->hp, $auto->power) == false) {
                        $result->is_ban = false;
                        $result->text .= "Неверна указана Мощность (л.с.) - " . titleFloatFormat($autoCod->content->tech_data->engine->power->hp) . ";<br/>";
                    }
                }
            }
        }


        return $result;
    }



    public static function repairEffects($contract)
    {

        $result = new \stdClass();
        $result->scoring = true;
        $result->text = '';
        return $result;

        $auto = $contract->object_insurer->data();
        $scoringsData = self::getDataInfoRepair($contract, $auto);

        if($scoringsData) {
            if ($scoringsData->state_id == 1 && strlen($scoringsData->json_response) > 0) {
                $audaTex = \GuzzleHttp\json_decode($scoringsData->json_response);
                if($audaTex && isset($audaTex->AudaHistoryHeader)){

                    $price = 0;
                    $size = 0;

                    foreach ($audaTex->AudaHistoryHeader as $history){
                        $size++;
                        $price = getFloatFormat($price)+getFloatFormat($history->TotalCost);
                    }
                    if($size > 0 && $price > 0){
                        $result->scoring = false;
                        $result->text = "Кол-во ремонтных работ {$size} на общую сумму ".titleFloatFormat($price).";<br/>";
                    }
                }
            }
        }

        return $result;
    }


    public static function getDataInfoRepair($contract, $auto)
    {
        $query_type_id = null;
        $query = null;
        return null;


        if($auto->vin != 'ОТСУТСТВУЕТ' && strlen($auto->vin) > 0){
            $query_type_id = 'VIN';
            $query = $auto->vin;
        }

        if($query_type_id != null && $query != null) {
            $scoring = ContractsScorings::getContractsScorings($contract, 2, $query_type_id, $query);

            if($scoring->state_id == 1 && $query_type_id == $scoring->query_type_id && $query == $scoring->query){
                $scoring->is_actual = 1;
            }else{
                $data = new AudaTex();
                if($data->STATE == true){
                    $scoring->state_id = 1;

                    $json_send = [
                        $query_type_id     => (string) $query,
                    ];

                    $scoring->json_send = \GuzzleHttp\json_encode($json_send);
                    $json_response = $data->getDataInfo($query);
                    $scoring->json_response = \GuzzleHttp\json_encode($json_response);
                    $scoring->is_actual = 1;
                }else{
                    $scoring->state_id = 0;
                }
            }

            $scoring->save();
            return $scoring;

        }

        return null;
    }

    public static function getDataInfo($contract, $auto)
    {
        $query_type_id = null;
        $query = null;
        return null;

        if($auto->vin == 'ОТСУТСТВУЕТ' && strlen($auto->reg_number) > 0){
            $query_type_id = 'GRZ';
            $query = $auto->reg_number;
        }elseif($auto->vin != 'ОТСУТСТВУЕТ' && strlen($auto->vin) > 0){
            $query_type_id = 'VIN';
            $query = $auto->vin;
        }

        if($query_type_id != null && $query != null){
            $scoring = ContractsScorings::getContractsScorings($contract, 1, $query_type_id, $query);

            if($scoring->state_id == 1 && $query_type_id == $scoring->query_type_id && $query == $scoring->query){
                $scoring->is_actual = 1;
            }else{
                $data = new AutoCod();
                if($data->STATE == true){
                    $scoring->state_id = 1;
                    $json_send = [
                        'queryType' => (string) $query_type_id,
                        'query'     => (string) $query,
                    ];
                    $scoring->json_send = \GuzzleHttp\json_encode($json_send);
                    $json_response = $data->getDataInfo($query, $query_type_id);
                    $scoring->json_response = \GuzzleHttp\json_encode($json_response);
                    $scoring->is_actual = 1;
                }else{
                    $scoring->state_id = 0;
                }
            }

            $scoring->save();
            return $scoring;
        }

        return null;
    }


    public static function comparisonVal($set_val, $check_val)
    {
        $MIN = (int)getFloatFormat(getFloatFormat($set_val)-10);
        $MAX = (int)getFloatFormat(getFloatFormat($set_val)+10);
        $check_val = (int)getFloatFormat($check_val);

        if($MIN <= $check_val && $MAX >= $check_val){
            return true;
        }
        return false;
    }




}