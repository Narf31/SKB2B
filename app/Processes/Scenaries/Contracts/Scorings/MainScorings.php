<?php

namespace App\Processes\Scenaries\Contracts\Scorings;


use App\Models\Contracts\Contracts;
use Illuminate\Support\Str;

class MainScorings {

    public static function scoring(Contracts $contract)
    {
        if($product_class = self::get_online_product_class($contract)){

            $contract = Contracts::find($contract->id);
            return $product_class::scoring($contract);
        }
        return true;
    }


    public static function get_online_product_class($contract){

        $class_name = self::ucfirst($contract->product->slug);
        $product_class = "App\\Processes\\Scenaries\\Contracts\\Scorings\\Products\\{$class_name}";
        if(class_exists($product_class)){
            return $product_class;
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