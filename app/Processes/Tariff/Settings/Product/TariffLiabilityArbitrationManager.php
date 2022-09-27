<?php

namespace App\Processes\Tariff\Settings\Product;

use App\Models\Directories\Products\Data\LiabilityArbitrationManager;
use App\Models\Directories\Products\Data\Migrants;
use App\Processes\Operations\Contracts\ContractOnlineProduct;

class TariffLiabilityArbitrationManager {





    public static function defaultJson(){


        $TARIFF = [
            'programs' => [
                'yearly' => [
                    'title' => 'Годовой',
                    'categorys_tab_title' => 'Кол-во текущих процедур',
                    'categorys' => [
                        0 => [1 => 'Годовой'],
                        1 => LiabilityArbitrationManager::CURRENT_PROCEDURES,
                    ],
                    'values' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                'procedural' => [
                    'title' => 'Процедурный',
                    'categorys_tab_title' => 'Кол-во месяцов',
                    'categorys' => [
                        0 => [1 => 'Процедурный'],
                        1 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    ],
                    'values' => [],
                    'discount' => [],
                    'allowances' => [],
                ],


            ],
        ];


        return $TARIFF;
    }



    public static function getTariffValue($values, $param1, $param2)
    {
        if(isset($values)){
            if(isset($values[$param1])){
                if(isset($values[$param1][$param2])){
                    return $values[$param1][$param2];
                }
            }
        }

        return '';
    }


    public static function saveTariff($json, $request)
    {

        if(!$json) $json = TariffMigrants::defaultJson();

        $json['programs'][$request->program]['values'] = $request->value;

        if(isset($request->discount))
        {
            $json['programs'][$request->program]['discount'] = $request->discount;
        }else{
            $json['programs'][$request->program]['discount'] = [];
        }

        if(isset($request->amount))
        {
            $json['programs'][$request->program]['amounts'] = $request->amount;
        }else{
            $json['programs'][$request->program]['amounts'] = [];
        }

        if(isset($request->allowances))
        {
            $json['programs'][$request->program]['allowances'] = $request->allowances;
        }else{
            $json['programs'][$request->program]['allowances'] = [];
        }

        return $json;
    }


    public static function getTariffDiscontTextJson($json)
    {
        if(isset($json['discount'])){
            return \GuzzleHttp\json_encode($json['discount']);
        }
        return \GuzzleHttp\json_encode([]);
    }

    public static function getTariffAllowancesTextJson($json)
    {
        if(isset($json['allowances'])){
            return \GuzzleHttp\json_encode($json['allowances']);
        }
        return \GuzzleHttp\json_encode([]);
    }

}