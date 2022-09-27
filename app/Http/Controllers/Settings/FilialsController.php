<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\Filials;
use Illuminate\Http\Request;

class FilialsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:settings,filials');
    }

    public function index()
    {
        return view('settings.filials.index', [
            'filials' => Filials::orderBy('title')->get()
        ]);
    }

    public function create()
    {
        return view('settings.filials.create', [

        ]);
    }

    public function edit($id)
    {
        return view('settings.filials.edit', [
            'filial' => Filials::findOrFail($id),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save(new Filials, $request);
    }

    public function update($id, Request $request)
    {
        return $this->save(Filials::findOrFail($id), $request);
    }


    private function save(Filials $filial, Request $request)
    {
        $this->validate($request, Filials::$rules);

        $filial->title        = $request->title;
        $filial->ais_id       = $request->ais_id;

        $filial->save();
        return parentReload();

    }

    public function destroy($id)
    {
        Filials::findOrFail($id)->delete();

        return response('', 200);
    }

}
