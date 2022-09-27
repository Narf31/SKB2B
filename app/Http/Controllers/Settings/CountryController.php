<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Bank;
use App\Models\Settings\Country;
use App\Models\Settings\Currency;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,country');
    }

    public function index()
    {
        return view('settings.country.index', [
            'country' => Country::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.country.create');
    }

    public function edit($id)
    {
        return view('settings.country.edit', [
            'country' => Country::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $country = new Country;
        $country->title = "{$request->title_ru} - {$request->title_en}";
        $country->save();
        LogEvents::event($country->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 6, 0,0, $request->all());

        return $this->save($country, $request);
    }

    public function update($id, Request $request)
    {
        $country = Country::findOrFail($id);
        LogEvents::event($country->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 6, 0,0, $request->all());

        return $this->save($country, $request);
    }

    private function save(Country $country, Request $request)
    {


        $country->title_ru = $request->title_ru;
        $country->title_en = $request->title_en;
        $country->is_schengen = (int)$request->is_schengen;
        $country->code = ((int)$request->code > 0)?(int)$request->code:null;

        $country->title = "{$request->title_ru} - {$request->title_en}";


        $country->save();
        return parentReload();
    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 6);

        Country::findOrFail($id)->delete();

        return response('', 200);
    }

}

