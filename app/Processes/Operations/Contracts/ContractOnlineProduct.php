<?php

namespace App\Processes\Operations\Contracts;


use Illuminate\Support\Str;

class ContractOnlineProduct{


    public static function get_online_product_class($contract){

        $class_name = self::ucfirst($contract->product->slug);
        $online_product_class = "App\\Processes\\Scenaries\\Contracts\\Products\\{$class_name}";
        if(class_exists($online_product_class)){

            return $online_product_class;
        }

        return false;

    }


    public static function ucfirst($string)
    {
        $name = '';
        $strArr = explode('_', $string);
        foreach ($strArr as $str){
            $name .=Str::upper(Str::substr($str, 0, 1)).Str::substr($str, 1);
        }

        return $name;
    }

}