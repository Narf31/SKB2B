<?php

namespace App\Services\DaData;

use App\Models\Settings\SettingsSystem;
use Exception;
use GuzzleHttp\Client;

class DaDataGetInfo
{

    protected $url = '';
    protected $API_KEY = '';

    public function __construct()
    {
        $this->url = SettingsSystem::getDataParam('dadata', 'url');
        $this->API_KEY = SettingsSystem::getDataParam('dadata', 'token_api');
    }


    public function send($metod, $arr){
        $curl = curl_init();


        $headers = [];
        $headers[] = 'Authorization: Token '. $this->API_KEY ;
        $headers[] = 'Content-type: application/json; charset=utf-8';
        $headers[] = 'Accept: application/json';

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.$metod,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => \GuzzleHttp\json_encode($arr),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null;
        }

        return json_decode($response);

    }

    public function getAddress($address, $limit = 1)
    {
        $arr = [];
        $arr['query'] = $address;
        $arr['count'] = $limit;

        return $this->send('address', $arr);
    }

    public function getFIO($fio, $limit = 1)
    {
        $arr = [];
        $arr['query'] = $fio;
        $arr['count'] = $limit;

        return $this->send('fio', $arr);
    }

    public function getParty($party, $limit = 10)
    {
        $arr = [];
        $arr['query'] = $party;
        $arr['count'] = $limit;

        return $this->send('party', $arr);
    }

}