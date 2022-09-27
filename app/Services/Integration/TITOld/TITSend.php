<?php

namespace App\Services\Integration\TITOld;

use App\Models\Settings\SettingsSystem;
use Mockery\Exception;

class TITSend
{




    public static function send($select)
    {
        $curl = curl_init();
        $headers = [];

        $url = "https://b2b.sst.cat/cabinet/pageforadmin/";


        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "auth=no123need456auth_QSC&bd=1&select={$select}",
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {

            dd($err);

            //Логируем ошибки
            //return null;
        }




        if($response && strlen($response) > 0){
            return \GuzzleHttp\json_decode($response);
        }

        return null;
    }




}