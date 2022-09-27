<?php

namespace App\Services\PaymentAgent\AlfaBank;



class AlfaBankConnect
{

    public $URL = 'https://web.rbsuat.com/ab/rest/';//TEST
    //public $URL = 'https://pay.alfabank.ru/payment/rest/';
    public $userName = '';
    public $password = '';


    public $paymentLinck = '';

    public function __construct($userName, $password)
    {
        $this->userName = $userName;
        $this->password = $password;

    }


    private function send($data = null, $fun, $metod){


        $curl = curl_init();
        $headers = [];


        if($data){
            $data = http_build_query($data);
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL.$fun,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $metod,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

        }

        return \GuzzleHttp\json_decode($response);

    }



    public function test()
    {
        return $this->send(null, 'ping', 'GET');

    }


    public function createOrder($invoice)
    {

        $Amount = getFloatFormat((string)$invoice->invoice_payment_total)*100;
        $payment = $invoice->payments->first();
        $contract = $payment->contract;

        $email = $payment->send_email;
        $ReturnUrl = url("/clients/check/payment");
        $terminal = '';

        $insurer = $contract->insurer;

        $orderDescription = "{$contract->product->title}: {$contract->bso_title}";

        $expirationDate = date('Y-m-d\T23:59:59', strtotime($payment->payment_data));
        if(getDateFormatEn($payment->payment_data) <= getDateTime()){
            $expirationDate = date('Y-m-d\T23:59:59');
        }


        $data = new \stdClass();
        $data->amount = $Amount;
        $data->expirationDate = $expirationDate;
        $data->currency = '810';
        $data->language = 'ru';
        $data->orderNumber = $invoice->id;//$invoice->id.'-'.$invoice->md5_token;

        $data->userName = $this->userName;
        $data->password = $this->password;//urlencode($this->password);
        $data->description = $orderDescription;
        $data->returnUrl = $ReturnUrl;
        $data->failUrl = $ReturnUrl;

        $result = $this->send($data, 'register.do', 'POST');

        if(isset($result->orderId)){
            $invoice->payment_linck_id = $result->orderId;
            $invoice->payment_linck = $result->formUrl;
            $invoice->save();
            return $result->formUrl;
        }


        return false;
    }


    public function checkPaymentPage($invoice, $is_deb = 0)
    {
        $res = new \stdClass();
        $res->status = false;

        $data = new \stdClass();
        $data->language = 'ru';
        $data->orderId = $invoice->payment_linck_id;
        $data->userName = $this->userName;
        $data->password = $this->password;

        $result = $this->send($data, 'getOrderStatus.do', 'POST');
        if($is_deb == 1){
            dd($result);
        }

        if(isset($result->OrderStatus)) {

            if ($result->OrderStatus == 2) {
                //ОПЛАТА ЗАКАЗА
                $res->status = true;
                $res->amount = getFloatFormat(getFloatFormat($result->Amount)/100);
                $res->datetime = getDateTime();

            }
        }

        return $res;
    }


}