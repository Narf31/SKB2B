<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\BsoSuppliers;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class BsoSuppliersController extends Controller {

    public function __construct() {
        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];

        $this->breadcrumbs[] = [
            'label' => 'Страховые компании',
            'url' => 'directories/insurance_companies',
        ];
    }

    public function edit($id, $bso_supplier_id) {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();

        $this->breadcrumbs[] = [
            'label' => $sk->title,
            'url' => $id,
        ];

        if ((int) $bso_supplier_id > 0) {
            $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();

            $this->breadcrumbs[] = [
                'label' => 'Филиал - ' . $bso_supplier->title,
            ];
        } else {
            $bso_supplier = new BsoSuppliers();

            $this->breadcrumbs[] = [
                'label' => 'Филиал - Новый',
            ];
        }

        return view('directories.insurance_companies.bso_suppliers.edit', [
                    'insurance_companies' => $sk,
                    'bso_supplier' => $bso_supplier
                ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function save($id, $bso_supplier_id, Request $request) {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();

        //dd($request->all());
        if ((int) $bso_supplier_id > 0) {
            $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();
            LogEvents::event($bso_supplier->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 16, $id, $bso_supplier_id, $request->all());
        } else {
            $bso_supplier = new BsoSuppliers();
            $bso_supplier->insurance_companies_id = $id;

            LogEvents::event($bso_supplier->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 16, $id, $bso_supplier_id, $request->all());
        }


        $bso_supplier->title = $request->title;
        $bso_supplier->signer = $request->signer;
        $bso_supplier->is_actual = (int) $request->is_actual;
        $bso_supplier->source_org_id = (int) $request->source_org_id;
        $bso_supplier->purpose_org_id = (int) $request->purpose_org_id;
        $bso_supplier->city_id = (int) $request->city_id;
        $bso_supplier->save();

        return redirect(url("/directories/insurance_companies/$id/bso_suppliers/{$bso_supplier->id}/"))->with('success', trans('form.success_update'));
    }

}
