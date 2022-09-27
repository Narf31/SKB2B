<?php

namespace App\Http\Controllers\Reports\ReportsSK;

use App\Http\QueryHandlers\BsoActs\ActTransfer\ActListQueryHandler;
use App\Http\Controllers\Controller;
use App\Helpers\PaginationHelper;
use App\Models\BSO\BsoActs;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Organizations\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsSKController extends Controller {

    public function __construct() {
        $this->middleware('permissions:reports,reports_sk');
        $this->breadcrumbs[] = [
            'label' => 'Отчеты',
            'url' => 'reports/reports_sk'
        ];
    }

    public function index(Request $request)
    {
        $organizations = Organization::getALLOrg();

        $organization_id = (isset($request->organization_id))?$request->organization_id:0;
        $curator_id = (isset($request->curator_id))?$request->curator_id:0;

        $is_redirect = 1;

        if((int)$organization_id > 0){
            $organizations->where('id', (int)$organization_id);
            $is_redirect = 0;
        }

        if((int)$curator_id > 0){
            $organizations->where('curator_id', (int)$curator_id);
            $is_redirect = 0;
        }

        $organizations = $organizations->get();


        if(count($organizations) == 1 && $is_redirect == 1){
            return redirect("/reports/reports_sk/{$organizations[0]->id}/info");
        }

        return view('reports.reports_sk.index', [
            'organizations' => $organizations,
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function reports($organization_id)
    {
        $organization = Organization::getIdOrg($organization_id);

        $this->breadcrumbs[] = [
            'label' => $organization->title,
        ];


        return view('reports.list.index', [
            'organization' => $organization,
            'motion' => request()->has('motion') && request('motion') > 0 && request('motion') < 3 ? request('motion') : 0
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function reports_table($organization_id, Request $request) {

        $result = [
            'perpage' => 10,
            'page' => 1,
        ];

        $organization = Organization::getIdOrg($organization_id);
        $reports = $organization->reports();
        $reports->where('reports_orders.is_deleted', '=', 0);

        if($request->id && $request->id > 0){
            $reports->where('reports_orders.id', '=', $request->id);
        }


        if($request->payment_flow_type && $request->payment_flow_type > -1){
            $temp = array_flip($request->payment_flow_type);
            if (!collect($temp)->has(-1)){
                $reports->whereIn('reports_orders.payment_flow_type', $request->payment_flow_type);
            }
        }

        if ($request->type_id) {
            $reports->whereIn('reports_orders.type_id', $request->type_id);
        }
        if ($request->accept_status) {
            $reports->whereIn('reports_orders.accept_status', $request->accept_status);
        }
        if ($request->report_month) {
            $reports->whereIn('reports_orders.report_month', $request->report_month);
        }

        if ($request->like_title && strlen($request->like_title) >= 3) {
            $reports->where('reports_orders.title', 'like', "%{$request->like_title}%");
        }

        if ($request->year) {
            $reports->where('reports_orders.report_year', '=', $request->year);
        }


        if ($request->motion && $request->motion > 0 && $request->motion < 3){
            if ($request->motion == 1){
                $reports->where('reports_orders.to_transfer_total', '>', 0);
            }elseif($request->motion == 2){
                $reports->where('reports_orders.to_return_total', '>', 0);
            }
        }

        $result['count'] = $reports->count();

        if ($request->page_count > 0) {
            $result['perpage'] = $request->page_count;
        }

        if($request->use_by_button == 1 && $request->use_by_button != $request->used_by_button){
            $result['page'] = 1;
        }elseif ($request->page > 0) {
            $result['page'] = $request->page;
        }

        $reports->offset($result['perpage'] * ($result['page'] - 1));
        $reports->limit($result['perpage']);

        $result['used_by_button'] = $request->use_by_button == 1 ? 1 : 0;

        $result['html'] = view('reports.list.table', [
            'reports' => $reports->get(),
        ])->render();


        return $result;
    }

}
