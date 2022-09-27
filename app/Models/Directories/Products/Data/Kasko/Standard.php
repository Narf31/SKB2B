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
class Standard extends Model
{
    protected $table = 'products_kasko_standard';

    protected $guarded = ['id'];

    public $timestamps = false;

    const COATINGS_RISKS = [
        1 => 'Хищение и Ущерб',
        2 => 'Хищение + Полная гибель (Тотал)',
        3 => 'Ущерб',
    ];

    const INS_YEAR = [
        12 => '1 год',
        6 => '6 месяцев',
        3 => '3 месяца',
    ];

    const COATINGS_RISKS_POLICY = [
        1 => [
            'risk' => 'КАСКО',
            'add_condition' => '',
        ],
        2 => [
            'risk' => 'Хищение + Ущерб*',
            'add_condition' => '*Риск «Ущерб» застрахован только на случай полной гибели ТС без ответственности за случаи повреждения ТС.',
        ],

        3 => [
            'risk' => 'Ущерб',
            'add_condition' => '',
        ],
    ];

    const TERRIRORY = [
        1 => 'РФ',
        2 => 'РФ, СНГ, Европа',
    ];

    const TENURE = [
        1 => 'Только что купил (В процессе покупки)',
        2 => 'Менее полутора лет',
        3 => 'Более полутора лет',
    ];

    const REPAIR_OPTIONS = [
        1 => 'Официальный дилер',
        2 => 'Официальный дилер для гарантийных ТС',
        3 => 'По направлению страховщика',
    ];

    const FRANCHISE = [
        0 => 'Нет',
        5000 => '5 000',
        10000 => '10 000',
        15000 => '15 000',
        20000 => '20 000',
        25000 => '25 000',
        30000 => '30 000',
        35000 => '35 000',
        40000 => '40 000',
        45000 => '45 000',
        50000 => '50 000',
        55000 => '55 000',
        60000 => '60 000',
        65000 => '65 000',
        70000 => '70 000',
        75000 => '75 000',
        80000 => '80 000',
        85000 => '85 000',
        90000 => '90 000',
        95000 => '95 000',
        100000 => '100 000',
        150000 => '150 000',
    ];

    const CIVIL_RESPONSIBILITY = [
        0 => 'Нет',
        500000 => '500 000',
        1000000 => '1 000 000',
        1500000 => '1 500 000',
    ];

    const FRANCHISE_NUMBER = [
        1 => 'С 1го случая',
        2 => 'С 2го случая',
    ];


    const NS_SUM = [
        0 => 'Нет',
        500000 => '500 000',
        1000000 => '1 000 000',
    ];

    const BAGGAGE_SUM = [
        0 => 'Нет',
        15000 => '15 000',
        30000 => '30 000',
    ];

    const PAYMENT_NOT_CERTIFICATES = [
        0 => 'Нет',
        1 => '1 стеклянный элемент 1 раз в год',
        2 => '1 кузовной элемент 1 раз в год и 1 стеклянный элемент 1 раз в год',
        3 => '1 кузовной элемент 1 раз в год и 1 стеклянный элемент 2 раза в год',
        4 => '1 кузовной элемент 2 раза в год и 1 стеклянный элемент 2 раза в год',
        5 => '1 кузовной элемент 1 раз в год и 1 стеклянный элемент без ограничений в течение года',
    ];


    const TRANSITION = [
        0 => 'Нет',
        1 => 'Да',
    ];


    const NS_TYPE = [
        0 => 'Нет',
        1 => 'По системе мест в салоне ТС',
        2 => 'По паушальной системе',
    ];

    const NS_COUNT = [
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
    ];

    const LIMIT_INDEMNITY = [
        1 => 'Неагрегатная страховая сумма',
        2 => 'Агрегатная страховая сумма',
    ];

    public function contract() {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public static function ns_sum($program_id) {
        return KaskoProduct::where('program_id',$program_id)
            ->where('kasko_product_id',2)
            ->get();
    }

    public static function baggage_sum($program_id) {
        return KaskoProduct::where('program_id',$program_id)
            ->where('kasko_product_id',3)
            ->get();
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
