<?php

namespace App\Models\Directories\Products\Data;

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
class Osago extends Model
{
    protected $table = 'products_osago';

    protected $guarded = ['id'];

    public $timestamps = false;


    const CONTRACT_TYPE = [
        0 => 'Бумажный',
        1 => 'Электронный',
    ];


    public function contract() {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function setDefault()
    {
        $contract = $this->contract;

        $oi = ObjectInsurer::create(['type' => 1]);

        $contract->object_insurer_id = $oi->id;
        $contract->save();

        return true;
    }



}
