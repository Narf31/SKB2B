<?php

namespace App\Http\Controllers\Directories\InsuranceCompanies\BsoSuppliers;

use App\Http\Controllers\Controller;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\HoldKv;
use App\Models\Directories\HoldKvDocuments;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Log\LogEvents;
use App\Models\Settings\FinancialGroupPayment;
use App\Models\Settings\FinancialGroupPaymentInfo;
use App\Models\Settings\PaymentMethods;
use Illuminate\Http\Request;
use App\Models\Settings\IntegrationsVersionsSupplierFormValues;
use App\Models\Settings\IntegrationsVersions;

class HoldKvController extends Controller {

    public function __construct() {
        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];

        $this->breadcrumbs[] = [
            'label' => 'Страховые компании',
            'url' => 'directories/insurance_companies',
        ];
    }

    public function create($id, $bso_supplier_id) {
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();


        $this->breadcrumbs[] = [
            'label' => $sk->title,
            'url' => $id,
        ];

        $this->breadcrumbs[] = [
            'label' => 'Филиал - ' . $bso_supplier->title,
            'url' => 'bso_suppliers/' . $bso_supplier->id,
        ];

        $this->breadcrumbs[] = [
            'label' => 'Настройка продуктов - создать',
        ];

        return view('directories.insurance_companies.bso_suppliers.hold_kv.create', [
                    'insurance_companies' => $sk,
                    'bso_supplier' => $bso_supplier,
                ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function edit($id, $bso_supplier_id, $hold_kv_id) {
        $hold_kv = HoldKv::findOrFail($hold_kv_id);


        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();

        $this->breadcrumbs[] = [
            'label' => $sk->title,
            'url' => $id,
        ];

        $this->breadcrumbs[] = [
            'label' => 'Филиал - ' . $bso_supplier->title,
            'url' => 'bso_suppliers/' . $bso_supplier->id,
        ];

        $this->breadcrumbs[] = [
            'label' => 'Настройка продуктов',
        ];

        return view('directories.insurance_companies.bso_suppliers.hold_kv.edit', [
                    'insurance_companies' => $sk,
                    'bso_supplier' => $bso_supplier,
                    'hold_kv' => $hold_kv,
                ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function store($id, $bso_supplier_id, Request $request) {
        $hold_kv_id = $this->save(new HoldKv, $id, $bso_supplier_id, $request);
        return redirect("/directories/insurance_companies/$id/bso_suppliers/$bso_supplier_id/hold_kv/$hold_kv_id/edit");
    }

    public function update($id, $bso_supplier_id, $hold_kv_id, Request $request) {
        $hold_kv = HoldKv::findOrFail($hold_kv_id);
        $this->save($hold_kv, $id, $bso_supplier_id, $request);

        return redirect("/directories/insurance_companies/$id/bso_suppliers/$bso_supplier_id/hold_kv/$hold_kv_id/edit");
    }

    private function save(HoldKv $hold_kv, $id, $bso_supplier_id, Request $request) {

        //$hold_kv->hold_type_id = (int) $request->hold_type_id;
        $hold_kv->product_id = (int) $request->product_id;
        $hold_kv->insurance_companies_id = (int) $id;
        $hold_kv->bso_supplier_id = (int) $bso_supplier_id;

        //$hold_kv->is_auto_bso = (int)$request->is_auto_bso;
        $hold_kv->bso_class_id = (int)$request->bso_class_id;



        if (isset($request->is_check_policy))
            $hold_kv->is_check_policy = (int) $request->is_check_policy;
        else
            $hold_kv->is_check_policy = 0;
        if (isset($request->is_many_files))
            $hold_kv->is_many_files = (int) $request->is_many_files;
        else
            $hold_kv->is_many_files = 0;
        if (isset($request->many_text))
            $hold_kv->many_text = $request->many_text;

        $hold_kv->save();

        LogEvents::event($hold_kv->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE] . ' ' . $hold_kv->product->title, 17, $id, $bso_supplier_id, $request->all());


        if (isset($request->file_title)) {

            $file_title = $request->get("file_title");
            $file_name = $request->get("file_name");
            $is_required = $request->get("is_required");
            $program_id = $request->get("program_id");

            HoldKvDocuments::where('hold_kv_id', $hold_kv->id)->update(['is_delete' => 1]);

            foreach ($file_title as $key => $file_t) {
                $hold_kv_doc = HoldKvDocuments::where('hold_kv_id', $hold_kv->id)->where('file_title', $file_title[$key])->get()->first();

                if (!$hold_kv_doc) {
                    $hold_kv_doc = HoldKvDocuments::create([
                                'hold_kv_id' => $hold_kv->id,
                    ]);
                }

                $hold_kv_doc->update([
                    'hold_kv_id' => $hold_kv->id,
                    'file_title' => $file_title[$key],
                    'file_name' => $file_name[$key],
                    'is_required' => $is_required[$key],
                    'program_id' => $program_id[$key],
                    'is_delete' => 0,
                ]);
            }

            HoldKvDocuments::where('hold_kv_id', $hold_kv->id)->where('is_delete', 1)->delete();
        }else{
            HoldKvDocuments::where('hold_kv_id', $hold_kv->id)->delete();
        }



        return $hold_kv->id;
    }

    public function destroy($id, $bso_supplier_id, $hold_kv_id) {

        $hold_kv = HoldKv::findOrFail($hold_kv_id);

        LogEvents::event($hold_kv_id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE] . ' ' . $hold_kv->product->title, 17, $id, $bso_supplier_id);


        $hold_kv->delete();

        return response('', 200);
    }

    public function supplier_form($id, $bso_supplier_id, $hold_kv_id, Request $request) {
        $errors = [];
        $hold_kv = HoldKv::findOrFail($hold_kv_id);
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();

        $formValues = IntegrationsVersionsSupplierFormValues::where('insurance_companies_id', '=', $hold_kv['insurance_companies_id'])
                ->where('hold_kv_id', '=', $hold_kv['id'])
                ->where('bso_supplier_id', '=', $hold_kv['bso_supplier_id'])
                ->where('product_id', '=', $hold_kv['product_id']);

        $versions = IntegrationsVersions::get();

        if ($request->isMethod('post')) {
            $post = $request->all();
            /* Validate */
            $version = IntegrationsVersions::findOrFail($request->version_id);
            if (class_exists($version->integration_class)) {
                $api = new $version->integration_class;
                $form = $api->getSupplierForm();

                if ($formValues->count()) {
                    $formValues->delete();
                }

                foreach ($form as $field) {
                    if (isset($post[$field['name']])) {
                        $integrationsVersionsMainFormValues = new IntegrationsVersionsSupplierFormValues;
                        $integrationsVersionsMainFormValues->form_key = $field['name'];
                        $integrationsVersionsMainFormValues->value = $post[$field['name']] ?? '';
                        $integrationsVersionsMainFormValues->version_id = $version->id;
                        $integrationsVersionsMainFormValues->insurance_companies_id = $hold_kv['insurance_companies_id'];
                        $integrationsVersionsMainFormValues->bso_supplier_id = $hold_kv['bso_supplier_id'];
                        $integrationsVersionsMainFormValues->product_id = $hold_kv['product_id'];
                        $integrationsVersionsMainFormValues->hold_kv_id = $hold_kv['id'];
                        $integrationsVersionsMainFormValues->save();
                    }
                }
                return parentReload();
            }
        }

        return view('settings.system.integration.version.supplier_form', [
            'messages' => $errors,
            'formValues' => $formValues,
            'insurance_companies' => $sk,
            'bso_supplier' => $bso_supplier,
            'hold_kv' => $hold_kv,
            'versions' => $versions,
        ]);
    }

    public function supplier_form_edit($id, $bso_supplier_id, $hold_kv_id, $version_id, Request $request) {
        $errors = [];
        $hold_kv = HoldKv::findOrFail($hold_kv_id);
        $sk = InsuranceCompanies::where('id', $id)->get()->first();
        $bso_supplier = BsoSuppliers::where('insurance_companies_id', $id)->where('id', $bso_supplier_id)->get()->first();

        $formValues = IntegrationsVersionsSupplierFormValues::where('insurance_companies_id', '=', $hold_kv['insurance_companies_id'])
                ->where('hold_kv_id', '=', $hold_kv['id'])
                ->where('bso_supplier_id', '=', $hold_kv['bso_supplier_id'])
                ->where('product_id', '=', $hold_kv['product_id'])
                ->where('version_id', '=', $version_id);

        $versions = IntegrationsVersions::get();

        if ($request->isMethod('post')) {
            $post = $request->all();
            /* Validate */
            $version = IntegrationsVersions::findOrFail($request->version_id);
            if (class_exists($version->integration_class)) {
                $api = new $version->integration_class;
                $form = $api->getSupplierForm();

                if ($formValues->count()) {
                    $formValues->delete();
                }

                foreach ($form as $field) {
                    if (isset($post[$field['name']])) {
                        $integrationsVersionsMainFormValues = new IntegrationsVersionsSupplierFormValues;
                        $integrationsVersionsMainFormValues->form_key = $field['name'];
                        $integrationsVersionsMainFormValues->value = $post[$field['name']] ?? '';
                        $integrationsVersionsMainFormValues->version_id = $version->id;
                        $integrationsVersionsMainFormValues->insurance_companies_id = $hold_kv['insurance_companies_id'];
                        $integrationsVersionsMainFormValues->bso_supplier_id = $hold_kv['bso_supplier_id'];
                        $integrationsVersionsMainFormValues->product_id = $hold_kv['product_id'];
                        $integrationsVersionsMainFormValues->hold_kv_id = $hold_kv['id'];
                        $integrationsVersionsMainFormValues->save();
                    }
                }
                return parentReload();
            }
        }

        return view('settings.system.integration.version.supplier_form_edit', [
            'messages' => $errors,
            'formValues' => $formValues->get()->keyBy('form_key')->toArray(),
            'insurance_companies' => $sk,
            'bso_supplier' => $bso_supplier,
            'hold_kv' => $hold_kv,
            'versions' => $versions,
            'version_id' => $version_id,
        ]);
    }

    public function supplier_select_form(Request $request) {
        $version = IntegrationsVersions::findOrFail($request->id);
        if (class_exists($version->integration_class)) {
            $api = new $version->integration_class;
            $form = $api->getSupplierForm();
            $html = "";
            foreach ($form as $field) {
                $html .= view('settings.system.integration.version.form_part', [
                    'field' => $field, 'formValues' => unserialize(base64_decode($request->formValues)),
                ]);
            }




            return $html;
        }
    }


    public function get_group_info($id, $bso_supplier_id, $hold_kv_id, Request $request)
    {
        $payment_info = FinancialGroupPaymentInfo::getPaymentInfo($id, $bso_supplier_id, $hold_kv_id, $request->group_id, $request->bso_class_id);
        if(!isset($payment_info)) {
            $payment_info = new FinancialGroupPaymentInfo();
        }
        return view('directories.insurance_companies.bso_suppliers.hold_kv.payment_type', [
            'group_id' => $request->group_id,
            'bso_class_id' => $request->bso_class_id,
            'group_payment_info' => $payment_info,
            'group_payment' => new FinancialGroupPayment(),
            'payments_type' => PaymentMethods::all(),
            'insurance_companies' => $id,
            'bso_supplier' => $bso_supplier_id,
            'hold_kv' => $hold_kv_id,
        ]);

    }

    public function group_save($id, $bso_supplier_id, $hold_kv_id, Request $request)
    {
        $payment_info = FinancialGroupPaymentInfo::getPaymentInfo($id, $bso_supplier_id, $hold_kv_id, $request->group_id, $request->bso_class_id, true);
        $payment_info->hold_type_id = $request->hold_type_id;
        $payment_info->is_auto_bso = $request->is_auto_bso;
        $payment_info->save();
        FinancialGroupPayment::savePayment($id, $bso_supplier_id, $hold_kv_id, $request->group_id, $request->payment_type, $request->bso_class_id);
        return response(200);
    }

    public function group_delete($id, $bso_supplier_id, $hold_kv_id, Request $request)
    {
        FinancialGroupPaymentInfo::deleteOthersPaymentInfo($id, $bso_supplier_id, $hold_kv_id, $request->bso_class_id);
        FinancialGroupPayment::deleteOthersPayment($id, $bso_supplier_id, $hold_kv_id, $request->bso_class_id);

        return response(200);
    }

}
