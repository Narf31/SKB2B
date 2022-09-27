<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\TypeBso;

use App\Http\Controllers\Controller;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\TypeBso;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class TypeBsoController extends Controller
{

    public function __construct()
    {

    }



    public function edit($id, $type_bso_id)
    {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();

        if((int)$type_bso_id > 0){
            $type_bso = TypeBso::where('insurance_companies_id', $id)->where('id', $type_bso_id)->get()->first();
        }else{
            $type_bso = new TypeBso();
        }

        return view('directories.insurance_companies.type_bso.edit', [
            'insurance_companies' => $sk,
            'type_bso' => $type_bso
        ]);

    }


    public function save($id, $type_bso_id, Request $request)
    {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();

        if((int)$type_bso_id > 0){
            $type_bso = TypeBso::where('insurance_companies_id', $id)->where('id', $type_bso_id)->get()->first();

            LogEvents::event($type_bso->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 13, $id, $type_bso->id, $request->all());


        }else{
            $type_bso = new TypeBso();
            $type_bso->insurance_companies_id = $id;

            LogEvents::event($type_bso->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 13, $id, $type_bso->id, $request->all());

        }


        //$type_bso->bso_class_id = $request->bso_class_id;
        //$type_bso->bso_serie = $request->bso_serie;
        $type_bso->title = $request->title;
        $type_bso->product_id = $request->product_id;
        $type_bso->is_actual = (int)$request->is_actual;
        $type_bso->min_yellow = (int)$request->min_yellow;
        $type_bso->min_red = (int)$request->min_red;
        //$type_bso->day_sk = (int)$request->day_sk;
        $type_bso->day_agent = (int)$request->day_agent;

        $type_bso->save();

        if((int)$type_bso_id > 0){
            return parentReload();
        }

        return redirect(url("/directories/insurance_companies/$id/type_bso/{$type_bso->id}/"))->with('success', trans('form.success_update'));
        //return parentReload();

    }


}
