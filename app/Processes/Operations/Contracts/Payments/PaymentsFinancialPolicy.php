<?php

namespace App\Processes\Operations\Contracts\Payments;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Payments;
use App\Models\Directories\FinancialPolicy;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use Illuminate\Support\Str;

class PaymentsFinancialPolicy{


    public static function getFinancialPolicy($contract,$is_contract = 0)
    {

        $financial_policy = new \stdClass();
        $financial_policy->financial_policy_id = 0;
        $financial_policy->financial_policy_manually_set = 0;
        $financial_policy->financial_policy_kv_bordereau = 0;
        $financial_policy->financial_policy_kv_dvoy = 0;

        $fp = FinancialPolicy::query();

        $fp->where('is_actual', 1);
        $fp->where('product_id', $contract->product_id);
        $fp->where('bso_supplier_id', $contract->bso_supplier_id);


        //Сделать определения по сегменту

        $result = [];

        foreach ($fp->get() as $actually){

            if(sizeof($actually->segments)){


            }else{

                $result[] = [
                    'id' => $actually->id,
                    'bso_supplier_id' => $actually->bso_supplier_id,
                    'kv_sk' => $actually->kv_sk,
                ];
            }


        }


        if (count($result) > 0) {
            usort($result, function ($a, $b) {
                return ($b['kv_sk'] - $a['kv_sk']);
            });

            $policy_id = $is_contract ? $contract->financial_policy_id : $result[0]['id'];

            $policy = FinancialPolicy::findOrFail($policy_id);
            $info = $policy->getContractGroupKV($contract);

            $financial_policy->financial_policy_id = $policy->id;
            $financial_policy->financial_policy_kv_bordereau = $info->kv_borderau;
            $financial_policy->financial_policy_kv_dvoy = $info->kv_dvou;
            $financial_policy->financial_policy_kv_parent = $info->kv_parent;
        }


        return $financial_policy;
    }



}