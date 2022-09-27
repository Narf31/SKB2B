<?php

namespace App\Processes\Operations\Contracts\Object;


use App\Helpers\EmptyObject;
use App\Models\Contracts\ObjectInsurer;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerFlats;
use Illuminate\Support\Str;

class ContractObject{


    public static function update_or_create_flats($object_insurer_id, $data){


        $title = $data->address;

        if(isset($object_insurer_id) && (int)$object_insurer_id>0){
            $object = ObjectInsurer::find($object_insurer_id);
        }else{
            $object = ObjectInsurer::create([]);
        }

        $object->update([
            'type' => 2,
            'title' => $title,
        ]);

        $flats = ObjectInsurerFlats::where('object_insurer_id', $object->id)->first();

        $old_flats = $flats ? $flats : new EmptyObject();

        $update_data = [
            'address' => isset($data->address) ? $data->address : $old_flats->address,
            'address_kladr' => isset($data->address_kladr) ? $data->address_kladr : $old_flats->address_kladr,
            'address_region' => isset($data->address_region) ? $data->address_region : $old_flats->address_region,
            'address_city' => isset($data->address_city) ? $data->address_city : $old_flats->address_city,
            'address_city_kladr_id' => isset($data->address_city_kladr_id) ? $data->address_city_kladr_id : $old_flats->address_city_kladr_id,
            'address_street' => isset($data->address_street) ? $data->address_street : $old_flats->address_street,
            'address_latitude' => isset($data->address_latitude) ? $data->address_latitude : $old_flats->address_latitude,
            'address_longitude' => isset($data->address_longitude) ? $data->address_longitude : $old_flats->address_longitude,
            'address_house' => isset($data->address_house) ? $data->address_house : $old_flats->address_house,
            'address_block' => isset($data->address_block) ? $data->address_block : $old_flats->address_block,
            'address_flat' => isset($data->address_flat) ? $data->address_flat : $old_flats->address_flat,
            'house_floor' => isset($data->house_floor) ? $data->house_floor : $old_flats->house_floor,
            'flat_floor' => isset($data->flat_floor) ? $data->flat_floor : $old_flats->flat_floor,

            'comments' => isset($data->comments) ? $data->comments : $old_flats->comments,

            'object_insurer_id' => $object->id
        ];

        if($flats){

            $flats->update($update_data);
        }else{
            ObjectInsurerFlats::create($update_data);
        }

        return $object;

    }


}