<?php

namespace App\Models\Contracts;

use App\Models\BSO\BsoItem;
use App\Models\Directories\InsuranceCompanies;
use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\Country;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $next_act
 * @property string $default_purpose_payment
 * @property string $inn
 * @property float $limit_year
 * @property float $spent_limit_year
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereNextAct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereDefaultPurposePayment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereSpentLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereIsActual($value)
 */
class SubjectsFl extends Model
{
    protected $table = 'subjects_fl';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function citizenship(){
        return $this->hasOne(Country::class, 'id','citizenship_id');
    }

    public function updateInfo($data, $subject_id)
    {
        $info = $this;

        $info->subject_id = $subject_id;
        $info->fio = $data->fio;
        if(isset($data->fio_lat)){
            $info->fio_lat = $data->fio_lat;
        }

        $info->sex = $data->sex;

        $info->is_resident = isset($data->is_resident)?1:0;

        $citizenship_id = (isset($data->citizenship_id)?$data->citizenship_id:0);
        if(isset($data->is_resident)){
            $citizenship_id = 51;
        }

        $info->citizenship_id = $citizenship_id;

        $info->birthdate = $data->birthdate !== '' ? getDateFormatEn($data->birthdate) : '';

        if(isset($data->address_born)){
            $info->address_born = $data->address_born;
            $info->address_born_kladr = $data->address_born_kladr;


            $info->address_born_fias_code = $data->address_born_fias_code;
            $info->address_born_fias_id = $data->address_born_fias_id;

        }else{
            if(isset($data->address_register)){
                $info->address_born = $data->address_register;
                $info->address_born_kladr = $data->address_register_kladr;

                $info->address_born_fias_code = $data->address_register_fias_code;
                $info->address_born_fias_id = $data->address_register_fias_id;
            }
        }

        if(isset($data->address_register)){
            $info->address_register = $data->address_register;
            $info->address_register_kladr = $data->address_register_kladr;
            $info->address_register_zip = $data->address_register_zip;
            $info->address_register_okato = $data->address_register_okato;

            $info->address_register_region = $data->address_register_region;
            $info->address_register_city = $data->address_register_city;
            $info->address_register_city_kladr_id = $data->address_register_city_kladr_id;
            $info->address_register_street = $data->address_register_street;
            $info->address_register_house = $data->address_register_house;
            $info->address_register_block = $data->address_register_block;
            $info->address_register_flat = $data->address_register_flat;

            $info->address_register_fias_code = $data->address_register_fias_code;
            $info->address_register_fias_id = $data->address_register_fias_id;
        }


        if(isset($data->address_fact)){
            $info->address_fact = $data->address_fact;
            $info->address_fact_kladr = $data->address_fact_kladr;
            $info->address_fact_zip = $data->address_fact_zip;
            $info->address_fact_okato = $data->address_fact_okato;

            $info->address_fact_region = $data->address_fact_region;
            $info->address_fact_city = $data->address_fact_city;
            $info->address_fact_city_kladr_id = $data->address_fact_city_kladr_id;
            $info->address_fact_street = $data->address_fact_street;
            $info->address_fact_house = $data->address_fact_house;
            $info->address_fact_block = $data->address_fact_block;
            $info->address_fact_flat = $data->address_fact_flat;

            $info->address_fact_fias_code = $data->address_fact_fias_code;
            $info->address_fact_fias_id = $data->address_fact_fias_id;
        }




        $info->doc_type = $data->doc_type;
        $info->doc_serie = $data->doc_serie;
        $info->doc_number = $data->doc_number;
        if(isset($data->doc_date)){
            $info->doc_date = $data->doc_date !== '' ? getDateFormatEn($data->doc_date) : '';
            $info->doc_office = $data->doc_office;
            $info->doc_info = $data->doc_info;
        }


        $info->save();

        return $info;
    }

}
