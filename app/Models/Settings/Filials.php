<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Department
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Department whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Department whereTitle($value)
 * @mixin \Eloquent
 * @property integer $user_type_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Department whereUserTypeId($value)
 */
class Filials extends Model
{
    protected $table = 'filials';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static $rules = [
        'title' => 'required'
    ];
}
