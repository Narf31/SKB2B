<?php

namespace App\Models\Contracts;


use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use Illuminate\Database\Eloquent\Model;

class SubjectsUl extends Model
{
    protected $table = 'subjects_ul';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function subject() {
        return $this->hasOne(Subjects::class, 'id', 'subject_id');
    }

    public function updateInfo($data, $subject_id)
    {
        $info = $this;

        $info->subject_id = $subject_id;

        $info->title = $data->title;
        $info->inn = $data->inn;
        $info->ogrn = $data->ogrn;

        if(isset($data->manager)){

            $info->title_full = $data->title_full;

            $info->kpp = $data->kpp;

            $info->okpo = $data->okpo;
            $info->oktmo = $data->oktmo;
            $info->okfs = $data->okfs;
            $info->okato = $data->okato;
            $info->okogy = $data->okogy;
            $info->okved_code = $data->okved_code;

            $info->manager_position = $data->manager['position'];
            $info->manager_fio = $data->manager['fio'];
            $info->manager_birthdate = getDateFormatEn($data->manager['birthdate']);
            $info->manager_phone = $data->manager['phone'];
            $info->manager_email = $data->manager['email'];

            if(isset($data->address)){
                foreach ($data->address as $key => $address){
                    $this->saveAddress($info, $address, $key);
                }
            }

            if(isset($data->of)){
                $info->of_code = $data->of['code'];
                $info->of_full_title = $data->of['full_title'];
                $info->of_title = $data->of['title'];
            }

            $info->bank_id = $data->bank_id;
            $info->bik = $data->bik;
            $info->rs = $data->rs;
            $info->ks = $data->ks;

        }



        $info->save();

        return $info;
    }


    public function saveAddress($info, $address, $name){



        $info->update([
            "address_{$name}" => $address['title'],
            "address_{$name}_fias_code" => $address['fias_code'],
            "address_{$name}_fias_id" => $address['fias_id'],
            "address_{$name}_kladr" => $address['kladr'],
            "address_{$name}_region" => $address['region'],
            "address_{$name}_city" => $address['city'],
            "address_{$name}_city_kladr_id" => $address['city_kladr_id'],
            "address_{$name}_street" => $address['street'],
            "address_{$name}_house" => $address['house'],
            "address_{$name}_block" => $address['block'],
            "address_{$name}_flat" => $address['flat'],
            "address_{$name}_zip" => $address['zip'],
            "address_{$name}_okato" => $address['okato'],
        ]);

    }

}
