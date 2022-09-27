<?php

namespace App\Processes\Operations\Contracts\Payments;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use Illuminate\Support\Str;

class PaymentsCreate{


    public static function save($contract)
    {
        if($contract->statys_id == 0 || $contract->statys_id == 2){

            $contract->payments()->delete();

            $payment_total = $contract->payment_total;
            $payment_data = getDateFormatEn($contract->begin_date);




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
                PaymentsCreate::create(($key+1), $contract, $payment_total, $payment_data, $quantity);
            }

            return true;

        }

        return false;
    }

    public static function create($payment_number, $contract, $payment_total, $payment_data, $quantity, $is_return = false)
    {
        $official_discount = 0;
        if($contract->data){
            if(isset($contract->data->official_discount)){
                $official_discount = getFloatFormat($contract->data->official_discount);
            }
        }

        $payment = Payments::create([
            'statys_id' => 0,
            'type_id' => 0,

            'payment_number' => $payment_number,
            'payment_data' => date('Y-m-d', strtotime("+".$quantity["month"]." month $payment_data")),
            'payment_total' => getTotalSumToPrice($payment_total, $quantity["payment"]),
            'installment_algorithms_payment' => getFloatFormat($quantity["payment"]),

            'contract_id' => $contract->id,

            'agent_organization_id' => $contract->agent_organization_id,
            'agent_id' => $contract->agent_id,
            'agent_parent_id' => $contract->agent_parent_id,
            'agent_curator_id' => $contract->agent_curator_id,

            'official_discount' => $official_discount,
            'informal_discount' => 0,
            'bank_kv' => 0,

            'financial_policy_id' => $contract->financial_policy_id,
            'financial_policy_manually_set' => $contract->financial_policy_manually_set,
            'financial_policy_kv_bordereau' => $contract->financial_policy_kv_bordereau,
            'financial_policy_kv_dvoy' => $contract->financial_policy_kv_dvoy,

        ]);

        PaymentDiscounts::recount($payment);

        if($is_return == true){
            return $payment;
        }
        return true;

    }

}