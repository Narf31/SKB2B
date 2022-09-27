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
class Currency extends Model
{
    protected $table = 'currency';

    protected $guarded = ['id'];

    public $timestamps = false;


    public static function getCurrencyDay($date = null)
    {
        if(!$date){
            $date = getDateFormatEn(getDateTime());
        }

        $currency = Currency::query();

        $currency->leftJoin('currency_value', function($leftJoin) use($date)
        {
            $leftJoin->on('currency_value.currency_id', '=', 'currency.id')

                ->where('currency_value.actual_date', $date);
        });

        $currency->orderBy('currency.title');
        $currency->select(['currency.id', 'currency.title', 'currency_value.amount']);

        $result = [];
        foreach ($currency->get() as $curr)
        {
            $result[$curr->id] = "{$curr->title} - {$curr->amount}";
        }

        return $result;
    }

}
