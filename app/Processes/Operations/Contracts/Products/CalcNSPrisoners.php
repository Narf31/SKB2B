<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\CurrencyValue;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use App\Processes\Tariff\Settings\Product\TariffDefault;
use App\Processes\Tariff\Settings\Product\TariffMigrants;
use App\Processes\Tariff\Settings\Product\TariffNSPrisoners;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use App\Services\DaData\DaDataGetInfo;
use Illuminate\Support\Str;

class CalcNSPrisoners{



    public static function calc($contract){

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }
        $state_calc = false;
        $msg = 'Ошибка тарификатора не настроен тариф!';

        $payment_total = 0;

        $info = new \stdClass();
        $info->title = 'НС Заключенных';
        $info->insurance_amount = '';
        $info->payment_total = '';
        $info->title_tariff = '';
        $info->official_discount = new \stdClass();
        $info->info = [];


        $ns_payment_total = 0;
        $nst_payment_total = 0;

        $tariff = CalcDefault::getTariff($contract);
        if($tariff){

            $programs = $tariff['programs'][0];
            $_base = TariffDefault::getTariffValue($programs['values'], 1, $contract->data->insurance_amount_ns);

            if($_base){
                $baserate_tarife = getFloatFormat($_base);
                $ns_payment_total = getTotalSumToPrice(getFloatFormat($contract->data->insurance_amount_ns), (getFloatFormat($baserate_tarife)));

                if($contract->data->insurance_term != 12){
                    $ns_payment_total = ($ns_payment_total/12) * $contract->data->insurance_term;
                }

                $info->info['ns'] = [
                    'title' => $programs['title'],
                    'tariff' => getFloatFormat($baserate_tarife),
                    'insurance_amount' => $contract->data->insurance_amount_ns,
                    'payment_total' => $ns_payment_total,
                ];

                $contract->insurance_amount = $contract->data->insurance_amount_ns;
                $info->insurance_amount = $contract->data->insurance_amount_ns;
                $info->title_tariff .= 'N:'.getFloatFormat($baserate_tarife).'; ';
                $state_calc = true;
            }

        }


        if($state_calc == true){
            if(isset($contract->data->is_tuberculosis) && $contract->data->is_tuberculosis == 1){
                $programs = $tariff['programs'][1];
                $base_coefficient = TariffDefault::getTariffValue($programs['values'], 1, $contract->data->insurance_amount_tuberculosis);
                if($base_coefficient){

                    $baserate_tarife = getFloatFormat($_base);
                    $nst_payment_total = getTotalSumToPrice(getFloatFormat($contract->data->insurance_amount_tuberculosis), (getFloatFormat($baserate_tarife)));

                    if($contract->data->insurance_term != 12){
                        $nst_payment_total = ($nst_payment_total/12) * $contract->data->insurance_term;
                    }

                    $info->info['tuberculosis'] = [
                        'title' => $programs['title'],
                        'tariff' => getFloatFormat($baserate_tarife),
                        'insurance_amount' => $contract->data->insurance_amount_tuberculosis,
                        'payment_total' => $nst_payment_total,
                    ];
                    $info->title_tariff .= 'T:'.getFloatFormat($baserate_tarife).'; ';
                    $state_calc = true;


                }else{
                    $state_calc = false;
                    $msg = 'Ошибка тарификатора Туберкулез!';
                }

            }
        }


        if($state_calc == true){
            $payment_total = $ns_payment_total + $nst_payment_total;
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

    public static function getTariff($contract){

        //Определяем настройки тарифа
        //Проверяем пользователя
        $special_settings = ProductsAgentsSpecialSsettings::where('product_id', $contract->product_id)->where('user_id', $contract->agent_id)->get()->first();
        if($special_settings){
            if($special_settings->json && strlen($special_settings->json) > 0){
                return \GuzzleHttp\json_decode($special_settings->json, true);
            }
        }

        //Проверяем организацию
        $special_settings = ProductsAgentsSpecialSsettings::where('product_id', $contract->product_id)->where('organization_id', $contract->agent->organization_id)->get()->first();
        if($special_settings){
            if($special_settings->json && strlen($special_settings->json) > 0){
                return \GuzzleHttp\json_decode($special_settings->json, true);
            }
        }

        //Возвращаем значения по умолчанию
        $special_settings = ProductsSpecialSsettings::where('product_id', $contract->product_id)->get()->first();
        if($special_settings){
            if($special_settings->json && strlen($special_settings->json) > 0){
                return \GuzzleHttp\json_decode($special_settings->json, true);
            }
        }


        return null;
    }


    public static function getCalcProgram($contract, $calculation, $programs, $base_tariff, $insurance_amount)
    {
        if(strlen($base_tariff) == 0){
            return null;
        }

        if(strlen($insurance_amount) == 0){
            return null;
        }

        $result = new \stdClass();
        $result->title = $programs['title'];


        $result->insurance_amount = getFloatFormat($insurance_amount);
        $result->payment_base_tariff = getFloatFormat($base_tariff);

        $result->payment_tariff = $result->payment_base_tariff;
        $result->discont_total = 0;
        $result->allowances_total = 0;

        $result->payment_total = getTotalSumToPrice($result->insurance_amount, $result->payment_tariff);

        return $result;
    }



}