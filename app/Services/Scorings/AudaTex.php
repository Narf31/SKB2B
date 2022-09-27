<?php

namespace App\Services\Scorings;

use Mockery\Exception;

class AudaTex
{

    public $STATE = false;
    public $URL = "https://www.audatex.ru/b2b/services/";
    private $loginId = "";
    private $password = "";


    public function __construct()
    {
        $this->loginId = env('AudaTex_loginId', '');
        $this->password = env('AudaTex_password', '');

        if(strlen($this->loginId) > 0 && strlen($this->password)){
            $this->STATE = true;
        }
    }

    public function getDataInfo($query, $type = 'vin')
    {
        $client = new \SoapClient("{$this->URL}AudaHistoryService_v1?wsdl", array('trace' => 1, 'exception' => 1));

        $arr = array(
            'parameter' =>
                array (
                    array('name' => 'loginId', 'value' => $this->loginId),
                    array('name' => 'password', 'value' => $this->password),
                    array('name' => $type, 'value' => $query )
                )
        );

        try
        {
            $response = $client->getHistory($arr);
        }
        catch(\SoapFault $e)
        {

            return null;
        }

        $xml = new \SimpleXMLElement($response->payload);
        if(count($xml) == 0){
            return null;
        }

        return $xml;

    }

}