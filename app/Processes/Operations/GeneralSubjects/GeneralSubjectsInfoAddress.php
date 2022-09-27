<?php

namespace App\Processes\Operations\GeneralSubjects;



use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Contracts\Subjects;

class GeneralSubjectsInfoAddress {


    public static function createGeneralSubjectAddress($info, $general, $type_id)
    {
        $name = GeneralSubjectsAddress::TYPE[$type_id];
        $address = $general->getAddressType($type_id);
        if(isset($info) && isset($info["address_{$name}"])){

            $address->address = $info["address_{$name}"];

            $address->kladr = $info["address_{$name}_kladr"];
            $address->fias_code = $info["address_{$name}_fias_code"];
            $address->fias_id = $info["address_{$name}_fias_id"];

            if($type_id != 0){
                $address->okato = $info["address_{$name}_okato"];
                $address->zip = $info["address_{$name}_zip"];
                $address->region = $info["address_{$name}_region"];
                $address->city = $info["address_{$name}_city"];
                $address->city_kladr_id = $info["address_{$name}_city_kladr_id"];
                $address->street = $info["address_{$name}_street"];
                $address->house = $info["address_{$name}_house"];
                $address->block = $info["address_{$name}_block"];
                $address->flat = $info["address_{$name}_flat"];
            }

            $address->save();
        }



        return $address;
    }

}