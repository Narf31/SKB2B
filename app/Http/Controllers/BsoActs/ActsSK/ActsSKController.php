<?php

namespace App\Http\Controllers\BsoActs\ActsSK;

use App\Classes\Export\ExportManager;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\BsoActs\ActsSK\ActsListQueryHandler;
use App\Models\BSO\BsoLogs;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Organizations\Organization;
use App\Models\Settings\TemplateCategory;
use App\Repositories\FilesRepository;

class ActsSKController extends Controller {

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository) {

        $this->middleware('permissions:bso_acts,acts_sk');
        $this->filesRepository = $filesRepository;
        $this->breadcrumbs[] = [
            'label' => 'Акты в СК',
            'url' => 'bso_acts/acts_sk'
        ];
    }

    public function index() {
        return view('bso_acts.acts_sk.index');
    }

    public function get_filters() {
        return view('bso_acts.acts_sk.filters', $this->get_suppliers());
    }

    public function get_table() {
        $data = $this->get_suppliers();
        $data['html'] = view('bso_acts.acts_sk.table', $data)->render();
        return $data;
    }

    public function get_suppliers() {

        $this->validate(request(), [
            'org_id' => 'integer',
            'insurance_id' => 'integer',
            'supplier_id' => 'integer',
        ]);


        $organizations = Organization::query()->where('org_type_id', 1);
        $suppliers = BsoSuppliers::query()->orderBy('purpose_org_id');
        $insurances = InsuranceCompanies::query();

        if (request()->has('org_id') && request('org_id') > 0) {
            $suppliers = $suppliers->where('purpose_org_id', request('org_id'));
        }

        if (request()->has('insurance_id') && request('insurance_id') > 0) {
            $suppliers->where('insurance_companies_id', request('insurance_id'));
        }

        if (request()->has('supplier_id') && request('supplier_id') > 0) {
            $suppliers->where('id', request('supplier_id'));
        }

        $suppliers = $suppliers->get()->keyBy('id');

        $organizations->whereIn('id', $suppliers->pluck('purpose_org_id')->toArray());
        $insurances->whereIn('id', $suppliers->pluck('insurance_companies_id')->toArray());

        $organizations = $organizations->get()->keyBy('id');
        $insurances = $insurances->get()->keyBy('id');

        return [
            'organizations' => $organizations,
            'insurances' => $insurances,
            'suppliers' => $suppliers,
        ];
    }

    public function acts($supplier_id) {
        $supplier = BsoSuppliers::findOrFail($supplier_id);

        $this->breadcrumbs[] = [
            'label' => $supplier->title,
        ];
        
        return view('bso_acts.acts_sk.acts_list.index', [
            'supplier' => $supplier
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function acts_list($supplier_id) {

        $this->validate(request(), [
            'month' => 'integer',
            'year' => 'integer',
            'type' => 'integer',
            'page_count' => 'integer',
            'PAGE' => 'integer',
            'title' => 'string',
        ]);


        $supplier = BsoSuppliers::findOrFail($supplier_id);

        $acts = $supplier->reports_acts()->getQuery();

        $acts = (new ActsListQueryHandler($acts))->allowEmpty()->apply();

        $page = request()->get('PAGE') > 0 ? (int) request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int) request()->get('page_count') : 10;
        $result = PaginationHelper::paginate($acts, $page, $page_count);

        $acts = $result['builder']->orderBy('created_at', 'desc')->get();

        return [
            'acts' => $acts,
            'supplier' => $supplier,
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
        ];
    }

    public function acts_list_table($supplier_id) {
        $data = $this->acts_list($supplier_id);
        $data['html'] = view('bso_acts.acts_sk.acts_list.table', $data)->render();
        return $data;
    }

    public function edit($supplier_id, $act_id) {

        $supplier = BsoSuppliers::findOrFail($supplier_id);

        $act = $supplier->reports_acts()->where('id', $act_id)->firstOrFail();

        return view('bso_acts.acts_sk.act.index', [
            'supplier' => $supplier,
            'act' => $act,
        ]);
    }

    public function accept($supplier_id, $act_id) {

        $supplier = BsoSuppliers::findOrFail($supplier_id);

        $act = $supplier->reports_acts()->where('id', $act_id)->firstOrFail();

        $act->update([
            'accept_status' => 1,
            'accepted_at' => date('Y-m-d H:i:s'),
            'accept_user_id' => auth()->id()
        ]);

        if($act->type_id == 0){
            $act->bso_items()->getQuery()->update(['location_id' => 2]);
            foreach($act->bso_items as $bso_item){
                $bso_item->setBsoLog(2);
            }
        }else{
            foreach($act->payments as $payment){
                $payment->setBsoLogToPayment(2);
                $payment->bso()->update(['location_id' => 2]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function update($supplier_id, $act_id) {

        $this->validate(request(), [
            "title" => "string",
            "report_month" => "integer",
            "report_year" => "integer",
            "report_date_start" => "date",
            "report_date_end" => "date",
            "signatory_org" => "string",
            "signatory_sk_bso_supplier" => "string",
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);
        $act = $supplier->reports_acts()->where('id', $act_id)->firstOrFail();

        $upd = $act->update([
            "title" => request()->get('title'),
            "report_month" => request()->get('report_month'),
            "report_year" => request()->get('report_year'),
            "report_date_start" => request()->get('report_date_start'),
            "report_date_end" => request()->get('report_date_end'),
            "signatory_org" => request()->get('signatory_org'),
            "signatory_sk_bso_supplier" => request()->get('signatory_sk_bso_supplier'),
        ]);

        if ($upd) {
            return response()->json(['status' => 'ok']);
        }
    }

    public function delete_items($supplier_id, $act_id) {

        $this->validate(request(), [
            'item_ids' => 'array',
            'item_ids.*' => 'integer',
            'delete_act' => 'integer'
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);
        $act = $supplier->reports_acts()->where('id', $act_id)->firstOrFail();

        if ($act->type_id == 0) {

            $items = $act->bso_items()->whereIn('id', request()->get('item_ids'));

            foreach ($items->get() as $bso_item) {
                $bso_item->setBsoLog(7);
            }

            $items->update([
                'acts_sk_id' => -1, //текущий реестр
            ]);


        } elseif ($act->type_id == 1) {

            $items = $act->payments()->whereIn('id', request()->get('item_ids'));

            foreach ($items->get() as $payment) {
                $payment->setBsoLogToPayment(7);
            }

            $items->update([
                'acts_sk_id' => -1, //текущий реестр
            ]);
        }

        if (request()->get('delete_act')) {
            $act->delete();
        }

        return response()->json(['status' => 'ok']);
    }


    public function export($supplier_id, $act_id){

        $supplier = BsoSuppliers::findOrFail($supplier_id);
        $builder = $supplier->reports_acts()->where('id', $act_id)->getQuery();

        $act = $builder->firstOrFail();
        $code = $act->type_id == 0 ? 'sk_bso_to_sk' : 'contracts_to_sk';

        $category = TemplateCategory::get($code);

        return (new ExportManager($category, $builder))->handle();
    }



}
