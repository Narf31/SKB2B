<?php

namespace App\Services\PaymentAgent\YooKassa;



class KkmYooKassa
{



    public static function sendKKT(){

        $api = new YooKassaClient();

        $data = array(
            'amount' => array(
                'value' => 100.0,
                'currency' => 'RUB',
            ),

            'payment_method_data' => array(
                'type' => 'cash',
            ),
            'confirmation' => array(
                'type' => 'redirect',
                'return_url' => '',
            ),
            'description' => 'Заказ №72',

            'capture' => true,

            'metadata' => array(
                'order_id' => '37',
            )
        );

        $payment_data = $api->createPayments($data);
        dd($payment_data);

        $data = array(
            'customer' => array(
                'full_name' => 'Иванов Иван Иванович',
                'phone' => '+79859630544',
                'email' => 'narf-narf@yandex.ru',
                'inn'   => '0000000000',
            ),
            'payment_id' => '10',
            'type' => 'payment',
            'send' => true,
            'items' => array(
                array(
                    'description' => 'Наименование товара 1',
                    'quantity' => '1.00',
                    'amount' => array(
                        'value' => '100.00',
                        'currency' => 'RUB',
                    ),
                    'vat_code' => 1,
                    'payment_mode' => 'full_prepayment',
                    'payment_subject' => 'insurance_premium',
                    'country_of_origin_code' => 'RU'
                ),

            ),
            'settlements' => array(
                array(
                    'type' => 'prepayment',
                    'amount' => array(
                        'value' => '100.00',
                        'currency' => 'RUB',
                    )
                )
            ),
        );

        $result = $api->createReceipt($data);
        dd($result);

    }


}