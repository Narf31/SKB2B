<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\SalaryState as State;
use Illuminate\Http\Request;

class SalariesStatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:settings,salaries_states');
    }

    public function index()
    {
        return view('settings.salaries_states.index', [
            'states' => State::all(),
        ]);
    }

    public function create()
    {
        return view('settings.salaries_states.create');
    }

    public function edit($id)
    {
        return view('settings.salaries_states.edit', [
            'state' => State::findOrFail($id),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save(new State, $request);
    }

    public function update($id, Request $request)
    {
        return $this->save(State::findOrFail($id), $request);
    }

    private function save(State $state, Request $request)
    {
        $state->title  = $request->title;
        $state->prefix = $request->prefix;

        $state->save();
        return parentReload();

        return redirect(url("/settings/salaries_states/$state->id/edit"))->with('success', trans('form.success_update'));
    }

    public function destroy($id)
    {
        State::findOrFail($id)->delete();

        return response('', 200);
    }

}
