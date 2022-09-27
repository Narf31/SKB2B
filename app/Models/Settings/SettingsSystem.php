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
class SettingsSystem extends Model
{
    protected $table = 'settings_system';

    protected $guarded = ['id'];

    public $timestamps = false;

    const FRONT_PROTOCOL = [
        1 => 'Кансалтинг РИТ (версия api 2.1)',

    ];


    public static function getDataParam($types, $param)
    {

        $system = SettingsSystem::where('types', $types)->where('param', $param)->get()->first();

        if($system){
            return $system->val;
        }else{
            return '';
        }
    }

}
