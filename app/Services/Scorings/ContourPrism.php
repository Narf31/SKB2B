<?php

namespace App\Services\Scorings;

use Mockery\Exception;

class ContourPrism
{
    private $URL = 'https://kyc.kontur.ru/api';
    private $URL_AUTH = 'https://api.kontur.ru';

    private $apiKeyValue = '';
    private $bankId = '';
    private $Sid = '';
    private $RefreshToken = '';


    public function __construct()
    {

        $this->apiKeyValue = env('ContourPrism_apiKeyValue');
        $this->bankId = env('ContourPrism_bankId');
        $this->getSid();
    }

    private function send($url, $data = null, $method = 'GET')
    {
        $headers = [];

        if(strlen($this->apiKeyValue) < 3){
            return null;
        }


        if(strlen($this->Sid) > 0){
            $send_url = "{$this->URL}$url";

            $url_param = "auth.sid={$this->Sid}&api-key={$this->apiKeyValue}";
            if(strripos($send_url, '?') === false){
                $send_url = "{$send_url}?{$url_param}";
            }else{
                $send_url = "{$send_url}&{$url_param}";

            }

        }else{
            $send_url = "{$this->URL_AUTH}$url";
        }


        //dump($send_url);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $send_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $res = $response;
        try{
            $res = \GuzzleHttp\json_decode($response);
        }catch (\Exception $e){

            //dd($response);

        }


        return $res;
    }

    private function getSid()
    {
        $url = '/auth/v5.11/authenticate-by-pass?login='.env('ContourPrism_login');
        $result = $this->send($url, env('ContourPrism_pass'), 'POST');
        $this->Sid = $result->Sid;
        $this->RefreshToken = $result->RefreshToken;
    }

    public function getLimits()
    {
        $url = "/banks/{$this->bankId}/api-keys/{$this->apiKeyValue}";
        return $this->send($url, null, 'GET');
    }

    public function getUser()
    {
        $url = "/auth/user";
        return $this->send($url, null, 'GET');
    }

    public function getCompanies($inn = '', $ogrn = '')
    {
        $url = "/banks/{$this->bankId}/companies?inn={$inn}&ogrn={$ogrn}";
        return $this->send($url, null, 'GET');
    }

    public function getIndividuals($fio, $dateOfBirth = '')
    {
        $fio = urlencode($fio);//"Иванов Иван Иванович"
        $dateOfBirth = urlencode($dateOfBirth);//1972-09-19

        $url = "/banks/{$this->bankId}/individuals?fio={$fio}&dateOfBirth=$dateOfBirth";
        return $this->send($url, null, 'GET');
    }

    public function getIndividualPassport($number)
    {

        $number = urlencode($number);//"4617 820766"

        $url = "/banks/{$this->bankId}/individuals/passport?number={$number}&bankId={$this->bankId}";

        return $this->send($url, null, 'GET');
    }


    public function getGlobalSanctions($name)
    {

        $name = urlencode($name);//"4617 820766"
        $url = "/banks/{$this->bankId}/companies/profiles?search={$name}&bankId={$this->bankId}";

        return $this->send($url, null, 'GET');
    }




    public function test()
    {

        //$result = $this->getCompanies('332711968985', '1167746494442');
        $result = $this->getIndividuals("Иванов Иван Иванович", '1972-09-19');
        //$result = $this->getIndividualPassport("4617 820766");


        dd($result);

        /*
        https://api.kontur.ru/banks/831d2fe9-bde6-42cb-8875-4955326080ce/api-keys/93c1e5b3-6e01-40d6-ad7b-de96dde99276?auth.sid=340051250B6D284F92573974350C7EC153959F5375796544921ACCABD49FF4BF&api-key=93c1e5b3-6e01-40d6-ad7b-de96dde99276

        https://kyc.kontur.ru/api/banks/831d2fe9-bde6-42cb-8875-4955326080ce/api-keys/93c1e5b3-6e01-40d6-ad7b-de96dde99276?auth.sid=340051250B6D284F92573974350C7EC1B770B51F1503BC4A87B3B5B6B7AB7D1E&api-key=93c1e5b3-6e01-40d6-ad7b-de96dde99276

        */

    }

}