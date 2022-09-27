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
use App\Processes\Tariff\Settings\Product\TariffMigrants;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use Illuminate\Support\Str;

class CalcMigrants{



    public static function calc($contract){

        $calculation = $contract->data;
        $calculation_res = $contract->calculation;
        if(!$calculation_res){
            $calculation_res = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }

        $tariff = self::getTariff($contract);

        if(!$tariff){
            return false;
        }

        $result = new \stdClass();
        $result->payment_total = getFloatFormat(0);
        $result->info = [];

        $programs = $tariff['programs'][0];
        $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, $calculation->programs_id);
        $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, $calculation->programs_id);
        $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);

        if($calc){
            $contract->insurance_amount = $calc->insurance_amount;
            $result->payment_total += getFloatFormat($calc->payment_total);
            $result->info[] = $calc;
        }

        if(isset($calculation->ns) && $calculation->ns == 1){
            $programs = $tariff['programs'][1];
            $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, 1);
            $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);
            if($calc){
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        if(isset($calculation->pregnancy) && $calculation->pregnancy == 1){
            $programs = $tariff['programs'][2];
            $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, 1);
            $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);
            if($calc){
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        if(isset($calculation->clinical_examination) && $calculation->clinical_examination == 1){
            $programs = $tariff['programs'][3];
            $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, 1);
            $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);
            if($calc){
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        if(isset($calculation->dental_care) && $calculation->dental_care == 1){
            $programs = $tariff['programs'][4];
            $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, 1);
            $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);
            if($calc){
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        if(isset($calculation->interment) && $calculation->interment == 1){
            $programs = $tariff['programs'][5];
            $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, 1);
            $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);
            if($calc){
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        if(isset($calculation->transportation) && $calculation->transportation == 1){
            $programs = $tariff['programs'][6];
            $base_coefficient = TariffMigrants::getTariffValue($programs['values'], $calculation->date_month, 1);
            $insurance_amount = TariffMigrants::getTariffValue($programs['amounts'], 1, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $insurance_amount);
            if($calc){
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }



        if($result->payment_total > 0){

            $contract->payment_total = $result->payment_total;
            $calculation_res->sum = $result->payment_total;
            $calculation_res->json = \GuzzleHttp\json_encode($result);
            $calculation_res->state_calc = 1;
            $calculation_res->messages = '';

        }else{
            $contract->insurance_amount = 0;
            $contract->payment_total = 0;
            $calculation_res->state_calc = 0;
            $calculation_res->sum = '';
            $calculation_res->json = null;
            $calculation_res->messages = 'Ошибка тарификатора';

        }


        $calculation_res->save();
        $contract->save();

        if($result->payment_total > 0){
            return (boolean)$calculation_res->createPaymentCalc();
        }




        return false;
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

        $result->payment_total = getFloatFormat($base_tariff);
        $result->payment_base_tariff = getPriceToTotalSum($result->insurance_amount, $result->payment_total);

        $result->discont = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['discount']);
        $result->allowances = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['allowances']);


        $result->payment_tariff = ($result->payment_base_tariff+$result->allowances) - $result->discont;
        $result->discont_total = getTotalSumToPrice($insurance_amount, $result->discont);
        $result->allowances_total = getTotalSumToPrice($insurance_amount, $result->allowances);

        if($result->payment_base_tariff != $result->payment_tariff){
            $result->payment_total = getTotalSumToPrice($result->insurance_amount, $result->payment_tariff);
        }

        return $result;
    }


    public static function getDiscontOrAllowancesProgram($contract, $calculation, $array)
    {
        $tariff = getFloatFormat(0);
        foreach ($array as $where){
            $tariff += self::getTariffDopWhere($contract, $calculation, (object)$where);
        }
        return $tariff;
    }



    public static function getTariffDopWhere($contract, $calculation, $where)
    {
        if(strlen($where->people_group) > 0 && (int)$where->people_group < $contract->contracts_insurers()->count()){
            return getFloatFormat($where->tariff);
        }

        if($calculation->programs_id == $where->people_program){
            if(strlen($where->people_from) > 0 || strlen($where->people_to) > 0){

                $insurers = $contract->contracts_insurers();

                if(strlen($where->people_from) > 0 && strlen($where->people_to) > 0){
                    $insurers->where('birthyear', '>=', (int)$where->people_from);
                    $insurers->where('birthyear', '<=', (int)$where->people_to);

                    if($insurers->get()->first()){
                        return getFloatFormat($where->tariff);
                    }

                    return 0;
                }else{
                    if(strlen($where->people_from) > 0){
                        $insurers->where('birthyear', '>=', (int)$where->people_from);

                        if($insurers->get()->first()){
                            return getFloatFormat($where->tariff);
                        }
                    }
                    if(strlen($where->people_to) > 0){
                        $insurers->where('birthyear', '<=', (int)$where->people_to);
                        if($insurers->get()->first()){
                            return getFloatFormat($where->tariff);
                        }
                    }
                }

            }
        }



        return 0;
    }


}