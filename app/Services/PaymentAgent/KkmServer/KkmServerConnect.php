<?php

namespace App\Services\PaymentAgent\KkmServer;



class KkmServerConnect
{

    public $URL = 'http://10.0.20.64:5893/Execute';
    public $userName = 'User';
    public $password = '';


    public $paymentLinck = '';

    public function __construct()
    {


    }


    private function send($data){


        $http = [
            'method' => 'POST',
            'header' => 'Content-Type: application/json; charset=utf-8'."\r\n".
                'Authorization: Basic '.base64_encode("{$this->userName}:{$this->password}")."\r\n",
            'content' => json_encode($data)
        ];

        $stream = stream_context_create(['http'=>$http]);
        $result = file_get_contents($this->URL,false, $stream);

        return \GuzzleHttp\json_decode($result);

    }



    public function test()
    {
        return $this->send(['Command'=>'GetDataKKT','NumDevice'=>1]);

    }

    public function createBillingOFD($invoice)
    {

        if($invoice->kkt_status_id == 0){

            $invoice->kkt_token = UUID_V4();
            $invoice->save();
        }

        $payment = $invoice->payments()->get()->first();

        $arr = [
            'Command' => 'RegisterCheck',
            'NumDevice' => '0',
            'InnKkm' => '',
            'KktNumber' => '',
            'Timeout' => '30',
            'IdCommand' => $invoice->kkt_token,
            'IsFiscalCheck' => 'true',
            'TypeCheck' => '0',
            'NotPrint' => 'true',
            'NumberCopies' => '0',
            'CashierName' => 'Грушевская А.В.',
            'CashierVATIN' => '',
            'ClientAddress' => $invoice->client_email,
            'ClientInfo' => $invoice->client_info,
            'ClientINN' => '',
            'SenderEmail' => 'noreply@riks-ins.ru',
            'PlaceMarket' => 'kkmserver.ru',
            'TaxVariant' => '',
            'AgentSign' => null,
            'AgentData' => null,
            'PurveyorData' => null,
            'UserAttribute' => null,

            'AdditionalAttribute' => '',
            'CheckStrings' => [
                ['PrintImage' => null],
                ['PrintText' => [
                    'Text' => 'ООО "РИКС"',
                    'Font' => 1
                ]],
                ['PrintText' => [
                    'Text' => '<<->>',
                ]],
                ['Register' => ['Name' => 'Договор '.$payment->contract->bso->bso_title,
                    'Quantity' => 1,
                    'Price' => getFloatFormat($payment->payment_total),
                    'Amount' => getFloatFormat($payment->payment_total),
                    'Department' => 0,
                    'Tax' => -1,
                    'EAN13' => 1254789547853,
                    'SignMethodCalculation' => 1,
                    'SignCalculationObject' => 1,
                    'MeasurementUnit' => 'шт',
                ]],
            ],

            "Cash" => 0,
            "ElectronicPayment" => getFloatFormat($invoice->invoice_payment_total),
            "AdvancePayment" => 0,
            "Credit" => 0,
            "CashProvision" => 0,

        ];

        $result = $this->send($arr);
        $invoice->kkt_json = json_encode($result);


        if(isset($result) && isset($result->Status) && (int)$result->Status == 0){

            $invoice->kkt_status_id = 1;

        }else{

            $invoice->kkt_status_id = 2;

        }

        $invoice->save();

        return true;
    }

    public function deleteOfd($invoice)
    {

        if($invoice->kkt_status_id == 1){

            $invoice->kkt_token = UUID_V4();
            $invoice->save();

            $payment = $invoice->payments()->get()->first();

            $arr = [
                'Command' => 'RegisterCheck',
                'NumDevice' => '0',
                'InnKkm' => '',
                'KktNumber' => '',
                'Timeout' => '30',
                'IdCommand' => $invoice->kkt_token,
                'IsFiscalCheck' => 'true',
                'TypeCheck' => '1',
                'NotPrint' => 'true',
                'NumberCopies' => '0',
                'CashierName' => 'Грушевская А.В.',
                'CashierVATIN' => '',
                'ClientAddress' => $invoice->client_email,
                'ClientInfo' => $invoice->client_info,
                'ClientINN' => '',
                'SenderEmail' => 'noreply@riks-ins.ru',
                'PlaceMarket' => 'kkmserver.ru',
                'TaxVariant' => '',
                'AgentSign' => null,
                'AgentData' => null,
                'PurveyorData' => null,
                'UserAttribute' => null,

                'AdditionalAttribute' => '',
                'CheckStrings' => [
                    ['PrintImage' => null],
                    ['PrintText' => [
                        'Text' => 'ООО "РИКС"',
                        'Font' => 1
                    ]],
                    ['PrintText' => [
                        'Text' => '<<->>',
                    ]],
                    ['Register' => ['Name' => 'Договор '.$payment->contract->bso->bso_title,
                        'Quantity' => 1,
                        'Price' => getFloatFormat($payment->payment_total),
                        'Amount' => getFloatFormat($payment->payment_total),
                        'Department' => 0,
                        'Tax' => -1,
                        'EAN13' => 1254789547853,
                        'SignMethodCalculation' => 1,
                        'SignCalculationObject' => 1,
                        'MeasurementUnit' => 'шт',
                    ]],
                ],

                "Cash" => 0,
                "ElectronicPayment" => getFloatFormat($invoice->invoice_payment_total),
                "AdvancePayment" => 0,
                "Credit" => 0,
                "CashProvision" => 0,

            ];

            $result = $this->send($arr);
            $invoice->kkt_json = json_encode($result);


            if(isset($result) && isset($result->Status) && (int)$result->Status == 0){

                $invoice->kkt_status_id = 5;

            }else{

                $invoice->kkt_status_id = 4;

            }

            $invoice->save();

        }

        return true;
    }



}