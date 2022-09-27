<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Bank;
use App\Models\Settings\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,currency');
    }

    public function index()
    {
        return view('settings.currency.index', [
            'currency' => Currency::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.currency.create');
    }

    public function edit($id)
    {
        return view('settings.currency.edit', [
            'currency' => Currency::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->save();
        LogEvents::event($currency->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 6, 0,0, $request->all());

        return $this->save($currency, $request);
    }

    public function update($id, Request $request)
    {
        $currency = Currency::findOrFail($id);
        LogEvents::event($currency->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 6, 0,0, $request->all());

        return $this->save($currency, $request);
    }

    private function save(Currency $currency, Request $request)
    {

        $currency->title = $request->title;
        $currency->code = $request->code;

        $currency->save();
        return parentReload();
    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 6);

        Currency::findOrFail($id)->delete();

        return response('', 200);
    }

}