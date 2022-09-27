<?php

namespace App\Processes\Tariff\Settings\Product;

use App\Models\Directories\Products\Data\Migrants;
use App\Processes\Operations\Contracts\ContractOnlineProduct;

class TariffMigrants {





    public static function defaultJson(){


        $TARIFF = [
            'programs' => [
                0 => [
                    'title' => 'Медицинские и иные услуги',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => Migrants::PROGRAMS,
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => Migrants::PROGRAMS,
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                1 => [
                    'title' => 'Несчастный случай',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => [1 => 'Несчастный случай'],
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => [1 => 'Несчастный случай'],
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                2 => [
                    'title' => 'Беременность',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => [1 => 'Беременность'],
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => [1 => 'Беременность'],
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                3 => [
                    'title' => 'Диспансеризация',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => [1 => 'Диспансеризация'],
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => [1 => 'Диспансеризация'],
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                4 => [
                    'title' => 'Стоматологическая помощь',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => [1 => 'Стоматологическая помощь'],
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => [1 => 'Стоматологическая помощь'],
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                5 => [
                    'title' => 'Погребение',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => [1 => 'Погребение'],
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => [1 => 'Погребение'],
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                6 => [
                    'title' => 'Транспортировка',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => Migrants::DATE_MONTH,
                        1 => [1 => 'Транспортировка'],
                    ],
                    'limits' => [
                        0 => [1 => 'Страховая сумма'],
                        1 => [1 => 'Транспортировка'],
                    ],
                    'values' => [],
                    'amounts' => [],
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