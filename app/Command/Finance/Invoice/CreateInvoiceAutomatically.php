<?php
namespace App\Command\Finance\Invoice;
use App\Models\Finance\Invoice;
use Closure;


class CreateInvoiceAutomatically
{
    public function handle($data, Closure $next)
    {
        if(request()->get('create_type') != 1){
            return $next($data);
        }


        $payments_data = [];
        foreach($data['payments'] as $payment){
            $payments_data[$payment->bso->supplier_org->id]['org'] = $payment->bso->supplier_org;
            if(!isset($payments_data[$payment->bso->supplier_org->id]['payments_sum'])){
                $payments_data[$payment->bso->supplier_org->id]['payments_sum'] = 0;
            }
            $payments_data[$payment->bso->supplier_org->id]['payments_sum'] += $payment->payment_total;
        }

        //Наиболее дорогой счёт вверх
        uasort($payments_data, function($a,$b){
            return $a['payments_sum'] < $b['payments_sum'];
        });
        //первая организация, потому что у неё самй дорогой счёт
        $org = current($payments_data)['org'];


        $invoice = new Invoice();
        $invoice->user_id = auth()->user()->id;
        $invoice->create_type = 1;
        $invoice->status_id = 1;
        $invoice->org_id = $org->id;
        $invoice->type = $data['payment_types']->first();
        $invoice->type_invoice_payment_id = $data['payments']->first()->type_to_invoice();
        $invoice->agent_id = $data['payments']->first()->agent_id;
        $invoice->save();



        foreach($data['payments'] as $payment){
            $payment->invoice_id = $invoice->id;
            $payment->invoice_payment_total = $payment->payment_total;
            $payment->invoice_payment_date = date('Y-m-d H:i:s');
            $payment->save();
        }


    }

}