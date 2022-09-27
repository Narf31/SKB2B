<?php

namespace App\Services\Backoffice\Send;

use App\Models\Settings\SettingsSystem;
use Exception;
use GuzzleHttp\Client;

class SendAPI
{

    public function __construct()
    {


    }


    public static function send($metod, $object)
    {
        $body = self::request($object);
        dump($body);

    }

    public static function response($object)
    {
        $result = new \stdClass();
        $result->Backoffice = new \stdClass();
        $result->Backoffice->Response = new \stdClass();
        $result->Backoffice->Response = $object;

        $result->Backoffice->Error = new \stdClass();
        $result->Backoffice->Error->Code = 0;
        $result->Backoffice->Error->Text = '';

        $response = \GuzzleHttp\json_encode($result);

        dd($response);

        return $response;
    }

    private static function request($object)
    {
        return \GuzzleHttp\json_encode($object);
    }


}