<?php

namespace App\Services\PaymentAgent\SmartFin;



class SmartFinConnect
{

    public $URL = 'https://api-ecom.2can.ru/';
    public $page_link = "https://checkout-ecom.2can.ru/pay";
    public $inn = '7604305400';
    public $userName = 'testproject';
    public $password = 'No2tXpmNwBBRmjf6EObvGR6P';


    public $paymentLinck = '';

    public function __construct($userName, $password)
    {
        $this->userName = $userName;
        $this->password = $password;

        if(app()->environment() == 'local') {
            $this->inn = '3206003459';
            $this->URL = 'https://api.ecom-sandbox.2can.ru/';
            $this->page_link = 'https://checkout.ecom-sandbox.2can.ru:443/pay';
        }

    }


    private function send($data = null, $fun, $metod){


        $curl = curl_init();

        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode($this->userName . ":" . $this->password)
        );


        if($data){
            $data = \GuzzleHttp\json_encode($data);
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
            CURLOPT_HEADER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            /*
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
            */
        ));


        $response = curl_exec($curl);
        $err = curl_error($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);




        curl_close($curl);


        if ($err) {




        }

        if($metod == "POST"){
            $resp_headers = [];
            $rtn=explode("\r\n", $header);
            foreach ($rtn as $v)
            {
                $v=explode(': ', $v, 2);
                if(isset($v) && is_array($v) && isset($v[1])){
                    $resp_headers[$v[0]]=$v[1];
                }

            }

            if(isset($resp_headers['Location'])){
                $this->paymentLinck = $resp_headers['Location'];
            }

        }


        return \GuzzleHttp\json_decode($body);

    }



    public function test()
    {
        return $this->send(null, 'ping', 'GET');

    }


    public function createOrder($invoice)
    {


        $Amount = getFloatFormat((string)$invoice->invoice_payment_total);
        $payment = $invoice->payments->first();
        $contract = $payment->contract;


        $email = $payment->send_email;
        $Product = '';
        $ReturnUrl = url("/clients/check/payment");
        $terminal = '';


        //$end_date = date('Y-m-d H:i:s', strtotime($contract->end_date));
        //$create_date = date('Y-m-d H:i:s', strtotime($contract->sign_date));
        $expiration_timeout = '100m';

        $insurer = $contract->insurer;

        $orderDescription = "contract: {$contract->bso_title}; holder: {$insurer->title};";


        $data = new \stdClass();
        $data->amount = $Amount;
        $data->currency = 'RUB';
        $data->merchant_order_id = $invoice->id;
        //$data->segment = 987654321;
        $data->client = new \stdClass();
        //$data->client->address = '';
        //$data->client->city = '';
        //$data->client->country = '';
        //$data->client->login = '';
        $data->client->email = $email;
        $data->client->name = (string)$insurer->title;
        //$data->client->phone = '';
        //$data->client->state = '';
        //$data->client->zip = '';


        //$data->custom_fields = '';
        $data->description = $orderDescription;
        //$data->location = new \stdClass();
        //$data->location->ip = '';
        $data->options = new \stdClass();
        $data->options->expiration_timeout = $expiration_timeout;//Время сессии
        //$data->options->force3d = '0';
        $data->options->language = 'ru';
        $data->options->return_url = $ReturnUrl;
        //$data->options->template = '12';
        //$data->options->mobile = '1';
        $data->options->auto_charge = '1';
        $data->options->terminal = $terminal;

        /*
        $data->extra_fields = new \stdClass();
        $data->extra_fields->electronic_receipt = new \stdClass();
        $data->extra_fields->electronic_receipt->email = $email;
        $data->extra_fields->electronic_receipt->items = [
            ['name' => "Страховой взнос по договору ".(string)$contract->bso_title,
                'amount' => $Amount,
                'price' => $Amount,
                'quantity' =>  '1.0',
                'type' => '0',
                'tax_type' => 'none',
                'payment_object' => "service"
            ]
        ];

        $data->extra_fields->electronic_receipt->items[0]->name = ;
        $data->extra_fields->electronic_receipt->items[0]->amount = $Amount;
        $data->extra_fields->electronic_receipt->items[0]->price = $Amount;
        $data->extra_fields->electronic_receipt->items[0]->quantity = 1.0;
        $data->extra_fields->electronic_receipt->items[0]->type = 0;
        $data->extra_fields->electronic_receipt->items[0]->tax_type = 0;
        $data->extra_fields->electronic_receipt->items[0]->payment_object = "service";


        $data->extra_fields->electronic_receipt->payment_address = 'https://riks-ins.ru/';
        $data->extra_fields->electronic_receipt->taxation_type = "0";



        $customer = "{$insurer->title} {$insurer->doc_serie} {$insurer->doc_number}";

        $data->extra_fields->electronic_receipt->total_amount = $Amount;
        $data->extra_fields->electronic_receipt->inn = $this->inn;
        $data->extra_fields->electronic_receipt->phone = '';
        $data->extra_fields->electronic_receipt->customer = $customer;
        */


        //dump($data);

        $result = $this->send($data, 'orders/create', 'POST');



        //dump($result);

        if(isset($result->orders[0]->id)){

            $invoice->payment_linck_id = $result->orders[0]->id;
            $invoice->payment_linck = $this->paymentLinck;
            $invoice->save();
            //dd($result);

            return $this->paymentLinck;
        }

        return false;
    }


    public function checkPaymentPage($invoice, $is_deb = 0)
    {
        $res = new \stdClass();
        $res->status = false;

        $result = $this->send(null, "orders/{$invoice->payment_linck_id}", 'GET');
        if($is_deb == 1){
            dd($result);
        }

        if(isset($result->orders[0]->id)) {

            if ($result->orders[0]->status == 'charged') {
                //ОПЛАТА ЗАКАЗА
                $res->status = true;
                $res->amount = getFloatFormat($result->orders[0]->amount_charged);
                $res->datetime = setDateTimeFormat($result->orders[0]->updated);

            }
        }

        return $res;
    }


}