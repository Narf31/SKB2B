<?php

namespace App\Http\Controllers\Users\Roles;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Users\Permission;
use App\Models\Users\PermissionGroup;
use App\Models\Users\Role;
use App\Models\Users\RolesVisibility;
use Illuminate\Http\Request;

class RolesController extends Controller {

    public function __construct() {
        $this->middleware('permissions:users,roles');
        $this->breadcrumbs[] = [
            'label' => 'Роли',
            'url' => 'users/roles',
        ];
    }

    public function index() {
        return view('users.roles.index', [
            'roles' => Role::all(),
        ]);
    }

    public function edit($id) {
        $role = Role::find($id);

        if ($role){

            $this->breadcrumbs[] = [
                'label' => $role->title,
            ];

            return view('users.roles.edit', [
                'role' => $role,
                'groups' => PermissionGroup::orderBy('sort_view', 'asc')->get()
            ])->with('breadcrumbs', $this->breadcrumbs);

        }else{
            return redirect('/users/roles');
        }
    }

    public function create() {
        $this->breadcrumbs[] = [
            'label' => 'Новая роль',
        ];

        return view("users.roles.create", [
                    'role' => new Role(),
                    'groups' => PermissionGroup::orderBy('sort_view', 'asc')->get()
                ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function store(Request $request) {
        $role = new Role;
        $role->save();
        LogEvents::event($role->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 0, 0, 0, $request->all());

        return $this->save($role, $request);
    }

    public function update($id, Request $request) {
        $role = Role::findOrFail($id);
        LogEvents::event($role->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 0, 0, 0, $request->all());

        return $this->save($role, $request);
    }

    private function save(Role $role, Request $request) {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $role->title = $request->title;

        $role->save();

        $role->permissions()->detach();

        if ($request->has('permissions')) {

            $permissions = array_map(function ($permissionId) {
                return Permission::find($permissionId);
            }, $request->permissions);

            $role->permissions()->saveMany($permissions);



            foreach ($request->groups as $group_id) {
                $arr = $request->get('visibility') ?: [];


                if (isset($arr[$group_id])) {

                    $visibility_obj = $role->rolesVisibility($group_id);
                    if ($visibility_obj) {
                        $visibility_obj->delete();
                    }
                    RolesVisibility::create([
                        'role_id' => $role->id,
                        'permission_id' => $group_id,
                        'visibility' => $arr[$group_id],
                    ]);
                }
            }
        }

        return redirect(url("/users/roles/$role->id/edit/"))->with('success', trans('form.success_update'));
    }

    public function destroy($id) {
        $role = Role::find($id);

        $role->delete();

        return response('', 200);
    }

    public function show($id)
    {
        return redirect('/users/roles/'.$id.'/edit');
    }




}
