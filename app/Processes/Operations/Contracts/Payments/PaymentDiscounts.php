<?php

namespace App\Processes\Operations\Contracts\Payments;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use Illuminate\Support\Str;

class PaymentDiscounts{


    public static function recount($payment){

        $payment->update([
            "official_discount_total" => getTotalSumToPrice($payment->payment_total, $payment->official_discount),
            "informal_discount_total" => getTotalSumToPrice($payment->payment_total, $payment->informal_discount),
            "financial_policy_kv_bordereau_total" => getTotalSumToPrice($payment->payment_total, $payment->financial_policy_kv_bordereau),
            "financial_policy_kv_dvoy_total" => getTotalSumToPrice($payment->payment_total, $payment->financial_policy_kv_dvoy),
            "bank_kv_total" => getTotalSumToPrice($payment->payment_total, $payment->bank_kv),
        ]);

        //БУДЕТ ТУТ
        // КВ БОРДЕРО - (official_discount + bank_kv)
        // КВ Агента/Менеджера - (official_discount + bank_kv)

        if($payment->financial_policy_manually_set == 0 && $payment->financial_policy_id > 0){

            if($payment->bank_kv > 0){

                $payment->financial_policy_kv_bordereau = $payment->financial_policy_kv_bordereau-$payment->bank_kv;
                $payment->financial_policy_kv_bordereau_total = getTotalSumToPrice($payment->payment_total, $payment->financial_policy_kv_bordereau);

            }

            if($payment->official_discount > 0){

                $payment->financial_policy_kv_bordereau = $payment->financial_policy_kv_bordereau-$payment->official_discount;
                $payment->financial_policy_kv_bordereau_total = getTotalSumToPrice($payment->payment_total, $payment->financial_policy_kv_bordereau);

            }

            // если есть коммисия > 0
            if($pay_mehtod = $payment->payment_method){
                if(isset($pay_mehtod->acquiring) && $pay_mehtod->acquiring > 0){
                    $payment->acquire_percent  = $pay_mehtod->acquiring;
                    $payment->acquire_total = getTotalSumToPrice($payment->payment_total, $pay_mehtod->acquiring);
                }else{
                    $payment->acquire_percent = 0;
                    $payment->acquire_total = 0;
                }
            }

        }


        $payment->invoice_payment_total = getFloatFormat($payment->payment_total) - getFloatFormat($payment->official_discount_total);

        $marjing = 100-($payment->financial_policy_kv_bordereau+$payment->financial_policy_kv_dvoy)-getFloatFormat($payment->acquire_percent);
        $payment->financial_policy_marjing = $marjing;
        $payment->financial_policy_marjing_total = getTotalSumToPrice($payment->payment_total, $marjing);

        $payment->save();
        return $payment;


    }

}