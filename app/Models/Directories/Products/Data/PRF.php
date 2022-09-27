<?php

namespace App\Models\Directories\Products\Data;

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
class PRF extends Model
{
    protected $table = 'products_prf';

    protected $guarded = ['id'];

    public $timestamps = false;



    public function setDefault()
    {


        return true;
    }



    const PROGRAMS = [
        1 => 'B1',
    ];

    const AMOUNT = [
        50000 => '50 000',
        100000 => '100 000',
        300000 => '300 000',
    ];


    const NS_PROGRAM = [
        0 => 'Нет',
        1 => 'Да',
    ];

    const NS_AMOUNT = [
        50000 => '50 000',
        100000 => '100 000',
        300000 => '300 000',
    ];

    const OPTIONS = [
        'is_leisure' => 'Активный отдых',
        'is_chronic_diseases' => 'Хронич. заболевания',
        'is_pregnancy' => 'Беременность',
        'is_science' => 'Наука',
        'is_children' => 'Дети',
        'is_alcohol' => 'Алкоголь',
        'is_covid19' => 'COVID-19',
    ];


}
