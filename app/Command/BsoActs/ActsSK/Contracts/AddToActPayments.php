<?php

namespace App\Command\BsoActs\ActsSK\Contracts;

use Closure;
use App\Models\Directories\BsoSuppliers;
use Illuminate\Database\Eloquent\Builder;

class AddToActPayments{

    public function handle(Builder $payments, Closure $next){

        $acts_sk_add = 2;

        if(request('event_id') != $acts_sk_add){
            return $next($payments);
        }

        $supplier = BsoSuppliers::findOrFail((int)request()->route('supplier_id'));
        $report_act = $supplier->reports_acts()->where('id', (int)request()->get('to_act_sk_id'))->firstOrFail();

        $payments->update([
            'acts_sk_id' => $report_act->id,
        ]);

        foreach($payments->get() as $payment){
            $payment->setBsoLogToPayment(9);
        }


    }

}