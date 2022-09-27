<?php

namespace App\Services\PaymentAgent;


use App\Models\Settings\SettingsSystem;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use App\Processes\Operations\Mails\ContractMails;
use App\Processes\Operations\Mails\PaymentMails;
use App\Processes\Scenaries\Contracts\Products\Kasko;
use App\Services\Front\api\KansaltingRIT_V2_1;
use App\Services\Integration\VernaControllers\VernaMask;
use App\Services\Integration\VernaControllers\VernaPayment;
use App\Services\PaymentAgent\KkmServer\KkmServerConnect;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

class IntegrationPaymentAgent
{



    public static function getBillingPage($invoice)//ПЛАТЕЖНАЯ СТРАНИЦА
    {

        $payment_type_agent = IntegrationPaymentAgent::getPaymentAgent($invoice->org->payment_type_agent);
        if($payment_type_agent){
            $link = $payment_type_agent::createBillingPage($invoice);
            //Отправляем ссылку на почту
            $payment = $invoice->payments->first();
            PaymentMails::sendLink($payment, $link);
        }

    }

    public static function checkBillingPage($invoice)//ПЛАТЕЖНАЯ СТРАНИЦА
    {
        $payment_type_agent = IntegrationPaymentAgent::getPaymentAgent($invoice->org->payment_type_agent);

        if($payment_type_agent){

            $result = $payment_type_agent::checkBillingPage($invoice);

            if($result->status == true){
                if($invoice->status_id == 1){
                    InvoiceAutomatic::closeInvoice($invoice, $result->datetime, $result->amount);
                    //События БСО 12 Оплачен BsoLog
                    foreach ($invoice->payments as $payment){
                        $payment->setBsoLogToPayment(12);
                        //ContractMails::sendDocuments($payment->contract, $invoice->client_email);
                    }
                }
                return true;
            }
        }
        return false;
    }


    public static function deleteBillingPage($invoice)//ПЛАТЕЖНАЯ СТРАНИЦА - ОТМЕНА ПЛАТЕЖА
    {

    }

    public static function getOfdReceipt($invoice)//ЧЕК ОФД
    {
        if(app()->environment() != 'local') {
            $kkm = new KkmServerConnect();
            return $kkm->createBillingOFD($invoice);
        }
        return true;
    }


    public static function deleteOfdReceipt($invoice)//ЧЕК ОФД - ОТМЕНА ПЛАТЕЖА
    {
        if(app()->environment() != 'local') {
            $kkm = new KkmServerConnect();
            return $kkm->deleteOfd($invoice);
        }

        return true;
    }


    public static function getPaymentAgentList()
    {
        $sk_services_path = app()->basePath() . '/app/Services/PaymentAgent';
        $dir_names = array_filter(scandir($sk_services_path), function ($v) {
            return !stristr($v, '.');
        });

        $dir_names = collect($dir_names)->keyBy(function ($item) {
            return $item;
        });

        return $dir_names;
    }

    public static function getPaymentAgent($class_name)
    {
        $agent_class = "App\\Services\\PaymentAgent\\{$class_name}\\IntegrationClass";
        if(class_exists($agent_class)){

            return $agent_class;
        }

        return null;
    }


}