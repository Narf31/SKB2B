<?php

namespace App\Services\PaymentAgent\PAYMO;



class IntegrationClass
{

    const URL = 'https://paymo.ru/rest';

    //https://docs.paymo.ru/#4-2

    //Создаем платежную страницу
    public static function createBillingPage($invoice)
    {
        //$invoice->org->secret_key

        return urlClient("/contracts/online/$invoice->md5_token");
        /*
        $payment = $invoice->payments->first();
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', self::URL.'/merchant/invoice/', [
            'json' => [
                'api_key' => $invoice->org->api_key,
                'contact_email' => $payment->send_email,
                'price' => $invoice->invoice_payment_total,
                'description' => "Оплата счета {$invoice->id}",
                'order' => $invoice->md5_token.'11111',
            ]
        ]);

        return json_decode((string)$response->getBody(), true);
        */
    }


    public static function checkBillingPage($invoice)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', self::URL.'/merchant/invoice/status/', [
            'json' => [
                'api_key' => $invoice->org->api_key,
                'order' => $invoice->md5_token
            ]
        ]);

        $result = json_decode((string)$response->getBody(), true);

        /*

            "result" => "success"
            "message" => "Счет найден."
            "status" => "processing"

            PROCESSING 	Платеж в обработке.
            DEPOSITED 	Транзакция совершена успешно.
            DECLINED 	Транзакция неуспешна.
            WAIT_EXTERNAL 	Ожидается подтверждение от внешней платежной системы.
            REFUNDED 	Осуществлен полный возврат денежных средств.
            APPROVED 	Денежные средства захолдированы, ожидается подтверждение платежа.
            PART_DEPOSITED 	Произведено частичное списание захолдированных средств.
            PART_REFUNDED 	Произведен частичный возврат денежных средств.

         */


        dd($result);

    }


    public static function createOfdReceipt($invoice)
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', self::URL.'/merchant/', [
            'extra' => [
                'receipt' => [
                    'taxationSystem' => 0,
                    'positions' =>[
                        'tax' => 1,
                        'name' => "Название покупки",
                        'amount' => 100,
                        'quantity' => 1,
                        'paymentMethod' => 1,
                        'paymentSubject' => 1,
                    ],
                    'contact' => 'narf-narf@yandex.ru'
                ]
            ]
        ]);

        dd($response);
        dd("OK");

    }

}