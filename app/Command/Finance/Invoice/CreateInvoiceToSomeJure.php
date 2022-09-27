<?php
namespace App\Command\Finance\Invoice;
use App\Models\Finance\Invoice;
use Closure;
use Illuminate\Http\Request;

class CreateInvoiceToSomeJure
{
    public function handle($data, Closure $next)
    {
        $request = request();
        if($request->get('create_type') != 3){
            return $next($data);
        }

        $payments_data  = [];
        foreach($data['payments'] as $payment){
            $payments_data[$payment->bso->supplier_org->id]['org'] = $payment->bso->supplier_org;
            $payments_data[$payment->bso->supplier_org->id]['payments'][] = $payment;
        }

        foreach($payments_data as $org_id => $payment_data){

            $invoice = new Invoice();
            $invoice->user_id = auth()->user()->id;
            $invoice->create_type = 3;
            $invoice->status_id = 1;
            $invoice->org_id = $org_id;
            $invoice->type = $data['payment_types']->first();
            $invoice->type_invoice_payment_id = $data['payments']->first()->type_to_invoice();
            $invoice->agent_id = $data['payments']->first()->agent_id;;
            $invoice->save();

            foreach($payment_data['payments'] as $payment){
                $payment->invoice_id = $invoice->id;
                $payment->invoice_payment_total = $payment->payment_total;
                $payment->invoice_payment_date = date('Y-m-d H:i:s');
                $payment->save();
            }
        }

        return false;
    }
}