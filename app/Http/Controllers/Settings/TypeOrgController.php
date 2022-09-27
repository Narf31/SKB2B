<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\TypeOrg;
use Illuminate\Http\Request;

class TypeOrgController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,type_org');
    }

    public function index()
    {
        return view('settings.type_org.index', [
            'type_orgs' => TypeOrg::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.type_org.create');
    }

    public function edit($id)
    {
        return view('settings.type_org.edit', [
            'type_org' => TypeOrg::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $type_org = new TypeOrg;
        $type_org->save();
        LogEvents::event($type_org->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 7, 0,0, $request->all());


        return $this->save($type_org, $request);
    }

    public function update($id, Request $request)
    {
        $type_org = TypeOrg::findOrFail($id);
        LogEvents::event($type_org->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 7, 0,0, $request->all());


        return $this->save($type_org, $request);
    }

    private function save(TypeOrg $type_org, Request $request)
    {

        $type_org->title = $request->title;
        $type_org->is_actual = (int)$request->is_actual;
        $type_org->is_provider = (int)$request->is_provider;
        $type_org->is_contract = (int)$request->is_contract;


        $type_org->save();
        return parentReload();
    }

    public function destroy($id)
    {

        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 7);

        TypeOrg::findOrFail($id)->delete();

        return response('', 200);
    }

}
