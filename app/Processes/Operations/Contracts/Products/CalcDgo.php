<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\Data\DGO\BaseRateDgo;
use App\Models\Directories\Products\Data\GAP\BaseRateGap;
use App\Models\Directories\Products\Data\GAP\Gap;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use Illuminate\Support\Str;

class CalcDgo{


    public static function calc($contract){

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }
        $state_calc = false;


        $payment_total = 0;

        $info = new \stdClass();
        $info->payment_tariff = '';
        $info->insurance_amount = '';
        $info->payment_total = '';

        $getTariff = BaseRateDgo::getBaseRateList($contract->insurance_amount);


        if($getTariff){



            $payment_total = getFloatFormat($getTariff->payment_total);

           if($payment_total > 0){
               $state_calc = true;

               $total_tarif = getPriceToTotalSum($contract->insurance_amount, $payment_total);
               $total_tarif = round(ceil($total_tarif*100)/100,2);

               $info->payment_tariff = $total_tarif;
               $info->insurance_amount = $contract->insurance_amount;
               $info->payment_total = $payment_total;
           }


        }



        if($state_calc == false){
            $contract->payment_total = 0;
            $calculation->state_calc = 0;
            $calculation->sum = '';
            $calculation->json = null;
            $calculation->messages = 'Ошибка тарификатора не настроен тариф!';
            $calculation->save();
        }else{

            $contract->payment_total = $payment_total;
            $calculation->insurance_amount = $contract->insurance_amount;
            $calculation->sum = $payment_total;
            $calculation->state_calc = 1;
            $calculation->json = \GuzzleHttp\json_encode($info);
            $calculation->messages = '';
            $calculation->save();


        }


        $contract->save();


        if($contract->payment_total > 0){
            $calculation->createPaymentCalc();
            return self::setGeneralSubjects($contract);
        }

        return $state_calc;

    }

    public static function setGeneralSubjects($contract)
    {
        //Привязываем контрагентов
        if($contract->insurer_id > 0) {
            GeneralSubjectsInfo::getGeneralSubject($contract->insurer_id);
        }
        if((int)$contract->owner_id > 0 && $contract->owner_id!=$contract->insurer_id) {
            GeneralSubjectsInfo::getGeneralSubject($contract->owner_id);
        }
        if((int)$contract->beneficiar_id > 0 && $contract->beneficiar_id!=$contract->insurer_id && $contract->beneficiar_id!=$contract->owner_id){
            if($contract->beneficiar_id!=$contract->owner_id){

                GeneralSubjectsInfo::getGeneralSubject($contract->beneficiar_id);
            }
        }



        foreach ($contract->contracts_insurers as $insurer){
            GeneralSubjectsInfo::updateGeneralToInsurer($insurer, 1145);
        }


        return MainScorings::scoring($contract);
    }


}