<?php

namespace App\Services\Scorings;

use Mockery\Exception;

class OdysseySearch
{

    public $URL = "https://api.odyssey-search.info/";
    private $loginId = "8eb106dd54d772a7b6de40bcc04858d7";


    public function __construct()
    {


    }


    private function send($data, $fun, $metod = 'POST', $get_data = ''){


        $curl = curl_init();

        $headers = [];
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $headers[] = 'Accept: application/json';

        if(strlen($this->token) > 5){
            $headers[] = 'Authorization: AR-REST '.$this->token;

        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->URL.$fun.$get_data,
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

}