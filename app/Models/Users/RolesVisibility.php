<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Permission
 *
 * @property integer $id
 * @property string $title
 * @property integer $group_id
 * @property-read \App\Models\Settings\PermissionGroup $group
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Permission whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Permission whereGroupId($value)
 * @mixin \Eloquent
 */
class RolesVisibility extends Model
{

    const VISIBILITY = [
        0 => 'Все',
        1 => 'Все в рамках организации',
        2 => 'Только свои',
        3 => 'Только свои и своих подчиненных',
    ];

    protected $table = 'roles_visibility';

    protected $guarded = ['id'];

    public $timestamps = false;



}