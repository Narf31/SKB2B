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
class Drive extends Model
{
    protected $table = 'products_kasko_drive';

    protected $guarded = ['id'];

    public $timestamps = false;

    const COATINGS_RISKS = [
        1 => 'Хищение и Ущерб',
        2 => 'Хищение + Полная гибель (Тотал)',
        3 => 'Ущерб',
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


    const REPAIR_OPTIONS = [
        1 => 'Официальный дилер',
        2 => 'По направлению страховщика',
       /* 3 => 'Экономный ремонт',*/
    ];

    const TRANSITION = [
        0 => 'Нет',
        1 => 'Да',
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
