<?php

namespace App\Http\Controllers\Client\Сontracts\Online;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Contracts;
use App\Models\Directories\Products;
use App\Models\Settings\PaymentMethods;
use App\Models\Settings\SettingsSystem;
use App\Processes\Operations\Contracts\Contract\ContractAccept;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Scenaries\Contracts\OnlineContractSave;
use Illuminate\Http\Request;

class OnlineClientController extends Controller
{

    public function __construct()
    {
        $this->breadcrumbs[] = [
            'label' => 'Оформление договора',
            'url' => urlClient('contracts/', 0),
        ];
    }

    public function create($product_id, Request $request)
    {

        $product = Products::where('is_actual', '1')
            ->where('is_online', 1)
            ->where('id', $product_id)
            ->first();


        if (!$product) {
            return redirect(urlClient('/contracts/online/'));
        }

        $agent_id = SettingsSystem::getDataParam('client', 'user_id');
        $contract = ContractCreate::create($product, null, $agent_id);
        return redirect(urlClient("/contracts/online/{$contract->md5_token}"));

    }

    public function edit($md5_token)
    {

        $contract = Contracts::getContractToken($md5_token);
        $this->breadcrumbs[] = [
            'label' => $contract->product->title,
        ];

        $product_info = $contract->product->get_products_info(1);

        return view('client.contracts.online.edit', [
            'contract' => $contract,
            'product_info' => $product_info,
            'type' => $contract->getViewStateOnline(),
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function save($md5_token, Request $request)
    {

        $contract = Contracts::getContractToken($md5_token);
        $data = (object)$request->contract;

        return OnlineContractSave::handle($contract, $data);
    }


    public function calc($md5_token, Request $request)
    {
        $contract = Contracts::getContractToken($md5_token);
        $result = (object)OnlineContractSave::calc($contract);
        $calc = $contract->getPaymentsFirstNotInvoice();
        $result->payment_total = titleFloatFormat($calc->payment_total).' руб.';
        return response()->json($result);
    }

    public function release($md5_token, Request $request)
    {
        $contract = Contracts::getContractToken($md5_token);
        $data = (object)$request->contract;
        OnlineContractSave::handle($contract, $data);

        $accept_data = new \stdClass();
        $accept_data->bso_id = 0;
        $accept_data->bso_supplier_id = $contract->bso_supplier_id;
        $accept_data->bso_is_epolicy = 1;
        $accept_data->agent_id = $contract->agent_id;
        $accept_data->promocode = $data->payment['promocode'];
        $method = PaymentMethods::where('key_type', (int)$request->payment_type)->get()->first();
        $accept_data->payment_type = $method->id;
        $accept_data->payment_type_send_checkbox = 0;
        $accept_data->payment_send_checkbox = $contract->insurer->email;
        $accept_data->send_email = $contract->insurer->email;

        $result = ContractAccept::accept($contract, $accept_data);
        $result->payment_type = (int)$request->payment_type;

        return response()->json($result);
    }



    public function payment_info($md5_token)
    {

        $contract = Contracts::getContractToken($md5_token);
        $this->breadcrumbs[] = [
            'label' => $contract->product->title,
        ];

        $payment = $contract->getPaymentsFirstInvoiceLink();

        $json = [];

        if($payment){

            $invoice = $payment->invoice;
            $amount = $invoice->invoice_payment_total * 100;

            $success_redirect = urlClient("/contracts/online/payment-check/{$md5_token}");
            $fail_redirect = urlClient("/contracts/online/{$md5_token}");
            $signature = hash('sha256', $invoice->org->api_key.$invoice->md5_token.$amount.$invoice->org->secret_key);

            $json = [
                'parent_id' => 'iframe_parent',
                'api_key' => $invoice->org->api_key,
                'tx_id' => $invoice->md5_token,
                'description' => "Оплата счета {$invoice->id}",
                'amount' => $amount,
                'signature' => $signature,
                'success_redirect' => $success_redirect,
                'fail_redirect' => $fail_redirect,
                'auto_return' => 1,
                'rebill' => [],
                'extra' => [
                    'some_key' => $invoice->id
                ],
                'phone' => '',
                'email' => '',
                'send_post_message' => 'false',
                'version' => '2.0.0',
            ];
        }


        return response()->json($json);

    }


    public function payment_link($md5_token)
    {
        $contract = Contracts::getContractToken($md5_token);
        $this->breadcrumbs[] = [
            'label' => $contract->product->title,
        ];

        $payment = $contract->getPaymentsFirstInvoiceLink();
        if(!$payment){
            return redirect(urlClient("/contracts/online/{$contract->md5_token}"));
        }

        return view('client.contracts.online.payment_link', [
            'contract' => $contract,
            'payment' => $payment,
        ])->with('breadcrumbs', $this->breadcrumbs);

    }




}
