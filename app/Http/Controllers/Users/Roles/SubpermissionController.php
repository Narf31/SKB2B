<?php

namespace App\Http\Controllers\Users\Roles;

use App\Http\Controllers\Controller;
use App\Models\Users\Permission;
use App\Models\Users\Role;
use App\Models\Users\RoleSubpermissions;


class SubpermissionController extends Controller{


    public function __construct() {
        $this->middleware('permissions:users,roles');
        $this->breadcrumbs[] = ['label' => 'Роли', 'url' => 'users/roles',];
    }

    public function index($role_id, $permission_id){

        $role = Role::query()->findOrFail($role_id);
        $permission = Permission::query()->findOrFail($permission_id);

        if($permission->type == 1){ // Настраиваемый
            $template = "users.roles.frames.subpermissions_temps.{$permission->title}";
        }else{ // C вкладками
            $template = 'users.roles.frames.subpermissions';
        }

        return view($template, [
            'role_subpermissions' => $role->role_subpermissions->keyBy('subpermission_id'),
            'permission' => $permission,
            'role' => $role,
        ]);
    }

    public function save($role_id, $permission_id){

        $this->validate(request(), [
            'subpermission.*.edit' => 'integer',
            'subpermission.*.view' => 'integer',
        ]);

        $role = Role::query()->findOrFail($role_id);
        $permission = Permission::query()->findOrFail($permission_id);

        RoleSubpermissions::query()
            ->where('permission_id', $permission_id)
            ->where('role_id', $role_id)
            ->delete();

        $available_subpermissions = $permission->subpermissions->pluck('id')->toArray();


        foreach (request('subpermission', []) as $k => $v){
            if(in_array($k, $available_subpermissions)){

                $required_data = collect([
                    'subpermission_id' => $k,
                    'permission_id' => $permission_id,
                    'role_id' => $role->id,
                    'view' => 0,
                    'edit' => 0,
                ]);

                $data = $required_data->merge($v)->all();

                RoleSubpermissions::create($data);
            }
        }

        return redirect("/users/roles/{$role_id}/permission/{$permission_id}/subpermissions");

    }


}