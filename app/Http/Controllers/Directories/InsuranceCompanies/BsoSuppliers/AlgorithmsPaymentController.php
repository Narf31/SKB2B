<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\BsoSuppliers;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\HoldKv;
use App\Models\Directories\HoldKvDocuments;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use App\Models\Settings\FinancialGroupPayment;
use App\Models\Settings\PaymentMethods;
use Illuminate\Http\Request;
use App\Models\Settings\IntegrationsVersionsSupplierFormValues;
use App\Models\Settings\IntegrationsVersions;

class AlgorithmsPaymentController extends Controller {

    public function __construct() {

    }



    public function edit($id, $bso_supplier_id, $hold_kv_id, $group_id, $algorithm_id)
    {
        if($algorithm_id > 0)
        {
            $algorithm = InstallmentAlgorithms::findOrFail($algorithm_id);
        }else{
            $algorithm = new InstallmentAlgorithms();
        }


        return view('directories.insurance_companies.bso_suppliers.hold_kv.installment_algorithms_payment.edit', [
            'id' => $id,
            'bso_supplier_id' => $bso_supplier_id,
            'hold_kv_id' => $hold_kv_id,
            'group_id' => $group_id,
            'algorithm_id' => $algorithm_id,
            'algorithm' => $algorithm
        ]);

    }

    public function save($id, $bso_supplier_id, $hold_kv_id, $group_id, $algorithm_id, Request $request)
    {
        if($algorithm_id > 0)
        {
            $algorithm = InstallmentAlgorithms::findOrFail($algorithm_id);
        }else{
            $hold_kv = HoldKv::findOrFail($hold_kv_id);

            $algorithm = new InstallmentAlgorithms();
            $algorithm->insurance_companies_id = $id;
            $algorithm->bso_supplier_id = $bso_supplier_id;
            $algorithm->product_id = $hold_kv->product_id;
            $algorithm->hold_kv_id = $hold_kv_id;
            $algorithm->group_id = $group_id;
        }

        $algorithm->is_underwriting = (int)$request->is_underwriting;
        $algorithm->algorithm_id = $request->algorithm_id;
        $algorithm->save();

        return parentReload();

    }

    public function info($id, $bso_supplier_id, $hold_kv_id, Request $request)
    {
        $group_id = $request->group_id;
        $hold_kv = HoldKv::findOrFail($hold_kv_id);
        $algorithms = InstallmentAlgorithms::where('insurance_companies_id', $id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('product_id', $hold_kv->product_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('group_id', $group_id)
            ->get();

        return view('directories.insurance_companies.bso_suppliers.hold_kv.installment_algorithms_payment.info', [
            'id' => $id,
            'bso_supplier_id' => $bso_supplier_id,
            'hold_kv_id' => $hold_kv_id,
            'group_id' => $group_id,
            'algorithms' => $algorithms
        ]);

    }

    public function delete($id, $bso_supplier_id, $hold_kv_id, $algorithm_id)
    {
        InstallmentAlgorithms::findOrFail($algorithm_id)->delete();
        return response(200);
    }



}
