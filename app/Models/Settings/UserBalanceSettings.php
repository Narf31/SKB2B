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
class UserBalanceSettings extends Model
{
    protected $table = 'user_balance_settings';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPE = [
        0 => 'Дебитовый счет',
        1 => 'Кредитный счет',
        2 => 'Замороженное КВ',
        3 => 'Развязка договоров',
    ];

}
