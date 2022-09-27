<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Department;
use App\Models\Settings\TypeOrg;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,departments');
    }

    public function index()
    {
        return view('settings.departments.index');
    }

    public function create()
    {
        return view('settings.departments.create', [

        ]);
    }

    public function edit($id)
    {
        return view('settings.departments.edit', [
            'department' => Department::findOrFail($id),
        ]);
    }

    public function store(Request $request)
    {
        $department = new Department;
        $department->title = $request->title;
        $department->save();
        LogEvents::event($department->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 4, 0,0, $request->all());


        return $this->save($department, $request);
    }

    public function update($id, Request $request)
    {
        $department = Department::findOrFail($id);
        LogEvents::event($department->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 4, 0,0, $request->all());

        return $this->save($department, $request);
    }


    private function save(Department $department, Request $request)
    {
        $this->validate($request, Department::$rules);

        $department->title        = $request->title;
        $department->role_id       = (int) $request->role_id;
        $department->org_type_id       = (int) $request->org_type_id;
        $department->page_enthy       = $request->page_enthy;


        $department->save();
        return parentReload();


        return redirect(url("/settings/departments/$department->id/edit"))->with('success', trans('form.success_update'));
    }

    public function destroy($id)
    {

        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 4);

        Department::findOrFail($id)->delete();

        return response('', 200);
    }


    public function get_list(){

        $departmens = Department::orderBy('title');
        $types_org = TypeOrg::all()->keyBy('id')->toArray();

        if(in_array((int)request()->get('org_type_id'), array_keys($types_org))){
            $departmens = $departmens->where('org_type_id', (int)request()->get('org_type_id'));
        }


        return [
            'departments' => $departmens->get()
        ];
    }

    public function get_table(){
        $data = $this->get_list();
        $data['html'] = view('settings.departments.table', $data)->render();
        return $data;
    }

}
