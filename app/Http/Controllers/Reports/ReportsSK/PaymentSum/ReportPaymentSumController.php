<?php

namespace App\Http\Controllers\Reports\ReportsSK\PaymentSum;

use App\Http\Controllers\Controller;
use App\Models\Reports\AdverstisingReports;
use App\Models\Reports\ReportOrders;
use App\Models\Reports\ReportPaymentSum;

class ReportPaymentSumController extends Controller {


    public function __construct(){
        $this->middleware('permissions:reports,reports_payment');
    }

    public function create($report_id){
        $report = ReportOrders::query()->findOrFail($report_id);
        return view('reports.order.payment_sum.create', [
            'payment_sum' => new ReportPaymentSum(),
            'report' => $report
        ]);
    }

    public function edit($report_id, $id){
        $report = ReportOrders::query()->findOrFail($report_id);
        $payment_sum = ReportPaymentSum::query()->findOrFail((int)$id);
        return view('reports.order.payment_sum.edit', [
           'payment_sum' => $payment_sum,
           'report' => $report,
        ]);
    }


    public function store($report_id){

        $payment_sum = ReportPaymentSum::create([]);
        return $this->save($report_id, $payment_sum->id);
    }


    public function save($report_id, $id){
        $this->validate(request(), [
            'amount' => 'required',
            'type_id' => 'required',
        ]);
        $report = ReportOrders::query()->findOrFail($report_id);
        $payment_sum = ReportPaymentSum::query()->findOrFail((int)$id);
        $payment_sum->update([
            'amount' => getFloatFormat(request('amount', 0)),
            'type_id' => request('type_id', 0),
            'comments' => request('comments', ''),
            'user_id' => auth()->id(),
            'report_id' => $report_id,
        ]);

        $report->refreshAdvancePayment();

        return parentReload();
    }


    public function delete($report_id, $id){
        $report = ReportOrders::query()->findOrFail($report_id);
        ReportPaymentSum::query()->findOrFail((int)$id)->delete();
        $report->refreshAdvancePayment();

        return response()->json(['status' => 'ok']);
    }
}
