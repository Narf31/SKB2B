<?php

namespace App\Processes\Operations\Contracts\Payments;


use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Finance\Invoice;
use App\Models\Settings\PaymentMethods;
use App\Models\Users\PromoCode;
use App\Processes\Operations\Contracts\Invoice\InvoiceCreate;
use App\Processes\Operations\Contracts\Products\CalcOsago;
use App\Processes\Scenaries\Contracts\Products\Kasko;
use App\Services\Integration\VernaControllers\VernaMask;
use App\Services\Integration\VernaControllers\VernaPayment;
use Illuminate\Support\Str;

class PaymentsAccept{


    public static function accept($contract, $payment, $data)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = 'Ошибка платежа!';

        /*
          +"payment_type": "8"
          +"bso_receipt": "А7 110001"
          +"bso_receipt_id": "12"

          +"payment_type_send_checkbox": "0"
          +"payment_send_checkbox": ""
          +"send_email": ""
            +"promocode": ""
         */

        $method = PaymentMethods::findOrFail((int)$data->payment_type);
        if($method){

            if($method->key_type == 5){
                $promo = PromoCode::getPromoCode($data->promocode);
                if($promo){
                    $promo->contract_id = $contract->id;
                    $promo->is_actual = 0;
                    $promo->save();
                    $payment->payment_total = 0;
                }else{
                    $result->state = false;
                    $result->msg = 'Промокод не найден!';
                    return $result;
                }
            }


            $payment->payment_method_id = $method->id;
            $payment->payment_type = $method->payment_type;
            $payment->payment_flow = $method->payment_flow;

            $payment->payment_type_send_checkbox = $data->payment_type_send_checkbox;
            $payment->payment_send_checkbox = $data->payment_send_checkbox;
            $payment->send_email = $data->send_email;
            $payment->save();

            //$payment = PaymentDiscounts::recount($payment);
            //$payment = PaymentReceipt::attach($payment, $data);


            $result = InvoiceCreate::create($payment);

        }



        return $result;

    }

    public static function updeteDatePayments($contract, $payment_data)
    {

        if($contract->installment_algorithms){
            $installment_algorithms_info = $contract->installment_algorithms->info;
            $quantitys = [];
            foreach ($installment_algorithms_info->algorithm_list as $algorithm){
                $quantitys[] = ["month" => $algorithm->month, "payment" => $algorithm->payment];
            }

        }else{
            $quantitys = [
                ["month" => 0, "payment" => "100"]
            ];
        }

        foreach ($quantitys as $key => $quantity){
            PaymentsAccept::setDatePayment(($key+1), $contract, $payment_data, $quantity);
        }

        if($contract->begin_date <= $payment_data){
            $day = (int)countDayToDates($contract->begin_date, $contract->end_date);
            $contract->begin_date = date('Y-m-d 00:00:00', strtotime("+1 day $payment_data"));
            $contract->end_date = date('Y-m-d 23:59:59', strtotime("+{$day} day {$contract->begin_date}"));
            $contract->save();
        }


        return true;

    }

    public static function setDatePayment($payment_number, $contract, $payment_data, $quantity)
    {
        $payment = $contract->payments()->where('payment_number', $payment_number)->get()->first();
        if($payment){
            $payment->payment_data = date('Y-m-d', strtotime("+".$quantity["month"]." month $payment_data"));
            $payment->save();
        }

        return true;
    }



}