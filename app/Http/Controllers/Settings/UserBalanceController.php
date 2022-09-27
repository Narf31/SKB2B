<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Bank;
use App\Models\Settings\UserBalanceSettings;
use Illuminate\Http\Request;

class UserBalanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,user_balance');
    }

    public function index()
    {

        return view('settings.user_balance.index', [
            'user_balance' => UserBalanceSettings::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.user_balance.create');
    }

    public function edit($id)
    {
        return view('settings.user_balance.edit', [
            'balance' => UserBalanceSettings::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $user_balance = new UserBalanceSettings();
        $user_balance->save();
        LogEvents::event($user_balance->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 6, 0,0, $request->all());

        return $this->save($user_balance, $request);
    }

    public function update($id, Request $request)
    {
        $user_balance = UserBalanceSettings::findOrFail($id);
        LogEvents::event($user_balance->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 6, 0,0, $request->all());

        return $this->save($user_balance, $request);
    }

    private function save(UserBalanceSettings $user_balance, Request $request)
    {

        $user_balance->title = $request->title;
        $user_balance->is_actual = (int)$request->is_actual;
        $user_balance->type_id = (int)$request->type_id;

        $user_balance->save();
        return parentReload();
    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 6);

        UserBalanceSettings::findOrFail($id)->delete();

        return response('', 200);
    }

}
