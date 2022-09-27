<?php

namespace App\Models\Directories\Products\Data\Mortgage;

use App\Models\Clients\GeneralSubjects;
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
class Mortgage extends Model
{
    protected $table = 'products_mortgage';

    protected $guarded = ['id'];

    public $timestamps = false;

    const INS_YEAR = [
        12 => '1 год',
    ];


    const TRANSITION = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const TYPE_REALTY = [
        0 => 'Первичка',
        1 => 'Вторичка',
    ];

    const CLASS_REALTY = [
        0 => 'Квартира',
        1 => 'Комната',
        2 => 'Дом',
        3 => 'Земельный участок',
    ];

    const TYPE_HEALTH_DEVIATION = [
        0 => 'Нет',
        1 => 'Незначительное',
        2 => 'Значительное',
    ];

    const TYPE_SPORT = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const DOCUMENT_OWNER = [
        0 => 'Договор купли-продажи',
    ];

    public function contract() {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }


    public function bank() {
        return $this->hasOne(GeneralSubjects::class, 'id', 'bank_id');
    }

    public function setDefault()
    {
        return true;
    }



}
