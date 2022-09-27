<?php

namespace App\Processes\Tariff\Settings\Product;

use App\Models\Directories\Products\Data\Migrants;
use App\Processes\Operations\Contracts\ContractOnlineProduct;

class TariffDefault {



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