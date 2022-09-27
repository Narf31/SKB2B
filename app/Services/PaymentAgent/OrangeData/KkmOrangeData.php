<?php

namespace App\Services\PaymentAgent\OrangeData;



use App\Models\Contracts\Payments;

class KkmOrangeData
{


    public static function sendKKT($payment)
    {

        if(app()->environment() == 'local'){
            return true;
        }

        $payment = Payments::query()->where('id', $payment->id)->first();

        if(strlen($payment->kkt_number) > 0){
            return true;
        }


        $client = [
            'inn' => '7714819895',
            'api_url' => '12003',
            'sign_pkey' => dirname(__DIR__) . '/OrangeData/secure_path_prod/orange_data_private.pem',
            'ssl_client_key' => dirname(__DIR__) . '/OrangeData/secure_path_prod/7714819895.key',
            'ssl_client_crt' => dirname(__DIR__) . '/OrangeData/secure_path_prod/7714819895.crt',
            'ssl_ca_cert' => dirname(__DIR__) . '/OrangeData/secure_path_prod/cacert.pem',
            'ssl_client_crt_pass' => 1234,
        ];


        $buyer = new OrangeDataClient($client); // create new client

        //dd($buyer->get_devices_status('Main'));

        $amount = getFloatFormat($payment->invoice_payment_total);
        $send_email = $payment->send_email;
        if(strlen($send_email) < 3){
            $send_email = 'noreple@sst.cat';
        }

        $key_order = UUID_V4();

        $order = [
            'id' => $key_order,
            'type' => 1,
            'taxationSystem' => 1,
            'customerContact' => $send_email,
            'senderEmail' => $send_email,
            'key' => '7714819895',
        ];

        $buyer->create_order($order);


        $position = [
            'quantity' => 1.000,
            'price' => $amount,
            'tax' => 6,
            'text' => "Страховая премия по договору страхования {$payment->bso->bso_title}",
            'paymentMethodType' => ((int)$payment->installment_algorithms_payment == 100) ? 1 : 2,
            'paymentSubjectType' => 4,
        ];


        $buyer->add_position_to_order($position);

        /*
            1 - сумма по чеку наличными, 1031
            2 - сумма по чеку безналичными, 1081
            14 - сумма по чеку предоплатой (зачетом аванса и (или) предыдущих платежей), 1215
            15 - сумма по чеку постоплатой (в кредит), 1216
            16 - сумма по чеку (БСО) встречным предоставлением, 1217
         */
        $payment_val = [
            'type' => ($payment->payment_type == 0) ? 1 : 2,
            'amount' => $amount,
        ];

        $buyer->add_payment_to_order($payment_val);

        $additional = [
            'additionalAttribute' => '',
            'customer' => $payment->contract->insurer->title,
            'customerINN' => '',
            'customerContact' => $send_email,
            'senderEmail' => $send_email,
        ];

        $buyer->add_additional_attributes($additional);

        $result = $buyer->send_order();
        if($result && $result === true){

            $order_status = $buyer->get_order_status($key_order);
            if($order_status == true){
                $payment->kkt_number = $key_order;
                $payment->save();
                return true;
            }
        }else{
            //dump($result, $key_order);
        }

        return false;
    }



}