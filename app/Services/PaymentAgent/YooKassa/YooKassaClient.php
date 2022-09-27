<?php

namespace App\Services\PaymentAgent\YooKassa;



class YooKassaClient
{

    private $url = 'https://api.yookassa.ru/v3/';
    private $shopId = '903042';
    private $secretKey = 'test_yTZBPikyKZ_rfzgWvOoRjeuHFxg9oaN6ohOa1tN79Ko';


    public function __construct() {



    }

    private function send($data = null, $fun, $metod = 'POST'){
        $curl = curl_init();
        $headers = [];


        $headers = [];
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $headers[] = "Authorization: Basic ".base64_encode("{$this->shopId}:{$this->secretKey}");
        $headers[] = "Idempotence-Key: ".UUID_V4();

        if($data){
            $data = \GuzzleHttp\json_encode($data);
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.$fun,
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

    public function createReceipt($data){

        return $this->send($data, 'receipts');

    }


    public function createPayments($data){

        return $this->send($data, 'payments');

    }


}