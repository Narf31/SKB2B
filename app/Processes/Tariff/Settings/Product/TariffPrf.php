<?php

namespace App\Processes\Tariff\Settings\Product;

use App\Models\Directories\Products\Data\PRF;
use App\Processes\Operations\Contracts\ContractOnlineProduct;

class TariffPrf {



    public static function defaultJson(){

        $TARIFF = [
            'programs' => [
                0 => [
                    'title' => 'По России',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => PRF::PROGRAMS,
                        1 => PRF::AMOUNT,
                    ],
                    'values' => [],
                    'insurers' => 'day',
                    'discount' => [],
                    'allowances' => [],
                ],

                1 => [
                    'title' => 'Несчастный случай',
                    'categorys_tab_title' => 'Страховая сумма',
                    'categorys' => [
                        0 => PRF::NS_AMOUNT,
                        1 => [1 => 'НС'],
                    ],
                    'values' => [],
                    'insurers' => 'day',
                    'discount' => [],
                    'allowances' => [],
                ],

                2 => [
                    'title' => 'Риски',
                    'categorys_tab_title' => 'Тариф',
                    'categorys' => [

                        0 => [1 => 'Тариф'],
                        1 => PRF::OPTIONS,

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
        return TariffDefault::getTariffValue($values, $param1, $param2);
    }


    public static function saveTariff($json, $request)
    {
        if(!$json) $json = TariffPrf::defaultJson();
        return TariffDefault::saveTariff($json, $request);
    }


    public static function getTariffDiscontTextJson($json)
    {
        return TariffDefault::getTariffDiscontTextJson($json);
    }

    public static function getTariffAllowancesTextJson($json)
    {
        return TariffDefault::getTariffAllowancesTextJson($json);
    }

}