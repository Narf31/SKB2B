<?php

namespace App\Processes\Validators\Contracts\View;


use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Tariff\Settings\Product\TariffMigrants;

class Migrants {


    const checkArr = [
        [ 'id'=>'1', 'title'=>'ns' ],
        [ 'id'=>'2', 'title'=>'pregnancy' ],
        [ 'id'=>'3', 'title'=>'clinical_examination' ],
        [ 'id'=>'4', 'title'=>'dental_care' ],
        [ 'id'=>'5', 'title'=>'interment' ],
        [ 'id'=>'6', 'title'=>'transportation' ],
    ];

    public function __construct()
    {

    }


    public function getArrayConfig($contract, $data)
    {
        $disabled = [];

        $tariff = CalcMigrants::getTariff($contract);

        $date_contract = (object)$data->contract;
        $date_migrants = (object)$date_contract->migrants;
        $date_month = $date_migrants->date_month;


        foreach (self::checkArr as $check){
            $programs = $tariff['programs'][$check['id']];
            if($this->checkTariff($programs['values'], $date_month) == false){
                $disabled[] = $check['title'];
            }
        }

        return $disabled;
    }


    public function checkTariff($values, $date_month)
    {

        $base_coefficient = TariffMigrants::getTariffValue($values, $date_month, 1);
        $base = getFloatFormat($base_coefficient);
        if($base > 0){
            return true;
        }
        return false;
    }

    public function getValidDataToMatching($contract)
    {
        $result = new \stdClass();
        $result->state = true;
        $result->errors = ['errors' => []];


        return $result;
    }

}