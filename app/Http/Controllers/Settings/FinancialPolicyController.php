<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\FinancialGroup;
use Illuminate\Http\Request;

class FinancialPolicyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,financial_policy');
    }

    public function index()
    {
        return view('settings.financial_group.index', [
            'financial_groups' => FinancialGroup::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.financial_group.create');
    }

    public function edit($id)
    {
        return view('settings.financial_group.edit', [
            'financial_group' => FinancialGroup::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $financial_group = new FinancialGroup;
        $financial_group->title = $request->title;
        $financial_group->save();
        LogEvents::event($financial_group->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 5, 0,0, $request->all());

        return $this->save($financial_group, $request);
    }

    public function update($id, Request $request)
    {
        $financial_group = FinancialGroup::findOrFail($id);
        LogEvents::event($financial_group->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 5, 0,0, $request->all());

        return $this->save($financial_group, $request);
    }

    private function save(FinancialGroup $financial_group, Request $request)
    {

        $financial_group->title = $request->title;
        $financial_group->is_actual = (int)$request->is_actual;

        $financial_group->save();
        return parentReload();

    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 5);
        FinancialGroup::findOrFail($id)->delete();

        return response('', 200);
    }

}
