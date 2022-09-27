<?php

namespace App\Services\PaymentAgent\SmartFin;



class IntegrationClass
{


    //Создаем платежную страницу
    public static function createBillingPage($invoice)
    {

        $ibox = new SmartFinConnect($invoice->org->api_key, $invoice->org->secret_key);
        return $ibox->createOrder($invoice);
    }


    public static function checkBillingPage($invoice)
    {

        $ibox = new SmartFinConnect($invoice->org->api_key, $invoice->org->secret_key);
        return $ibox->checkPaymentPage($invoice);
    }


    public static function createOfdReceipt($invoice)
    {


        return false;
    }

}