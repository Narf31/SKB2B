<?php

namespace App\Services\Backoffice\Auth;

use App\Models\Settings\SettingsSystem;
use App\Services\Backoffice\Send\SendAPI;
use Exception;
use GuzzleHttp\Client;

class Authentication
{

    public function __construct()
    {


    }


    public static function getAuth()
    {
        SendAPI::send('Auth', ["User"=>"Test","Psw"=>"TestPsw"]);

        SendAPI::response(["UserId"=>"121212","SessionId"=>uniqid()]);

    }


}