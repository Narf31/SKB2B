<?php

namespace App\Http\Controllers\Reports\ReportsSK;

use App\Http\QueryHandlers\BsoActs\ActTransfer\ActListQueryHandler;
use App\Http\Controllers\Controller;
use App\Helpers\PaginationHelper;
use App\Models\BSO\BsoActs;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Organizations\Organization;
use App\Models\Reports\ReportOrders;
use App\Models\Settings\PaymentMethods;
use Illuminate\Http\Request;


class ReportsSKFormationController extends Controller
{

    protected $report_type = 0; //$report_type - 0 Бордеро 1 ДВОУ

    public function __construct(Request $request)
    {
        $this->middleware('permissions:reports,reports_edit');


        $Prefix = $request->route() ? $request->route()->getPrefix() : "";
        if(strpos($Prefix, 'bordereau') !== false) $this->report_type = 0;
        if(strpos($Prefix, 'dvoy') !== false) $this->report_type = 1;

        $this->breadcrumbs[] = [
            'label' => 'Отчеты ',
            'url' => 'reports/reports_sk'
        ];

    }


    public function index($organization_id){
        $organization = Organization::getIdOrg($organization_id);

        $this->breadcrumbs[] = [
            'label' => "Формирования отчета $organization->title ".$this->getReportName(),
        ];

        return view('reports.formation.index',[
            'organization' => $organization,
            'report_type' => $this->report_type,
            'report_name' => $this->getReportName(),
            'report_prefix' => $this->getReportPrefix(),

        ])->with('breadcrumbs', $this->breadcrumbs);
    }


    public function getReportName(){
        return ($this->report_type == 0)?'Бордеро':'ДВОУ';
    }

    public function getReportPrefix(){
        return ($this->report_type == 0)?'bordereau':'dvoy';
    }

    public function getReportSql(){
        return ($this->report_type == 0)?'reports_payments.reports_order_id':'reports_payments.reports_dvou_id';
    }


    public function get_table($organization_id, Request $request){

        $location_id = $request->location_id;
        $product_id = $request->product_id;

        $type_id = $request->type_id;
        $bso_suppliers = $request->bso_suppliers;
        $payment_methods = $request->payment_methods;

        $payment_type = $request->payment_type;
        $payment_flow = $request->payment_flow;

        $report_id = $request->report_id;

        $organization = Organization::getIdOrg($organization_id);
        $payments = $organization->getPayments($this->report_type);
        $payments->where($this->getReportSql(), $report_id);


        $payments->leftJoin('payments', 'payments.id', '=', 'reports_payments.payment_id');
        $payments->leftJoin('bso_items', 'bso_items.id', '=', 'payments.bso_id');
        $payments->leftJoin('bso_items as bso_items_bso_receipt', 'bso_items_bso_receipt.id', '=', 'payments.bso_receipt_id');


        if($product_id) $payments->whereIn('bso_items.product_id', $product_id);
        if($location_id) $payments->whereIn('bso_items.location_id', $location_id);

        if($type_id) $payments->whereIn('payments.type_id', $type_id);
        $payments->where('payments.statys_id', 1);//Только оплаченые

        if($bso_suppliers) $payments->whereIn('bso_items.bso_supplier_id', $bso_suppliers);
        if($payment_methods) $payments->whereIn('payments.payment_method_id', $payment_methods);

        if($payment_type) $payments->whereIn('payments.payment_type', $payment_type);
        if($payment_flow) $payments->whereIn('payments.payment_flow', $payment_flow);

        $payments->select("reports_payments.*");

        //dd(getLaravelSql($payments));

        return view('reports.formation.table',[
            'organization' => $organization,
            'report_type' => $this->report_type,
            'report_name' => $this->getReportName(),
            'report_prefix' => $this->getReportPrefix(),
            'payments' => $payments->get(),
        ]);


    }


    public function get_action_table($organization_id, Request $request){


        $organization = Organization::getIdOrg($organization_id);
        $reports = ($this->report_type == 0)?$organization->reports_border:$organization->reports_dvoy;
        $reports->where('accept_status', 0);


        return view('reports.formation.action_table', [
            'report_prefix' => $this->getReportPrefix(),
            'report_id' => $request->report_id,
            'reports' => $reports,
        ]);


    }


    public function execute($organization_id, Request $request){

        $status = 1;
        $report_id = 0;
        $report_dvou = false;

        $organization = Organization::getIdOrg($organization_id);
        $payment_ids = array_map('intval', request('payment_ids'));

        $payments = $organization->getPayments($this->report_type);
        $payments->whereIn("reports_payments.id", $payment_ids);


        //Создаем Отчет
        if((int)$request->event_id == 1){

            $report_data = [
                'type_id' => $this->report_type,
                'title' => request()->get('report_name'),
                'agent_organization_id' => $organization->id,
                'report_year' => request()->get('report_year'),
                'report_month' => request()->get('report_month'),
                'create_user_id' => auth()->id(),
                'report_date_start' => date('Y-m-d', strtotime(request()->get('report_date_start'))),
                'report_date_end' => date('Y-m-d', strtotime(request()->get('report_date_end'))),
            ];


            $report = ReportOrders::create($report_data);

        }

        //Добавляем в Отчет
        if((int)$request->event_id == 2){
            $report = ReportOrders::find((int)$request->get('to_report_id'));
        }


        foreach ($payments->get() as $payment){
            if((int)$request->event_id <= 0){
                //Движение ДОГОВОРА

                $payment->update([$this->getReportSql() => (int)$request->event_id]);

            }else{

                if($report)
                {
                    $payment->update([$this->getReportSql() => (int)$report->id]);
                    //Добовляем ЛОГ

                    $payment->payment->setBsoLogToPayment(10);


                    //Обновляем цыфры
                    $report->refreshSumOrder();

                    $report_id = $report->id;
                }

                if($report_dvou){
                    $payment->update(['reports_dvou_id' =>  (int)$report_dvou->id]);
                    //Обновляем цыфры
                    $report_dvou->refreshSumOrder();
                }

                //else $status = 0;

            }
        }



        //dd($payments->get());


        return response()->json(['status' => $status, 'report_id' => $report_id]);
    }



}
