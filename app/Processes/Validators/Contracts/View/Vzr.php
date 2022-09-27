<?php

namespace App\Processes\Validators\Contracts\View;


use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Tariff\Settings\Product\TariffMigrants;

class Vzr {



    public function __construct()
    {

    }


    public function getArrayConfig($contract, $data)
    {
        $disabled = [];


        $tariff = CalcVzr::getTariff($contract);
        $date_contract = (object)$data->contract;
        $date_vzr = (object)$date_contract->vzr;

        if((int)$date_vzr->type_agr_id == 1){
            $programs = $tariff['programs'][0];
            $prog_id = $date_vzr->programs[1];

            foreach ($programs['values'][$prog_id] as $key => $amount){
                if(getFloatFormat($amount) > 0){
                    $disabled[$key] = titleFloatFormat($key);
                }
            }

        }


        return $disabled;
    }

    public function getValidDataToMatching($contract)
    {
        $result = new \stdClass();
        $result->state = true;
        $result->errors = ['errors' => []];


        return $result;
    }


}