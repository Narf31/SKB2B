<?php

namespace App\Processes\Operations\Contracts\Payments;


use App\Models\BSO\BsoItem;
use App\Models\Contracts\Payments;
use App\Models\Reports\ReportOrders;
use App\Models\Reports\ReportPayments;
use App\Models\Settings\PaymentMethods;
use App\Models\User;
use App\Models\Users\PromoCode;
use App\Processes\Operations\Contracts\Invoice\InvoiceCreate;
use Illuminate\Support\Str;

class PaymentsReports{


    public static function set_payment($payment)
    {
        $contract = $payment->contract;

        if($payment->statys_id == 1){

            if($contract->product->slug == 'liabilityArbitrationManager'){

                $data = $contract->data;
                if($data->kv_agent_id > 0 && getFloatFormat($data->kv_agent) > 0){
                    self::create_report_payment($contract,
                        $payment,
                        $data->agent,
                        getFloatFormat($data->kv_agent),
                        0, 0,
                        getTotalSumToPrice($payment->payment_total, getFloatFormat($data->kv_agent)));
                }

                if($data->kv_agent2_id > 0 && getFloatFormat($data->kv_agent2) > 0){
                    self::create_report_payment($contract,
                        $payment,
                        $data->agent2,
                        getFloatFormat($data->kv_agent2),
                        0, 0,
                        getTotalSumToPrice($payment->payment_total, getFloatFormat($data->kv_agent2)));
                }

                if($data->kv_manager_id > 0 && getFloatFormat($data->kv_manager) > 0){
                    self::create_report_payment($contract,
                        $payment,
                        $data->manager,
                        getFloatFormat($data->kv_manager),
                        0, 0,
                        getTotalSumToPrice($payment->payment_total, getFloatFormat($data->kv_manager)));
                }

                if($data->kv_manager2_id > 0 && getFloatFormat($data->kv_manager2) > 0){
                    self::create_report_payment($contract,
                        $payment,
                        $data->manager2,
                        getFloatFormat($data->kv_manager2),
                        0, 0,
                        getTotalSumToPrice($payment->payment_total, getFloatFormat($data->kv_manager2)));
                }

                if($data->kv_manager3_id > 0 && getFloatFormat($data->kv_manager3) > 0){
                    self::create_report_payment($contract,
                        $payment,
                        $data->manager3,
                        getFloatFormat($data->kv_manager3),
                        0, 0,
                        getTotalSumToPrice($payment->payment_total, getFloatFormat($data->kv_manager3)));
                }


            }else{

                $dep_total = 0;
                $kred_total = 0;

                if($payment->payment_flow == 1){//СК
                    $kred_total = ($payment->financial_policy_kv_bordereau+$payment->financial_policy_kv_dvoy);
                }else{
                    $dep_total = $payment->payment_total - ($payment->financial_policy_kv_bordereau+$payment->financial_policy_kv_dvoy);
                }


                self::create_report_payment($contract, $payment, $contract->agent, $payment->financial_policy_kv_bordereau, $payment->financial_policy_kv_dvoy,$dep_total, $kred_total);
            }

            return true;
        }

        return false;
    }

    public static function create_report_payment($contract, $payment, $agent, $financial_policy_kv_bordereau, $financial_policy_kv_dvoy, $dep_total = 0, $kred_total = 0)
    {
        $report_payment = ReportPayments::where('contract_id', $contract->id)
            ->where('payment_id', $payment->id)
            ->where('agent_id', $agent->id)
            ->get()->first();

        if(!$report_payment){
            $report_payment = ReportPayments::create([
                'contract_id' => $contract->id,
                'payment_id' => $payment->id,
                'agent_organization_id' => $agent->organization_id,
                'agent_id' => $agent->id,
                'agent_parent_id' => $agent->parent_id,
                'agent_curator_id' => $agent->curator_id,
                'financial_policy_kv_bordereau' => getFloatFormat($financial_policy_kv_bordereau),
                'financial_policy_kv_dvoy' => getFloatFormat($financial_policy_kv_dvoy),
                'financial_policy_kv_bordereau_total' => getTotalSumToPrice($payment->payment_total, $financial_policy_kv_bordereau),
                'financial_policy_kv_dvoy_total' => getTotalSumToPrice($payment->payment_total, $financial_policy_kv_dvoy),
                'reports_order_id' => 0,
                'reports_dvou_id' => 0,

                'dep_total' => $dep_total,
                'kred_total' => $kred_total,
            ]);
        }
        return $report_payment;

    }

}