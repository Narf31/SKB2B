<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\Data\GAP\BaseRateGap;
use App\Models\Directories\Products\Data\GAP\Gap;
use App\Models\Directories\Products\Data\Mortgage\BaseRateLife;
use App\Models\Directories\Products\Data\Mortgage\BaseRateProperty;
use App\Models\Directories\Products\Data\Mortgage\BaseRateTitle;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use Illuminate\Support\Str;

class CalcMortgage{


    public static function calc($contract){

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }
        $state_calc = false;
        $msg = 'Ошибка тарификатора не настроен тариф!';

        $payment_total = 0;

        $info = new \stdClass();
        $info->title = 'Ипотека';
        $info->insurance_amount = $contract->insurance_amount;
        $info->payment_total = '';
        $info->title_tariff = '';
        $info->official_discount = new \stdClass();
        $info->info = [];

        $insurance_amount = $contract->insurance_amount;

        $life_payment_total = 0;
        if((int)$contract->data->is_life == 1){

            $insurer = $contract->insurer->data();
            $baserate_life = BaseRateLife::getBaseRate($contract->product_id, date('Y')-date('Y', strtotime($insurer->birthdate)));

            if($baserate_life){
                $baserate_tarife = getFloatFormat($baserate_life->tarife_man);
                if($insurer->sex == 1) $baserate_tarife = getFloatFormat($baserate_life->tarife_woman);

                $life_payment_total = getTotalSumToPrice(getFloatFormat($insurance_amount), (getFloatFormat($baserate_tarife)));
                $info->info['life'] = [
                    'title' => 'Жизнь',
                    'tariff' => getFloatFormat($baserate_tarife),
                    'insurance_amount' => $insurance_amount,
                    'payment_total' => $life_payment_total,
                ];
                $info->title_tariff .= 'L:'.getFloatFormat($baserate_tarife).'; ';
                $state_calc = true;
            }

        }

        $property_payment_total = 0;
        if((int)$contract->data->is_property == 1){

            $baserate_property = BaseRateProperty::getBaseRate($contract->product_id, $contract->data->class_realty);
            if($baserate_property){
                $baserate_tarife = getFloatFormat($baserate_property->tarife);

                $property_payment_total = getTotalSumToPrice(getFloatFormat($insurance_amount), (getFloatFormat($baserate_tarife)));
                $info->info['property'] = [
                    'title' => 'Имущество',
                    'tariff' => getFloatFormat($baserate_tarife),
                    'insurance_amount' => $insurance_amount,
                    'payment_total' => $property_payment_total,
                ];
                $info->title_tariff .= 'P:'.getFloatFormat($baserate_tarife).'; ';
                $state_calc = true;
            }

        }

        $title_payment_total = 0;
        if((int)$contract->data->is_title == 1){

            $baserate_title = BaseRateTitle::getBaseRate($contract->product_id, $contract->data->class_realty, $contract->data->type_realty);
            if($baserate_title){
                $baserate_tarife = getFloatFormat($baserate_title->tarife);

                $title_payment_total = getTotalSumToPrice(getFloatFormat($insurance_amount), (getFloatFormat($baserate_tarife)));
                $info->info['title'] = [
                    'title' => 'Титул',
                    'tariff' => getFloatFormat($baserate_tarife),
                    'insurance_amount' => $insurance_amount,
                    'payment_total' => $title_payment_total,
                ];
                $info->title_tariff .= 'T:'.getFloatFormat($baserate_tarife).'; ';
                $state_calc = true;
            }
        }




        if((int)$contract->data->is_life == 1 && $life_payment_total == 0){
            $state_calc = false;
            $msg = 'Ошибка тарификатора Жизнь!';
        }
        if((int)$contract->data->is_property == 1 && $property_payment_total == 0){
            $state_calc = false;
            $msg = 'Ошибка тарификатора Имущество!';
        }
        if((int)$contract->data->is_title == 1 && $title_payment_total == 0){
            $state_calc = false;
            $msg = 'Ошибка тарификатора Титул!';
        }

        if($state_calc == true){
            $payment_total = $life_payment_total + $property_payment_total + $title_payment_total;
            $info->payment_total = getFloatFormat($payment_total);

            if($contract->data->official_discount > 0){
                $info->official_discount->tariff = getFloatFormat($contract->data->official_discount);
                $info->official_discount->summ = getTotalSumToPrice($payment_total, $contract->data->official_discount);
            }
        }


        if($state_calc == false){
            $contract->payment_total = 0;
            $calculation->state_calc = 0;
            $calculation->sum = '';
            $calculation->json = null;
            $calculation->messages = $msg;
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