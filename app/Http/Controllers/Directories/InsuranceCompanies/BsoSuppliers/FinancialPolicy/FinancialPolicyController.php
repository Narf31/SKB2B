<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\BsoSuppliers\FinancialPolicy;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicy;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class FinancialPolicyController extends Controller {

    public function __construct() {
        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];

        $this->breadcrumbs[] = [
            'label' => 'Страховые компании',
            'url' => 'directories/insurance_companies',
        ];
    }

    public function edit($id, $bso_supplier_id, $financial_policy_id) {

        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();

        $this->breadcrumbs[] = [
            'label' => $sk->title,
            'url' => $id,
        ];

        $this->breadcrumbs[] = [
            'label' => 'Поставщик БСО - ' . $bso_supplier->title,
            'url' => 'bso_suppliers/' . $bso_supplier->id,
        ];

        if ((int) $financial_policy_id > 0) {
            $financial_policy = FinancialPolicy::where('id', $financial_policy_id)->get()->first();
            $this->breadcrumbs[] = [
                'label' => 'Финполитика - '.$financial_policy->title,
            ];
        } else {
            $financial_policy = new FinancialPolicy();
            $this->breadcrumbs[] = [
                'label' => 'Новая финполитика',
            ];
        }




        return view('directories.insurance_companies.bso_suppliers.financial_policy.edit', [
            'insurance_companies' => $sk,
            'bso_supplier' => $bso_supplier,
            'financial_policy' => $financial_policy
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function save($id, $bso_supplier_id, $financial_policy_id, Request $request) {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();

        if ((int) $financial_policy_id > 0) {
            $financial_policy = FinancialPolicy::where('id', $financial_policy_id)->get()->first();
            LogEvents::event($financial_policy->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 18, $id, $bso_supplier_id, $request->all());
        } else {
            $financial_policy = new FinancialPolicy();
            LogEvents::event($financial_policy->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 18, $id, $bso_supplier_id, $request->all());
        }

        $financial_policy->title = $request->title;
        $financial_policy->date_active = getDateFormatEn($request->date_active);
        $financial_policy->is_actual = (int) $request->is_actual;
        $financial_policy->product_id = (int) $request->product_id;

        $financial_policy->kv_bordereau = getFloatFormat($request->kv_bordereau);
        $financial_policy->kv_dvou = getFloatFormat($request->kv_dvou);
        $financial_policy->kv_sk = getFloatFormat($request->kv_bordereau) + getFloatFormat($request->kv_dvou);
        $financial_policy->kv_parent = getFloatFormat($request->kv_parent);

        $financial_policy->bso_supplier_id = (int) $bso_supplier_id;
        $financial_policy->insurance_companies_id = (int) $id;

        $financial_policy->save();

        $financial_policy->groups()->detach();

        foreach ((array) $request->financialPolicyGroups as $financialPolicyGroupId => $financialPolicyGroup) {
            \DB::table('financial_policies_groups_kv')->insert([
                'financial_policy_id' => $financial_policy->id,
                'financial_policies_group_id' => $financialPolicyGroupId,
                'kv_sk' => getFloatFormat($financialPolicyGroup['kv_borderau']) + getFloatFormat($financialPolicyGroup['kv_dvou']),
                'kv_parent' => getFloatFormat($financialPolicyGroup['kv_parent']),
                'kv_borderau' => getFloatFormat($financialPolicyGroup['kv_borderau']),
                'kv_dvou' => getFloatFormat($financialPolicyGroup['kv_dvou']),

                'is_actual' => (int) isset($financialPolicyGroup['is_actual']),
            ]);
        }


        return redirect(url("/directories/insurance_companies/$id/bso_suppliers/{$bso_supplier->id}/financial_policy/{$financial_policy->id}/"))->with('success', trans('form.success_update'));
    }

}
