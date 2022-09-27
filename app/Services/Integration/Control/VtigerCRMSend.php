<?php

namespace App\Services\Integration\Control;

use Mockery\Exception;

class VtigerCRMSend
{


    public static function getDataInfo($method, $param)
    {

        $url = "https://connect.riks-ins.ru/vtigercrm/"."$method.php?$param";


        try {
            $res = file_get_contents($url);
            $res = \GuzzleHttp\json_decode($res);
        } catch (\Exception $e) {
            $res = new \stdClass();
            $res->state = 1;
            $res->msg = "VtigerCRM не настроен!";
        } finally {

        }
        return $res;
    }

}