<?php

namespace App\Http\Controllers\Reports\ReportsSK;

use App\Classes\Export\ExportManager;
use App\Http\QueryHandlers\BsoActs\ActTransfer\ActListQueryHandler;
use App\Http\Controllers\Controller;
use App\Helpers\PaginationHelper;
use App\Models\BSO\BsoActs;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Organizations\Organization;
use App\Models\Reports\ReportOrders;
use App\Models\Settings\TemplateCategory;
use Illuminate\Http\Request;


class ReportsSKOrderController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:reports,reports_sk');
        $this->breadcrumbs[] = [
            'label' => 'Отчеты',
            'url' => 'reports/reports_sk'
        ];

    }


    public function index($report_id){

        $report = ReportOrders::getReportId($report_id);

        $this->breadcrumbs[] = [
            'label' => $report->organization->title,
            'url' => "{$report->organization->id}/info"
        ];

        $this->breadcrumbs[] = [
            'label' => ReportOrders::TYPE[$report->type_id].' ('.ReportOrders::STATE[$report->accept_status].')',
        ];

        return view('reports.order.index', [
            'report' => $report,
            'payments' => $report->getPayments()->get(),
        ])->with('breadcrumbs', $this->breadcrumbs);
    }


    public function save($report_id, Request $request){

        $report = ReportOrders::getReportId($report_id);

        $report->title = $request->title;

        $report->report_month = $request->report_month;
        $report->report_year = $request->report_year;


        $report->report_date_start = setDateTimeFormat($request->report_date_start);
        $report->report_date_end = setDateTimeFormat($request->report_date_end);

        $report->signatory_org = $request->signatory_org;
        $report->signatory_sk_bso_supplier = $request->signatory_sk_bso_supplier;
        $report->comments = $request->comments;

        $report->save();

        //Обновляем цыфры
        $report->refreshSumOrder();

        return redirect("/reports/order/$report_id")->with('success', trans('form.success_update'));
    }

    public function set_status($report_id, Request $request)
    {
        $report = ReportOrders::getReportId($report_id);
        $report->accept_status = $request->status;
        $report->save();
        return response()->json(['status' => 1]);
    }



    public function delete_payments($report_id, Request $request)
    {
        $status = 1;

        $report = ReportOrders::find($report_id);
        $payment_ids = array_map('intval', request('payment_ids'));

        $payments = $report->getPayments();
        $payments->whereIn("reports_payments.id", $payment_ids);


        $report_type = ($report->type_id == 0)?'reports_payments.reports_order_id':'reports_payments.reports_dvou_id';

        foreach ($payments->get() as $payment){

            $payment->update([$report_type => 0]);

            $payment->payment->setBsoLogToPayment(8);

        }

        //Обновляем цыфры
        $report->refreshSumOrder();


        return response()->json(['status' => $status]);

    }

    public function delete_order($report_id, Request $request)
    {
        $status = 1;
        $report = ReportOrders::find($report_id);
        $payments = $report->getPayments();
        $report_type = ($report->type_id == 0)?'reports_payments.reports_order_id':'reports_payments.reports_dvou_id';
        foreach ($payments->get() as $payment){

            $payment->update([$report_type => 0]);
            $payment->payment->setBsoLogToPayment(8);

        }

        $report->delete();

        return response()->json(['status' => $status]);
    }




    public function recalc_kv($report_id){

        $this->validate(request(), [
            'payment_ids' => 'array',
            'payment_ids.*' => 'integer',
            'kv_borderau' => 'integer',
            'kv_dvou' => 'integer',
            'marker_color' => 'integer',
            'marker_text' => 'string',
        ]);



        $result = ['status' => 'ok'];

        $report = ReportOrders::find($report_id);
        $payment_ids = array_map('intval', request('payment_ids'));

        $payments = $report->getPayments();
        $payments->whereIn("reports_payments.id", $payment_ids);

        foreach ($payments->get() as $payment){

            $payment_total = $payment->payment->payment_total;

            $new_borderau = request('kv_borderau', $payment->financial_policy_kv_bordereau);
            $new_dvou = request('kv_dvou', $payment->financial_policy_kv_dvoy);
            $new_borderau_total = getTotalSumToPrice(getFloatFormat($payment_total), getFloatFormat($new_borderau));
            $new_dvou_total = getTotalSumToPrice(getFloatFormat($payment_total), getFloatFormat($new_dvou));

            $updated_data = [
                'financial_policy_kv_bordereau' => $new_borderau,
                'financial_policy_kv_bordereau_total' => $new_borderau_total,
                'financial_policy_kv_dvoy' => $new_dvou,
                'financial_policy_kv_dvoy_total' => $new_dvou_total,
            ];

            $payment->update($updated_data);
            $payment->payment->setBsoLogToPayment(11);
        }

        if(!empty(request('marker_color')) && (int)request('marker_color') > 0){
            $this->marker_payments($report_id);
        }

        //Обновляем цифры
        $report->refreshSumOrder();

        return response()->json($result);

    }

    public function marker_payments($report_id)
    {

        $request = request();
        $status = 1;

        $report = ReportOrders::find($report_id);
        $payment_ids = array_map('intval', request('payment_ids'));

        $payments = $report->getPayments();
        $payments->whereIn("reports_payments.id", $payment_ids);

        foreach ($payments->get() as $payment){

            $color = ReportOrders::MARKER_COLORS[$request->marker_color];

            $payment->update([
                'marker_color' => $color['color'],
                'marker_text' => $request->marker_text,
            ]);

        }

        //Обновляем цыфры
        $report->refreshSumOrder();

        return response()->json(['status' => $status]);

    }



    public function form_report($report_id){
        $this->validate(request(),[
            'template_id' => 'integer'
        ]);

        $builder = ReportOrders::query()->where('id', $report_id);
        $report = $builder->firstOrFail();

        $category = TemplateCategory::get($report->getTemplateCategory());
        $supplier_id = $report->bso_supplier_id;

        return (new ExportManager($category, $builder, $supplier_id))->handle();
    }

}
