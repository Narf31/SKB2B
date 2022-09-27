<?php

namespace App\Models\Directories\Products\Data\Kasko;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
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
class BaseRateKasko extends Model
{
    protected $table = 'kasko_baserate';

    protected $guarded = ['id'];

    public $timestamps = false;



    public static function getBaseRate($product_id, $program_id, $mark_id, $model_id, $year)
    {
        $base = self::query();
        $base->where('product_id', $product_id);
        $base->where('program_id', $program_id);
        $base->where('mark_id', $mark_id);
        $base->where('model_id', $model_id);
        $base->where('year', $year);
        return $base->get()->first();
    }


}
