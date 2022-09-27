<?php
namespace App\Classes\Notification\Contracts;


use App\Models\Contracts\Contracts;


class CorrectedSampler{

    public static function sample(Contracts $contract){

        if($contract->sales_condition == 0){
            return $contract->agent ? collect([$contract->agent]) : "";
        }else{
            return $contract->manager ? collect([$contract->manager]) : "";
        }

    }
}