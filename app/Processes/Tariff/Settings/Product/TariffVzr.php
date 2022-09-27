<?php

namespace App\Processes\Tariff\Settings\Product;

use App\Models\Directories\Products\Data\VZR;
use App\Processes\Operations\Contracts\ContractOnlineProduct;

class TariffVzr {





    public static function defaultJson(){

        $TARIFF = [
            'programs' => [
                0 => [
                    'title' => 'Однократный',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::PROGRAMS[1],
                        1 => VZR::AMOUNT[1],
                    ],
                    'values' => [],
                    'discount' => [],
                    'allowances' => [],
                ],
                1 => [
                    'title' => 'Годовой',
                    'categorys_tab_title' => 'Дней прибывания',
                    'categorys' => [
                        0 => VZR::AMOUNT[2],
                        1 => VZR::DAY_TO,
                    ],
                    'values' => [],
                    'discount' => [],
                    'allowances' => [],
                ],
                2 => [
                    'title' => 'Задержка рейса',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::FLIGHT_DELAY_PROGRAM,
                        1 => VZR::FLIGHT_DELAY_AMOUNT,
                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],
                3 => [
                    'title' => 'Опоздание на рейс',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::MISSED_FLIGHT_AMOUNT,
                        1 => [1 => 'ОР'],
                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],
                4 => [
                    'title' => 'Багаж',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::BAGGAGE_AMOUNT,
                        1 => [1 => 'Б'],
                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],
                5 => [
                    'title' => 'Отмена поездки',
                    'categorys_tab_title' => 'Тариф',
                    'categorys' => [
                        0 => VZR::CANCEL_TRIP_PROGRAM,
                        1 => [1 => 'Без франщизы', 2 => 'С франшизой'],
                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],
                6 => [
                    'title' => 'Гражданская ответственность',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::CIVIL_RESPONSIBILITY_AMOUNT,
                        1 => [1 => 'ГО'],
                    ],
                    'values' => [],
                    'insurers' => 'day',
                    'discount' => [],
                    'allowances' => [],
                ],
                7 => [
                    'title' => 'Юридическая помощь',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::LEGAL_AID_AMOUNT,
                        1 => [1 => 'ЮП'],
                    ],
                    'values' => [],
                    'insurers' => 'day',
                    'discount' => [],
                    'allowances' => [],
                ],
                8 => [
                    'title' => 'Отмена экскурсии',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::CANCEL_TOUR_AMOUNT,
                        1 => [1 => 'ОЭ'],
                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],
                9 => [
                    'title' => 'Несчастный случай',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => VZR::NS_AMOUNT,
                        1 => [1 => 'НС'],
                    ],
                    'values' => [],
                    'insurers' => 'day',
                    'discount' => [],
                    'allowances' => [],
                ],

                10 => [
                    'title' => 'Спорт',
                    'categorys_tab_title' => 'Тариф',
                    'categorys' => [
                        0 => VZR::SPORTS,
                        1 => [1 => 'Тариф'],

                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],

                11 => [
                    'title' => 'Профессия',
                    'categorys_tab_title' => 'Тариф',
                    'categorys' => [
                        0 => VZR::PROFESSIONS,
                        1 => [1 => 'Тариф'],

                    ],
                    'values' => [],
                    'insurers' => 'all',
                    'discount' => [],
                    'allowances' => [],
                ],

                12 => [
                    'title' => 'Риски',
                    'categorys_tab_title' => 'Тариф',
                    'categorys' => [

                        0 => [1 => 'Тариф'],
                        1 => VZR::OPTIONS,

                    ],
                    'values' => [],
                    'insurers' => 'all',
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

        if(!$json) $json = TariffVzr::defaultJson();

        $json['programs'][$request->program]['values'] = $request->value;

        if(isset($request->discount))
        {
            $json['programs'][$request->program]['discount'] = $request->discount;
        }else{
            $json['programs'][$request->program]['discount'] = [];
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