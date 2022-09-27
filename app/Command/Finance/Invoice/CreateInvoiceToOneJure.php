<?php
namespace App\Command\Finance\Invoice;
use App\Models\Finance\Invoice;
use App\Models\Organizations\Organization;
use Closure;

class CreateInvoiceToOneJure
{

    public function handle($data, Closure $next)
    {
        if(request()->get('create_type') != 2){
            return $next($data);
        }

        $org = Organization::findOrFail(request()->get('org_id'));

        $invoice = new Invoice();
        $invoice->user_id = auth()->user()->id;
        $invoice->create_type = 1;
        $invoice->status_id = 1;
        $invoice->org_id = $org->id;
        $invoice->type = $data['payment_types']->first();
        $invoice->type_invoice_payment_id = $data['payments']->first()->type_to_invoice();
        $invoice->agent_id = $data['payments']->first()->agent_id;;
        $invoice->save();

        foreach($data['payments'] as $payment){
            $payment->invoice_id = $invoice->id;
            $payment->invoice_payment_total = $payment->payment_total;
            $payment->invoice_payment_date = date('Y-m-d H:i:s');
            $payment->save();
        }

        return $invoice;

    }
}