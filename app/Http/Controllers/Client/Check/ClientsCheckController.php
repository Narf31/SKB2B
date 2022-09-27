<?php

namespace App\Http\Controllers\Client\Check;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Payments;
use App\Models\Finance\Invoice;
use App\Services\DaData\DaDataGetInfo;
use App\Services\Integration\VernaControllers\VernaPayment;
use App\Services\PaymentAgent\IntegrationPaymentAgent;
use Illuminate\Http\Request;

class ClientsCheckController extends Controller
{


    public function __construct()
    {

    }

    public function payment($contract_id, $payment_id, Request $request)
    {
        $result = false;
        $success = $request->success;
        $error = '';
        if(isset($request->bankActionDescr)){
            $error = $request->bankActionDescr;
        }

        $payment = Payments::find((int)$payment_id);
        $invoice = $payment->invoice;

        if($invoice && $invoice->status_id == 1){

            if($payment->payment_method->key_type == 4 && $payment->payment_method->control_type == 1)
            {
                $result = IntegrationPaymentAgent::checkBillingPage($payment->invoice);
            }

        }elseif($invoice->status_id == 2){
            $result = true;
        }



        return view('client.check.payments', [
            'result' => $result,
            'invoice' => $invoice,
            'error' => $error,
        ]);

    }

    public function clientPayment(Request $request)
    {
        if(isset($request->orderId)){
            $orderId = $request->orderId;

            $result = false;
            $error = '';
            $invoice = Invoice::where('payment_linck_id', $orderId)->get()->first();
            if($invoice){
                $result = IntegrationPaymentAgent::checkBillingPage($invoice);
            }


            return view('client.check.payments', [
                'result' => $result,
                'invoice' => $invoice,
                'error' => $error,
            ]);

        }

        return abort(303);

    }




}
