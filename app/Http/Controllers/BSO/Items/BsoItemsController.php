<?php

namespace App\Http\Controllers\BSO\Items;

use App\Http\Controllers\Controller;
use App\Models\BSO\BsoItem;
use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\TypeBso;
use App\Models\User;
use Illuminate\Http\Request;

class BsoItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:bso,items');
        $this->breadcrumbs[] = [
            'label' => 'БСО',
        ];
        
        $this->breadcrumbs[] = [

        ];
    }

    public function index($id)
    {

        $bso = BsoItem::find($id);
        $agents = User::getALLUser(24)->pluck('name', 'id');

        $this->breadcrumbs[] = [
            'label' => $bso->bso_title,
        ];
        
        return view('bso.items.index', [
            'bso' => $bso,
            'agents' => $agents,
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function supplier_org($id)
    {
        $bso = BsoItem::find($id);
        $supplier = BsoSuppliers::where('is_actual', 1)->where('insurance_companies_id', $bso->insurance_companies_id);

        return view('bso.items.edit_supplier_org', [
            'bso' => $bso,
            'supplier' => $supplier->get()
        ]);
    }


    public function edit_supplier_org($id, Request $request)
    {
        $bso = BsoItem::find($id);
        $supplier = BsoSuppliers::find($request->bso_suppliers_id);
        $bso->bso_supplier_id = $supplier->id;
        $bso->org_id = $supplier->purpose_org_id;
        $bso->save();

        return parentReload();

    }


    public function bso_title($id)
    {
        $bso = BsoItem::find($id);
        $bso_type = TypeBso::where('type_bso.is_actual', 1)
            ->where('type_bso.insurance_companies_id', $bso->insurance_companies_id)
            ->orderBy('title', 'asc')
            ->get();

        $bso_serie = BsoSerie::where('insurance_companies_id', $bso->insurance_companies_id)
            ->where('type_bso_id', $bso->type_bso_id)->get();

        $bso_dop_serie = BsoDopSerie::where('bso_serie_id', $bso->bso_serie_id)->get();


        return view('bso.items.bso_title', [
            'bso' => $bso,
            'bso_type' => $bso_type->pluck('title', 'id'),
            'bso_serie' => $bso_serie->pluck('bso_serie', 'id'),
            'bso_dop_serie' => $bso_dop_serie->pluck('bso_dop_serie', 'id'),
        ]);
    }


    public function edit_bso_title($id, Request $request)
    {
        //Изменять номер БСО
        $bso = BsoItem::find($id);

        $bso_serie = BsoSerie::find($request->bso_serie_id);
        $bso_dop_serie = BsoDopSerie::find($request->bso_dop_serie_id);


        $bso->type_bso_id = $request->bso_type;

        $bso->bso_serie_id = $bso_serie->id;
        $bso->bso_number = $request->bso_number;
        $bso->bso_dop_serie_id = ($bso_dop_serie)?$bso_dop_serie->id:0;

        $bso_title = $bso_serie->bso_serie.' '.$bso->bso_number;

        if($bso_dop_serie){
            $bso_title .= $bso_dop_serie->bso_dop_serie;
        }

        $bso->bso_title = $bso_title;

        

        $bso->save();


        return parentReload();

    }


    public function bso_state($id)
    {
        $bso = BsoItem::find($id);

        return view('bso.items.bso_state', [
            'bso' => $bso,
        ]);
    }

    public function edit_bso_state($id, Request $request)
    {
        $bso = BsoItem::find($id);

        $bso->state_id = $request->state_id;
        $bso->location_id = $request->location_id;
        $bso->save();

        return parentReload();
    }
}
