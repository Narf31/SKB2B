<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\BsoSuppliers\FinancialPolicy;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicy;
use App\Models\Directories\FinancialPolicySegment;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class SegmentsController extends Controller
{

    public function __construct()
    {

    }



    public function edit($id, $bso_supplier_id, $financial_policy_id, $segment_id)
    {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();
        $financial_policy = FinancialPolicy::where('id', $financial_policy_id)->get()->first();

        if((int)$segment_id > 0){
            $segment = FinancialPolicySegment::where('id', $segment_id)->get()->first();
        }else{
            $segment = new FinancialPolicySegment();
        }

        return view('directories.insurance_companies.bso_suppliers.financial_policy.segments.edit', [
            'insurance_companies' => $sk,
            'bso_supplier' => $bso_supplier,
            'financial_policy' => $financial_policy,
            'segment' => $segment
        ]);

    }


    public function save($id, $bso_supplier_id, $financial_policy_id, $segment_id, Request $request)
    {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();
        $financial_policy = FinancialPolicy::where('id', $financial_policy_id)->get()->first();

        if($segment_id>0){
            $segment = FinancialPolicySegment::findOrFail($segment_id);
            LogEvents::event($segment->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE].' cегмента в ФП:'.$financial_policy->title, 19, $id, $bso_supplier_id, $request->all());
        }else{
            $segment = new FinancialPolicySegment(['insurance_companies_id' => $id, 'bso_supplier_id' => $bso_supplier_id, 'financial_policy_id' => $financial_policy_id]);
            LogEvents::event($segment->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE].' cегмента в ФП:'.$financial_policy->title, 19, $id, $bso_supplier_id, $request->all());
        }

        $kbm = $request->get('kbm');
        if(strlen($kbm)>0){
            $kbm = str_replace(' ', "", $kbm);
            $kbm = str_replace(',', ".", $kbm);
        }

        $insurer_kt = $request->get('insurer_kt');
        if(strlen($insurer_kt)>0){
            $insurer_kt = str_replace(' ', "", $insurer_kt);
            $insurer_kt = str_replace(',', ".", $insurer_kt);
        }

        $segment->fill([
            'insurer_type_id'     => $request->get('insurer_type_id'),
            'location_id'         => $request->get('location_id'),
            'period'              => $request->get('period'),
            'contract_type_id'    => $request->get('contract_type_id'),
            'vehicle_country_id'  => $request->get('vehicle_country_id'),
            'vehicle_power_from'  => $request->get('vehicle_power_from'),
            'vehicle_power_to'    => $request->get('vehicle_power_to'),
            'vehicle_age'         => $request->get('vehicle_age'),
            'has_trailer'         => (int)$request->get('has_trailer'),
            'period_any'          => (int)$request->get('period_any'),
            'is_multi_drive'      => (int)$request->get('is_multi_drive'),
            'drivers_min_age'     => (int)$request->get('drivers_min_age'),
            'drivers_min_exp'     => (int)$request->get('drivers_min_exp'),
            'owner_age'           => (int)$request->get('owner_age'),
            'vehicle_category_id' => (int)$request->get('vehicle_category_id'),
            'contract_type_any'   => (int)$request->get('contract_type_any'),
            'vehicle_power_any'   => (int)$request->get('vehicle_power_any'),
            'vehicle_age_any'     => (int)$request->get('vehicle_age_any'),
            'has_trailer_any'     => (int)$request->get('has_trailer_any'),
            'is_multi_drive_any'  => (int)$request->get('is_multi_drive_any'),
            'drivers_age_any'     => (int)$request->get('drivers_age_any'),
            'owner_age_any'       => (int)$request->get('owner_age_any'),
            'drivers_exp_any'     => (int)$request->get('drivers_exp_any'),
            'insurer_type_any'   => (int)$request->get('insurer_type_any'),
            'vehicle_country_any'   => (int)$request->get('vehicle_country_any'),
            'insurer_location_any'   => (int)$request->get('insurer_location_any'),
            'kbm_any'   => (int)$request->get('kbm_any'),
            'kbm'   => $kbm,
            'insurer_kt_any'   => (int)$request->get('insurer_kt_any'),
            'insurer_kt'   => $insurer_kt,
        ]);

        $segment->save();


        return parentReload();

    }


}
