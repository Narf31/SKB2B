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
class KaskoProduct extends Model
{
    protected $table = 'kasko_product';

    protected $guarded = ['id'];

    public $timestamps = false;


    const PRODUCT = [
        1 => 'GAP',
        2 => 'НС по системе мест',
        3 => 'НС по паушальной системе',
        4 => 'Гражданская ответственность',
    ];


    public static function getProductSum($product_id, $program_id, $kasko_prod_id, $amount)
    {
        $product = self::query();
        $product->where('product_id', $product_id);
        $product->where('program_id', $program_id);
        $product->where('kasko_product_id', $kasko_prod_id);
        $product->where('amount', '>=', $amount);
        $product->orderBy('amount');
        return $product->get()->first();
    }



}
