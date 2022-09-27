<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Bank whereIsActual($value)
 */
class CurrencyValue extends Model
{
    protected $table = 'currency_value';

    protected $guarded = ['id'];

    public $timestamps = false;


    public static function updateValue($currency, $data, $amount)
    {
        $value = self::getActualValue($currency->id, $data);
        if(!$value){
            self::create(['currency_id'=>$currency->id, 'actual_date' => $data, 'amount' => $amount]);
        }
        return true;
    }

    public static function getActualValue($currency_id, $data)
    {
        return self::query()
            ->where("actual_date", '<=',$data)
            ->where("currency_id", $currency_id)
            ->orderBy('actual_date', 'desc')
            ->get()
            ->first();
    }

}
