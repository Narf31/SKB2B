<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Bank;
use Illuminate\Http\Request;

class BanksController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,banks');
    }

    public function index()
    {
        return view('settings.banks.index', [
            'banks' => Bank::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.banks.create');
    }

    public function edit($id)
    {
        return view('settings.banks.edit', [
            'bank' => Bank::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $bank = new Bank;
        $bank->save();
        LogEvents::event($bank->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 6, 0,0, $request->all());

        return $this->save($bank, $request);
    }

    public function update($id, Request $request)
    {
        $bank = Bank::findOrFail($id);
        LogEvents::event($bank->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 6, 0,0, $request->all());

        return $this->save($bank, $request);
    }

    private function save(Bank $bank, Request $request)
    {

        $bank->title = $request->title;
        $bank->is_actual = (int)$request->is_actual;

        $bank->save();
        return parentReload();
        return redirect("/settings/banks/{$bank->id}/edit/");
    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 6);

        Bank::findOrFail($id)->delete();

        return response('', 200);
    }

}
