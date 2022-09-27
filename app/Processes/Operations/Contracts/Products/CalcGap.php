<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\Data\GAP\BaseRateGap;
use App\Models\Directories\Products\Data\GAP\Gap;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use Illuminate\Support\Str;

class CalcGap{


    public static function calc($contract){

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }
        $state_calc = false;

        $auto = $contract->object_insurer->data();

        $getTariff = BaseRateGap::where('program_id', $contract->data->insurance_option)
            ->where('amount_from', '<=', getFloatFormat($auto->car_price))
            ->where('amount_to', '>=', getFloatFormat($auto->car_price))
            ->orderBy('amount_to', 'desc')
            ->get()->first();

        $payment_total = 0;

        $info = new \stdClass();
        $info->title = Gap::OPTION[$contract->data->insurance_option];
        $info->payment_tariff = '';
        $info->insurance_amount = $contract->insurance_amount;
        $info->max_amount = '';
        $info->payment_total = '';


        if($getTariff){



            $kv = 100-$contract->financial_policy_kv_bordereau;
            //if($getTariff->max_amount < $contract->insurance_amount){
                $info->max_amount = getFloatFormat($getTariff->max_amount);
            //}

            $payment_total = getFloatFormat((getFloatFormat($getTariff->technical_payment)/$kv)*100);


            if($payment_total > 0){
               $state_calc = true;

               $total_tarif = getPriceToTotalSum($contract->insurance_amount, $payment_total);
               $total_tarif = round(ceil($total_tarif*100)/100,2);

               $info->payment_tariff = $total_tarif;
               //$info->insurance_amount = $contract->insurance_amount;
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