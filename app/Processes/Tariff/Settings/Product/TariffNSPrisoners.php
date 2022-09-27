<?php

namespace App\Processes\Tariff\Settings\Product;

use App\Models\Directories\Products\Data\Migrants;
use App\Models\Directories\Products\Data\NSPrisoners;
use App\Processes\Operations\Contracts\ContractOnlineProduct;

class TariffNSPrisoners {



    public static function defaultJson(){


        $TARIFF = [
            'programs' => [
                0 => [
                    'title' => 'Несчастный случай',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => [1 => 'Несчастный случай'],
                        1 => NSPrisoners::INSURANCE_AMOUNT_NS,
                    ],
                    'values' => [],
                    'amounts' => [],
                    'discount' => [],
                    'allowances' => [],
                ],

                1 => [
                    'title' => 'Туберкулез',
                    'categorys_tab_title' => 'Программа',
                    'categorys' => [
                        0 => [1 => 'Туберкулез'],
                        1 => NSPrisoners::INSURANCE_AMOUNT_TUBERCULOSIS,
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




    public static function saveTariff($json, $request)
    {

        if(!$json) $json = self::defaultJson();

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


}