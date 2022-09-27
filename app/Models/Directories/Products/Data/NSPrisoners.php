<?php

namespace App\Models\Directories\Products\Data;

use App\Models\Contracts\Contracts;
use App\Models\Security\Security;
use App\Models\Settings\Country;
use App\Models\Settings\Currency;
use App\Models\Settings\TypeOrg;
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
class NSPrisoners extends Model
{
    protected $table = 'products_ns_prisoners';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function contract() {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }


    public function setDefault()
    {





        return true;
    }


    const CONVICTED_TERM_CONTRSCT = [
        1 => 'до 1 года',
        2 => 'от 1 года до 3-х лет',
        3 => 'более 3-х лет',
    ];

    const INSURANCE_AMOUNT_NS = [
        50000 => '50 000',
        100000 => '100 000',
        150000 => '150 000',
        200000 => '200 000',
        250000 => '250 000',
        300000 => '300 000',
        400000 => '400 000',
        500000 => '500 000',
        600000 => '600 000',
        700000 => '700 000',
        800000 => '800 000',
        900000 => '900 000',
        1000000 => '1 000 000',
        1100000 => '1 100 000',
        1200000 => '1 200 000',
        1300000 => '1 300 000',
        1400000 => '1 400 000',
        1500000 => '1 500 000',
        1600000 => '1 600 000',
        1700000 => '1 700 000',
        1800000 => '1 800 000',
        1900000 => '1 900 000',
        2000000 => '2 000 000',
        2100000 => '2 100 000',
        2200000 => '2 200 000',
        2300000 => '2 300 000',
        2400000 => '2 400 000',
        2500000 => '2 500 000',
        2600000 => '2 600 000',
        2700000 => '2 700 000',
        2800000 => '2 800 000',
        2900000 => '2 900 000',
        3000000 => '3 000 000',
    ];

    const INSURANCE_AMOUNT_TUBERCULOSIS = [
        50000 => '50 000',
        100000 => '100 000',
        150000 => '150 000',
        200000 => '200 000',
        250000 => '250 000',
        300000 => '300 000',
        400000 => '400 000',
        500000 => '500 000',
        600000 => '600 000',
        700000 => '700 000',
    ];

}
