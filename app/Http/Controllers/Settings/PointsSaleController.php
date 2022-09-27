<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\PointsSale;
use Illuminate\Http\Request;

class PointsSaleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,points_sale');
    }

    public function index()
    {
        return view('settings.points.index', [
            'points' => PointsSale::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.points.create');
    }

    public function edit($id)
    {
        return view('settings.points.edit', [
            'point' => PointsSale::findOrFail($id)
        ]);
    }

    public function store(Request $request)
    {
        $point = new PointsSale;
        $point->save();
        LogEvents::event($point->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 3, 0,0, $request->all());

        return $this->save($point, $request);
    }

    public function update($id, Request $request)
    {
        $point = PointsSale::findOrFail($id);
        LogEvents::event($point->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 3, 0,0, $request->all());

        return $this->save($point, $request);
    }

    private function save(PointsSale $point, Request $request)
    {

        $point->title = $request->title;
        $point->is_actual = (int)$request->is_actual;
        $point->city_id = (int)$request->city_id;


        $point->address = $request->address;
        $point->latitude = (!empty($request->latitude))?(int)$request->latitude:null;
        $point->longitude = (!empty($request->longitude))?(int)$request->longitude:null;


        $point->is_sale = (int)$request->is_sale;
        $point->is_pso = (int)$request->is_pso;
        $point->is_damages = (int)$request->is_damages;

        $point->save();
        return parentReload();
    }

    public function destroy($id)
    {

        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 3);
        PointsSale::findOrFail($id)->delete();

        return response('', 200);
    }

}
