<?php

namespace App\Processes\Scenaries\Contracts\Scorings\Products;


use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsScorings;
use App\Processes\Scenaries\Contracts\Scorings\Defaults\Auto;
use App\Processes\Scenaries\Contracts\Scorings\Defaults\General;

class Kasko {

    public static function scoring(Contracts $contract)
    {

        $contract = Contracts::find($contract->id);


        $contract->scoring_state = 0;
        $contract->scoring_text = '';


        $ban = self::is_ban($contract);
        if($ban->is_ban == false){
            $contract->scoring_state = 2;
            $contract->scoring_text = $ban->text;
        }else{
            $underwriting = self::is_underwriting($contract);
            if($underwriting->scoring == false) {
                $contract->scoring_state = 1;
            }
            $contract->scoring_text = $underwriting->text;
        }

        $contract->save();

        return $ban->is_ban;
    }


    public static function is_ban($contract)
    {
        $result = new \stdClass();
        $result->is_ban = true;
        $result->text = '';

        /*
        $result = General::is_ban($contract);
        if($result->is_ban == true){
            $result = Auto::is_ban($contract);
        }
        */

        return $result;
    }


    public static function is_underwriting($contract)
    {
        $result = new \stdClass();
        $result->scoring = true;
        $result->text = '';

        General::checkSpectrumData($contract);



        /*
        $result = General::checkDrivers($contract);
        if($result->scoring == true){
            $result = Auto::repairEffects($contract);
        }
        */

        return $result;
    }






}