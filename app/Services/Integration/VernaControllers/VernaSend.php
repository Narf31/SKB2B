<?php

namespace App\Services\Integration\VernaControllers;

use App\Models\Settings\SettingsSystem;
use Mockery\Exception;

class VernaSend
{

    public function __construct()
    {
        $this->url = SettingsSystem::getDataParam('verna', 'url');
        $this->login = SettingsSystem::getDataParam('verna', 'login');
        $this->password = SettingsSystem::getDataParam('verna', 'password');
    }



    public function send($method, $data, $type = 'POST', $is_link = 0)
    {
        $curl = curl_init();
        $headers = [];

        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Authorization: Bearer '.$this->password;

        $json = '';
        if($data){
            $json = 'params='.(string)\GuzzleHttp\json_encode($data, JSON_UNESCAPED_UNICODE);
        }


        if($type == 'POST'){
            $url = $this->url.$method;
        }else {
            if($data){
                $url = $this->url.$method."?".http_build_query($data);
            }else{
                $url = $this->url.$method;
            }
            $json = null;
        }

        if($is_link == 1){
            $url = $method;
        }



        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

            //dd($err);

            //Логируем ошибки
            return null;
        }


        if($is_link == 1){
            return $response;
        }

        if($response && strlen($response) > 0){
            return \GuzzleHttp\json_decode($response);
        }

        return null;
    }




}