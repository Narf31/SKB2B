<?php

namespace App\Processes\Scenaries\Contracts\Matchings;

use App\Models\Directories\HoldKvMatching;
use Illuminate\Support\Str;

class MatchingsContract {



    public static function check($contract){

        if($_class = self::get_online_product_class($contract)){
            return $_class::check($contract);
        }
        return true;
    }


    public static function checkStatus($contract){


        if($contract->calculation->matching && $contract->calculation->matching->status_id == 2) return true;
        if($contract->matching_underwriter && $contract->matching_underwriter->status_id == 2) return true;
        if($contract->matching_sb && $contract->matching_sb->status_id == 2) return true;
        return false;
    }


    public static function editTariff($contract, $tariff, $is_contract = 0){

        if($_class = self::get_online_product_class($contract)){
            return $_class::setTariff($contract, $tariff, $is_contract);
        }
        return true;
    }


    private static function get_online_product_class($contract){

        $class_name = self::ucfirst($contract->product->slug);
        $online_product_class = "App\\Processes\\Operations\\Contracts\\Matchings\\Matching{$class_name}";
        if(class_exists($online_product_class)){

            return $online_product_class;
        }
        return false;
    }

    private static function ucfirst($string)
    {
        $name = '';
        $strArr = explode('_', $string);
        foreach ($strArr as $str){
            $name .=Str::upper(Str::substr($str, 0, 1)).Str::substr($str, 1);
        }

        return $name;
    }


    public static function getMatchingsTerms($contract, $type)
    {
        if($contract->bso_supplier){
            $hold_kv = $contract->bso_supplier->hold_kv_product($contract->product_id);
            if($hold_kv){
                $matching_terms = HoldKvMatching::where('insurance_companies_id', $hold_kv->insurance_companies_id)
                    ->where('bso_supplier_id', $hold_kv->bso_supplier_id)
                    ->where('product_id', $hold_kv->product_id)
                    ->where('hold_kv_id', $hold_kv->id)
                    ->where('group_id', $contract->agent->financial_group_id)
                    ->where('type', $type)
                    ->get();

                $result = [];
                if($matching_terms){
                    foreach ($matching_terms as $terms){
                        if($terms->json && strlen($terms->json) > 0) {
                            $result[] = \GuzzleHttp\json_decode($terms->json, true);
                        }

                    }
                    return $result;
                }
            }
        }

        return null;
    }



}