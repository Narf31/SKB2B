<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Role
 *
 * @property integer $id
 * @property string $title
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Settings\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Role whereTitle($value)
 * @mixin \Eloquent
 */
class UsersLimitBSO extends Model
{
    protected $table = 'users_limit_bso';

    protected $guarded = ['id'];

    public $timestamps = false;



}
