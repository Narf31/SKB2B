<?php

namespace App\Models\Directories;

use App\Models\Security\Security;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
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
class HoldKvMatching extends Model
{
    protected $table = 'hold_kv_matching';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function saveTitle($json)
    {

        if($this->category == 'auto')
        {
            $this->title = self::getTitleAuto($json);
            $this->save();
        }



        return true;
    }


    public static function getTitleAuto($json)
    {
        $str = "";
        $json = \GuzzleHttp\json_decode($json);

        if((int)$json->contract->is_prolongation > -1){
            $str .= "тип договора: ".[0=>"Первичный", 1=>'Пролонгация'][(int)$json->contract->is_prolongation]."; ";
        }

        if($json->object->ts_category > 0){
            $obj = VehicleCategories::findOrFail($json->object->ts_category);
            $str .= "категория: {$obj->title}; ";
        }

        if((int)($json->object->mark_id) > 0){
            $obj = VehicleMarks::where('isn', $json->object->mark_id)->get()->first();
            $str .= "марка: {$obj->title}; ";
        }

        if((int)($json->object->model_id) > 0){
            $obj = VehicleModels::where('isn', $json->object->model_id)->get()->first();
            $str .= "модель: {$obj->title}; ";
        }

        if(strlen($json->object->car_year) > 0){
            $str .= "авто от: {$json->object->car_year} лет; ";
        }

        if(strlen($json->contract->insurance_amount) > 0){
            $str .= "Страховая сумма от: {$json->contract->insurance_amount}; ";
        }

        if(strlen($json->insurer->address_register) > 0){
            $str .= "регион страхователя: {$json->insurer->address_register}; ";
        }

        if(strlen($json->owner->address_register) > 0){
            $str .= "регион собственника: {$json->owner->address_register}; ";
        }


        return $str;
    }


}
