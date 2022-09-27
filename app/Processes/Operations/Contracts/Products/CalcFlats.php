<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use Illuminate\Support\Str;

class CalcFlats{


    public static function calc($contract){

        $calculation = $contract->calculation;
        $terms = null;
        if($calculation && strlen($contract->calculation->risks) > 0){
            $terms = json_decode($contract->calculation->risks);
        }

        $risks = ProductsFlatsRisks::whereIn('id', $terms)->get();

        $insurance_amount = 0;
        $payment_total = 0;

        foreach ($risks as $risk){
            $insurance_amount += $risk->insurance_amount;
            $payment_total += $risk->payment_total;
        }


        $contract->insurance_amount = $insurance_amount;
        $contract->payment_total = $payment_total;

        //Определить скидку
        $discount = ProductsOfficialDiscount::where('json', \GuzzleHttp\json_encode($terms))->orderBy('discount', 'desc')->get()->first();

        if($discount){
            if($discount->type_id == 1){//Процент
                $contract->payment_total = $contract->payment_total - getTotalSumToPrice($contract->payment_total, $discount->discount);
            }

            if($discount->type_id == 2){//Фиксированная
                $contract->payment_total = $contract->payment_total - $discount->discount;
            }
        }

        $contract->save();

        return (boolean)$calculation->createPaymentCalc();
    }



}