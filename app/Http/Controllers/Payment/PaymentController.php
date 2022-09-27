<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\BSO\BsoItem;
use App\Models\Contracts\Payments;
use App\Models\Finance\Invoice;
use App\Models\Security\Security;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function __construct()
    {
        //$this->middleware('permissions:security, security_inquiry');
    }


    public function add_payment($bso_id, Request $request)
    {
        $bso = BsoItem::getBsoId($bso_id);

        $payment = new Payments();
        $payment->type_id = $request->transaction_type_id;
        $payment->bso_id = $bso->id;
        $payment->agent_id = $bso->agent_id;
        $payment->contract_id = $bso->contract_id;

        if($payment->type_id == 0){
            $payment->payment_number = count($bso->payments)+1;

        }

        if($payment->type_id == 2){
            $payment->set_balance = 1;
        }

        return $this->view($payment);
    }

    public function index($id)
    {
        $payment = Payments::find($id);
        return $this->view($payment);
    }

    public function view(Payments $payment)
    {
        $view = 'payment';
        if($payment->type_id == 0) $view = 'payment';
        if($payment->type_id == 1) $view = 'debt';
        if($payment->type_id == 2) $view = 'premium';

        $agents = User::getALLUser(24)->pluck('name', 'id');

        return view("payments.payment.$view", [
            'payment' => $payment,
            'agents' => $agents,
        ]);
    }


    public function save($id, Request $request)
    {

        $data = (object)$request->get('payment')?:[];

        if((int)$id == 0){
            $payment = new Payments();
            $payment->type_id = $data->type_id;
            $payment->bso_id = $data->bso_id;

            $bso = BsoItem::getBsoId($data->bso_id);
            $payment->contract_id = $bso->contract_id;
            $payment->org_id = $bso->supplier_org->id;

        }else{
            $payment = Payments::find($id);
        }

        $payment->savePaymentData($data);
        return parentReload();
    }


    public function delete($id)
    {
        $payment = Payments::find($id);
        return response($payment->deletePayment());
    }

    public function detach_receipt($id){
        $payment = Payments::findOrFail($id);
        $payment->detachReceipt();
        return response()->json(['status' => 'ok']);
    }



}
