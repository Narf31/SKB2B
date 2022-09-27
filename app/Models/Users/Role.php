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
class Role extends Model
{
    protected $table = 'roles';

    protected $guarded = ['id'];

    public $timestamps = false;




    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');

    }

    public function subpermissions(){
        $role = $this;
        return $this->custom(Subpermission::class, function ($relation) use ($role) {
            $relation->join('roles_subpermissions', 'roles_subpermissions.subpermission_id', '=', 'subpermissions.id');
            $relation->where('roles_subpermissions.role_id', $role->id);
        });
    }

    public function role_subpermissions(){
        return $this->hasMany(RoleSubpermissions::class, 'role_id');
    }

    public function permissions_menu(){
        $permissions = Permission::query();
        $permissions->leftJoin('roles_permissions', 'permissions.id', '=', 'roles_permissions.permission_id');
        $permissions->leftJoin('permissions_groups', 'permissions_groups.id', '=', 'permissions.group_id');
        $permissions->where('roles_permissions.role_id', $this->id);
        $permissions->where('permissions.is_menu', 1);
        $permissions->select('permissions.title as permissions', 'permissions_groups.title as groups');
        return $permissions->get();
    }

    public function hasPermission($permissionId){
        return $this->permissions()->where('permission_id', $permissionId)->exists();
    }

    public function getSubpermissions($permissionId, $status_id = -1){
        $subpermissions = $this->role_subpermissions()->where('roles_subpermissions.permission_id', $permissionId);
        $subpermissions->where('roles_subpermissions.view', 1);
        $subpermissions->leftJoin('permissions_sub', 'permissions_sub.id', '=', 'roles_subpermissions.subpermission_id');
        $subpermissions->select(['roles_subpermissions.edit', 'permissions_sub.title','permissions_sub.status_id']);
        $subpermissions->orderBy('permissions_sub.status_id');

        $result = null;

        if($status_id != -1){
            $result = $subpermissions->where('permissions_sub.status_id', $status_id)->get()->first();
        }else{
            $result = $subpermissions->get();
        }


        return $result;


    }

    public function rolesVisibility($permissionId){
        return RolesVisibility::where('role_id', $this->id)->where('permission_id', $permissionId)->get()->last();
    }

    public function visibility($permission_group_name){
        if($permission_group = PermissionGroup::query()->where('title', $permission_group_name)->first()){
            return $this->rolesVisibility($permission_group->id)->visibility;
        }
        return false;
    }

}
