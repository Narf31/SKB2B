<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\TypeBso;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\TypeBso;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class BsoSerieController extends Controller
{

    public function __construct()
    {

    }



    public function edit($id, $type_bso_id, $bso_serie_id)
    {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $type_bso = TypeBso::where('insurance_companies_id', $id)->where('id', $type_bso_id)->get()->first();

        if((int)$bso_serie_id > 0){
            $bso_serie = BsoSerie::where('insurance_companies_id', $id)->where('id', $bso_serie_id)->get()->first();

        }else{
            $bso_serie = new BsoSerie();
        }

        return view('directories.insurance_companies.type_bso.bso_serie', [
            'insurance_companies' => $sk,
            'bso_serie' => $bso_serie,
            'type_bso' => $type_bso
        ]);

    }


    public function save($id, $type_bso_id, $bso_serie_id, Request $request)
    {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $type_bso = TypeBso::where('insurance_companies_id', $id)->where('id', $type_bso_id)->get()->first();

        if((int)$bso_serie_id > 0){
            $bso_serie = BsoSerie::where('insurance_companies_id', $id)->where('id', $bso_serie_id)->get()->first();
            LogEvents::event($bso_serie->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 14, $id, $type_bso_id, $request->all());

        }else{
            $bso_serie = new BsoSerie();
            $bso_serie->type_bso_id = $type_bso_id;
            $bso_serie->insurance_companies_id = $id;
            LogEvents::event($bso_serie->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 14, $id, $type_bso_id, $request->all());
        }

        $bso_serie->bso_count_number = $request->bso_count_number;
        $bso_serie->is_actual = (isset($request->is_actual))?$request->is_actual:0;
        $bso_serie->bso_class_id = $request->bso_class_id;
        $bso_serie->bso_serie = $request->bso_serie;
        $bso_serie->product_id = $type_bso->product_id;

        $bso_serie->save();



        return redirect(url("/directories/insurance_companies/$id/type_bso/$type_bso_id/"))->with('success', trans('form.success_update'));


    }


    public function dop_edit($id, $type_bso_id, $bso_serie_id, $bso_dop_serie_id)
    {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $type_bso = TypeBso::where('insurance_companies_id', $id)->where('id', $type_bso_id)->get()->first();
        $bso_serie = BsoSerie::where('insurance_companies_id', $id)->where('id', $bso_serie_id)->get()->first();

        if((int)$bso_dop_serie_id > 0){
            $bso_dop_serie = BsoDopSerie::where('insurance_companies_id', $id)->where('bso_serie_id', $bso_serie_id)->where('id', $bso_dop_serie_id)->get()->first();
        }else{
            $bso_dop_serie = new BsoDopSerie();
        }

        return view('directories.insurance_companies.type_bso.bso_dop_serie', [
            'insurance_companies' => $sk,
            'type_bso' => $type_bso,
            'bso_serie' => $bso_serie,
            'bso_dop_serie' => $bso_dop_serie,
        ]);

    }

    public function dop_save($id, $type_bso_id, $bso_serie_id, $bso_dop_serie_id, Request $request)
    {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $type_bso = TypeBso::where('insurance_companies_id', $id)->where('id', $type_bso_id)->get()->first();
        $bso_serie = BsoSerie::where('insurance_companies_id', $id)->where('id', $bso_serie_id)->get()->first();

        if((int)$bso_dop_serie_id > 0){
            $bso_dop_serie = BsoDopSerie::where('insurance_companies_id', $id)->where('bso_serie_id', $bso_serie_id)->where('id', $bso_dop_serie_id)->get()->first();
            LogEvents::event($bso_dop_serie->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 15, $id, $type_bso_id, $request->all());


        }else{
            $bso_dop_serie = new BsoDopSerie();
            $bso_dop_serie->type_bso_id = $type_bso_id;
            $bso_dop_serie->insurance_companies_id = $id;
            $bso_dop_serie->bso_serie_id = $bso_serie_id;

            LogEvents::event($bso_dop_serie->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 15, $id, $type_bso_id, $request->all());
        }


        $bso_dop_serie->bso_dop_serie = $request->bso_dop_serie;
        $bso_dop_serie->save();

        return redirect(url("/directories/insurance_companies/$id/type_bso/$type_bso_id/bso_serie/{$bso_serie->id}/"))->with('success', trans('form.success_update'));
        //return parentReload();

    }

}
