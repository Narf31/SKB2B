<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\BsoSuppliers;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\HoldKv;
use App\Models\Directories\HoldKvDocuments;
use App\Models\Directories\HoldKvMatching;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsCategory;
use App\Models\Log\LogEvents;
use App\Models\Settings\FinancialGroupPayment;
use App\Models\Settings\PaymentMethods;
use Illuminate\Http\Request;
use App\Models\Settings\IntegrationsVersionsSupplierFormValues;
use App\Models\Settings\IntegrationsVersions;

class MatchingTermsController extends Controller {

    public function __construct() {

    }



    public function edit($id, $bso_supplier_id, $hold_kv_id, $group_id, $type, $matching_id, Request $request)
    {
        $hold_kv = HoldKv::findOrFail($hold_kv_id);

        if(!\View::exists("directories.insurance_companies.bso_suppliers.hold_kv.matching.{$hold_kv->product->category->template}.edit")){
            abort(303);
        }

        $info = null;
        if($matching_id > 0)
        {
            $matching = HoldKvMatching::findOrFail($matching_id);
            $info = \GuzzleHttp\json_decode($matching->json, true);
        }else{
            $matching = new HoldKvMatching();
        }

        return view("directories.insurance_companies.bso_suppliers.hold_kv.matching.{$hold_kv->product->category->template}.edit", [
            'id' => $id,
            'bso_supplier_id' => $bso_supplier_id,
            'hold_kv_id' => $hold_kv_id,
            'group_id' => $group_id,
            'matching_id' => $matching_id,
            'type' => $type,
            'matching' => $matching,
            'info' => $info,
        ]);


    }

    public function save($id, $bso_supplier_id, $hold_kv_id, $group_id, $type, $matching_id, Request $request)
    {



        if($matching_id > 0)
        {
            $matching = HoldKvMatching::findOrFail($matching_id);
        }else{
            $hold_kv = HoldKv::findOrFail($hold_kv_id);

            $matching = new HoldKvMatching();
            $matching->insurance_companies_id = $id;
            $matching->bso_supplier_id = $bso_supplier_id;
            $matching->product_id = $hold_kv->product_id;
            $matching->hold_kv_id = $hold_kv_id;
            $matching->group_id = $group_id;

            $matching->type = $type;
            $matching->category = $hold_kv->product->category->template;

        }



        $matching->json = \GuzzleHttp\json_encode($request->matching);
        $matching->save();
        $matching->saveTitle($matching->json);


        return parentReload();

    }

    public function getMatchingTerms($id, $bso_supplier_id, $hold_kv_id, Request $request)
    {
        $type = $request->type;
        $group_id = $request->group_id;

        $hold_kv = HoldKv::findOrFail($hold_kv_id);

        $matchings = HoldKvMatching::where('insurance_companies_id', $id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('product_id', $hold_kv->product_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('group_id', $group_id)
            ->where('type', $type)
            ->get();

        return view('directories.insurance_companies.bso_suppliers.hold_kv.matching.list', [
            'id' => $id,
            'bso_supplier_id' => $bso_supplier_id,
            'hold_kv_id' => $hold_kv_id,
            'group_id' => $group_id,
            'type' => $type,
            'matchings' => $matchings
        ]);

    }

    public function delete($id, $bso_supplier_id, $hold_kv_id, $group_id, $type, $matching_id)
    {

        HoldKvMatching::findOrFail($matching_id)->delete();
        return response(200);

    }



}
