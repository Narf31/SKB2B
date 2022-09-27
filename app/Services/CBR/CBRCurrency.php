<?php

namespace App\Services\CBR;

use App\Models\Settings\Currency;
use App\Models\Settings\CurrencyValue;
use App\Models\Settings\SettingsSystem;
use Exception;
use GuzzleHttp\Client;

class CBRCurrency
{



    public function __construct()
    {

    }

    public static function updateValue()
    {
        $response = file_get_contents("http://www.cbr.ru/scripts/XML_daily.asp");
        if($response){


            try {

                $xml = new \SimpleXMLElement($response);

                $data = date("Y-m-d");

                /*

                $data = null;
                if($xml->attributes()['Date']){
                    $data = getDateFormatEn((string)$xml->attributes()['Date']);
                    if($data < date("Y-m-d")){
                        $data = date("Y-m-d");
                    }
                }
                */

                foreach ($xml as $currency){
                    self::setValue($data, (string)$currency->NumCode, getFloatFormat((string)$currency->Value));
                }

                self::setValue($data, 643, getFloatFormat(1));


            } catch (\Exception $e) {

            }
        }




        return true;

    }


    public static function setValue($data, $code, $amount)
    {
        $currency = Currency::where("code", $code)->get()->first();
        if($currency){
            return CurrencyValue::updateValue($currency, $data, $amount);
        }

        return false;
    }

}