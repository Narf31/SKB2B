<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\City;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,citys');
    }

    public function index()
    {
        return view('settings.citys.index', [
            'citys' => City::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.citys.create');
    }

    public function edit($id)
    {
        return view('settings.citys.edit', [
            'city' => City::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $city = new City;
        $city->kladr = $request->kladr;
        $city->save();
        LogEvents::event($city->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 2, 0,0, $request->all());

        return $this->save($city, $request);
    }

    public function update($id, Request $request)
    {

        $city = City::findOrFail($id);
        LogEvents::event($city->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 2, 0,0, $request->all());

        return $this->save($city, $request);
    }

    private function save(City $city, Request $request)
    {

        $city->title = $request->title;
        $city->is_actual = (int)$request->is_actual;
        $city->kladr = $request->kladr;
        $city->geo_lat = $request->geo_lat;
        $city->geo_lon = $request->geo_lon;

        $city->save();
        return parentReload();
    }

    public function destroy($id)
    {
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 2);

        City::findOrFail($id)->delete();

        return response('', 200);
    }

}
