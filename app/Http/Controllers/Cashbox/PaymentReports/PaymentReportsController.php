<?php

namespace App\Http\Controllers\Cashbox\PaymentReports;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\Cashbox\Invoice\InvoicesQueryHandler;
use App\Models\Finance\Invoice;
use App\Models\Reports\ReportOrders;
use App\Models\User;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class PaymentReportsController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:cashbox,payment_reports');

        $this->breadcrumbs[] = [
            'label' => 'Оплата отчетов',
            'url' => 'cashbox/payment_reports'
        ];

    }



    public function index(Request $request)
    {
        return view('cashbox.payment_reports.index')->with('breadcrumbs', $this->breadcrumbs);
    }


    public function reports_table(Request $request)
    {
        $result = [
            'perpage' => 10,
            'page' => 1,
        ];

        $reports = ReportOrders::query();


        $reports->where('reports_orders.is_deleted', '=', 0);

        if($request->id && $request->id > 0){
            $reports->where('reports_orders.id', '=', $request->id);
        }

        if ($request->organizations_id) {
            $reports->where('reports_orders.agent_organization_id', $request->organizations_id);
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
