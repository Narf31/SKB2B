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
class Permission extends Model
{
    protected $table = 'permissions';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class);
    }

    public function subpermissions(){
        return $this->hasMany(Subpermission::class, 'permission_id');
    }

}