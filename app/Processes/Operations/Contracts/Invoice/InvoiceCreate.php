<?php

namespace App\Processes\Operations\Contracts\Invoice;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Finance\Invoice;
use App\Processes\Operations\Contracts\Contract\ContractMasks;
use Illuminate\Support\Str;

class InvoiceCreate{



    public static function create($payment)
    {
        if((int)$payment->invoice_id > 0){
            if($payment->invoice->status_id == 1){
                $payment->invoice->delete();
            }
        }


        $type = Invoice::searchType($payment->payment_type, $payment->payment_flow);

        $client_type = 0;
        $ClientInfo = '';
        $email = 'noreply@riks-ins.ru';
        if(strlen($payment->send_email) > 0){
            $email = $payment->send_email;
        }else{
            if($payment->contract->insurer){
                if(strlen($payment->contract->insurer->email) > 0){
                    $email = $payment->contract->insurer->email;
                }
            }
        }

        if($payment->contract->insurer){
            $ClientInfo = $payment->contract->insurer->title;

            if($payment->contract->insurer->type == 0){
                $client_type = 0;
                $ClientInfo .= " {$payment->contract->insurer->doc_serie} {$payment->contract->insurer->doc_number}";
            }else{
                $client_type = 1;
                $ClientInfo .= " {$payment->contract->insurer->inn}";
            }

        }

        $org_id = 1;
        if($payment->contract->bso){
            $org_id = $payment->contract->bso->org_id;
        }

        $invoice = Invoice::create([
            'user_id' => $payment->agent_id,
            'status_id' => 1,
            'type' => $type->TYPES,
            'create_type' => 1,
            'org_id' => $org_id,
            'agent_id' => $payment->agent_id,
            'type_invoice_payment_id' => $type->TYPE_INVOICE_PAYMENT,
            'invoice_payment_total' => $payment->invoice_payment_total,
            'invoice_payment_date' => getDateTime(),
            'payment_method_id' => $payment->payment_method_id,
            'md5_token' => $payment->contract->md5_token,
            'client_email' => $email,
            'client_info' => $ClientInfo,
            'client_type' => $client_type,
        ]);

        $payment->invoice_id = $invoice->id;
        $payment->save();


        ContractMasks::invoices($invoice);

        return InvoiceAutomatic::automatic($invoice);
    }

}