<?php

namespace App\Processes\Operations\Contracts\BSO;



use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Settings\FinancialGroupPaymentInfo;
use App\Processes\Operations\Contracts\Contract\ContractMasks;


class BSOAccept{


    public static function getBso($contract, $bso_id)
    {
        $bso = null;
        if($bso_id > 0){
            $bso = BsoItem::getBsoOnlineId($bso_id);
        }else{
            $bso = BsoItem::getElectronicBso($contract->agent, $contract->bso_supplier, $contract->product);
        }

        //&& $bso->agent_id == $contract->agent_id
        //if($bso && (int)$bso->state_id == 0 && ((int)$bso->location_id == 0 || (int)$bso->location_id == 1)) return $bso;
        //else return null;

        return $bso;
    }


    public static function accept($bso)
    {
        if($bso){
            $bso->location_id = 1;
            $bso->state_id = 2;


            $hold_kv = $bso->contract->bso_supplier->hold_kv_product($bso->contract->product_id);

            if($hold_kv/* && $hold_kv->is_auto_bso == 1*/){

                $group_id = $bso->contract->agent->financial_group_id;
                $payment_info = FinancialGroupPaymentInfo::findPaymentInfo($hold_kv->insurance_companies_id, $hold_kv->bso_supplier_id, $hold_kv->id, $group_id, $bso->bso_class_id);

                if($payment_info && $payment_info->is_auto_bso == 1) {
                    $bso->user_id = 0;
                    $bso->location_id = 4;
                    $bso->transfer_to_org_time = getDateTime();
                }
            }


            $bso->save();

            BsoLogs::setLogs(
                $bso->id,
                $bso->state_id,
                $bso->location_id,
                0,
                0,
                0,
                0,
                0,
                0
            );

            //Создаем маску договора
            return ContractMasks::contract($bso->contract);
        }

        return null;
    }



}