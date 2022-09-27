<?php

namespace App\Command\BsoActs\ActsSK\Contracts;

use Closure;
use App\Models\Acts\ReportAct;
use Illuminate\Database\Eloquent\Builder;

class CreateActPayments{

    public function handle(Builder $payments, Closure $next){

        $act_sk_create = 1;

        if(request('event_id') != $act_sk_create){
            return $next($payments);
        }


        $report_act = ReportAct::create([
            'type_id' => 1, // Контракт
            'title' => request()->get('report_name'),
            'bso_supplier_id' => request()->route('supplier_id'),
            'report_year' => request()->get('report_year'),
            'report_month' => request()->get('report_month'),
            'create_user_id' => auth()->id(),
            'report_date_start' => date('Y-m-d H:i:s', strtotime(request()->get('report_date_start'))),
            'report_date_end' => date('Y-m-d H:i:s', strtotime(request()->get('report_date_end'))),
        ]);


        $payments->update([
            'acts_sk_id' => $report_act->id,
        ]);

        foreach($payments->get() as $payment){
            $payment->setBsoLogToPayment(9);
        }

    }

}