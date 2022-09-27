<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\PermissionGroup
 *
 * @property integer $id
 * @property string $title
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Settings\Permission[] $permissions
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\PermissionGroup whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\PermissionGroup whereTitle($value)
 * @mixin \Eloquent
 */
class PermissionGroup extends Model
{
    protected $table = 'permissions_groups';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id')->where('not_visible', 0)->orderBy('sort', 'asc');
    }

}
