<?php

namespace App\Processes\Operations\Contracts\Invoice;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Finance\Invoice;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Contract\ContractMasks;
use App\Processes\Operations\Contracts\Contract\ContractSubjects;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsReports;
use App\Processes\Operations\Mails\ContractMails;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsAutomatic;
use App\Services\PaymentAgent\IntegrationPaymentAgent;
use App\Services\PaymentAgent\OrangeData\KkmOrangeData;
use Illuminate\Support\Str;

class InvoiceAutomatic{



    public static function automatic($invoice)
    {

        $result = new \stdClass();
        $result->state = true;
        $result->msg = "Cчета #$invoice->id";
        $result->invoice_id = $invoice->id;

        $state_payment = $invoice->payment_method->getStatePayment($invoice);

        if($state_payment == 1){
            InvoiceAutomatic::closeInvoice($invoice, date("Y-m-d H:i:s"), $invoice->invoice_payment_total);
        }

        return $result;
    }

    public static function closeInvoice($invoice, $invoice_payment_date, $invoice_payment_total, $is_export = false)
    {


        foreach ($invoice->payments as $payment)
        {

            //$payment->invoice_payment_total = $payment->payment_total;
            $payment->invoice_payment_date = $invoice_payment_date;
            $payment->payment_data = $invoice_payment_date;
            $payment->statys_id = 1;
            $payment->save();

            $contract = $payment->contract;
            //if($contract->kind_acceptance == 0){
                $contract->statys_id = 4;
                $contract->kind_acceptance = 1;
                $contract->save();
                BSOAccept::accept($contract->bso);
            //}

            if((int)$payment->payment_number == 1){
                //PaymentsAccept::updeteDatePayments($contract, setDateTimeFormat($invoice_payment_date));
            }

            if($payment->supplementary){
                $supplementary = $payment->supplementary;
                $supplementary->status_id = 4;
                $supplementary->save();

                $contract->end_date = $supplementary->end_date;
                $contract->save();
            }


            ContractsLogs::setContractLogs(
                $contract->id,
                auth()->id(),
                $contract->statys_id,
                Contracts::STATYS[$contract->statys_id],
                'Договор оплачен! Платеж #'.(int)$payment->payment_number);

            PaymentsReports::set_payment($payment);

            if($is_export == false){
                //Создаем документ на оплату
                ContractMasks::payment($payment);


                //Доступ в ЛК
                //ContractSubjects::access($contract);

                //Отправляем договор клиенту
                $client_email = (strlen($invoice->client_email) > 3) ? $invoice->client_email : $contract->insurer->email;
                if(strlen($client_email) > 3){
                    //ContractMails::sendDocuments($contract, $client_email);
                }

                if((int)$payment->payment_number == 1){
                    //MatchingsAutomatic::checkContract($contract);
                    //KkmOrangeData::sendKKT($payment);
                }

            }




        }

        if(auth()->check()){
            $invoice->invoice_payment_user_id = auth()->id();
        }else{
            $invoice->invoice_payment_user_id = $invoice->agent_id;
        }


        $invoice->invoice_payment_date = $invoice_payment_date;
        $invoice->invoice_payment_total = $invoice_payment_total;

        $invoice->status_id = 2;
        $invoice->save();


        if($is_export == false && $invoice->type == 'cashless' && (int)$invoice->client_type == 0){
            //IntegrationPaymentAgent::getOfdReceipt($invoice);
        }


        return true;
    }


}