<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\Data\PRF;
use App\Models\Directories\Products\Data\VZR;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\CurrencyValue;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Tariff\Settings\Product\TariffPrf;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use Illuminate\Support\Str;

class CalcPrf{



    public static function calc($contract){

        $calculation = $contract->data;
        $calculation_res = $contract->calculation;
        if(!$calculation_res){
            $calculation_res = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }

        $tariff = CalcPrf::getTariff($contract);

        if(!$tariff){
            return false;
        }

        $result = new \stdClass();
        $result->payment_total = getFloatFormat(0);
        $result->info = [];



        $programs = $tariff['programs'][0];
        $base_coefficient = TariffPrf::getTariffValue($programs['values'], $calculation->programs_id, $calculation->amount);

        if(getFloatFormat($base_coefficient) > 0){
            $dop_tarif = self::getDopTarife($tariff, $contract, $calculation);
            if(getFloatFormat($dop_tarif) > 0){
                $base_coefficient = getFloatFormat($base_coefficient) * $dop_tarif;
            }
        }

        $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->amount);

        if($calc){
            $contract->insurance_amount = $calc->insurance_amount;
            $result->payment_total += getFloatFormat($calc->payment_total);
            $result->info[] = $calc;
        }


        //Несчастный случай
        if((int)$calculation->ns_program > 0){
            $programs = $tariff['programs'][1];
            $base_coefficient = TariffPrf::getTariffValue($programs['values'], $calculation->ns_amount, $calculation->ns_program);

            if(getFloatFormat($base_coefficient) > 0){
                $dop_tarif = self::getDopTarife($tariff, $contract, $calculation);
                if(getFloatFormat($dop_tarif) > 0){
                    $base_coefficient = getFloatFormat($base_coefficient) * $dop_tarif;
                }
            }

            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->ns_amount);
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


    public static function getCalcProgram($contract, $calculation, $programs, $base_tariff, $price)
    {

        if(strlen($base_tariff) == 0){
            return null;
        }


        $count_day = (int)$calculation->count_day;


        $result = new \stdClass();
        $result->title = $programs['title'];

        $result->payment_tariff = getFloatFormat($base_tariff);

        $result->discont = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['discount']);
        $result->allowances = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['allowances']);

        $result->insurance_amount = getFloatFormat($price);

        $result->payment_tariff = ($result->payment_tariff+$result->allowances) - $result->discont;
        $result->discont_total =  getFloatFormat($count_day * $result->discont);
        $result->allowances_total = getFloatFormat($count_day * $result->allowances);
        $result->payment_total = getFloatFormat($count_day * $result->payment_tariff);

        return $result;
    }


    public static function getDiscontOrAllowancesProgram($contract, $calculation, $array)
    {

        $сountry = [];

        $tariff = getFloatFormat(0);
        foreach ($array as $where){

            $where = (object)$where;
            if(isset($where->сountry_id) && (int)$where->сountry_id > 0){
                $сountry[$where->сountry_id] = getFloatFormat($where->tariff);
            }else{
                $tariff += self::getTariffDopWhere($contract, $calculation, $where);
            }

        }


        return $tariff;
    }




    public static function getTariffDopWhere($contract, $calculation, $where)
    {
        if(strlen($where->people_group) > 0 && (int)$where->people_group < $contract->contracts_insurers()->count()){
            return getFloatFormat($where->tariff);
        }

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

        return 0;
    }


    public static function getDopTarife($tariff, $contract, $calculation)
    {
        $coefficient = 0;

        $programs = $tariff['programs'][2];

        $data = $calculation->toArray();
        foreach (PRF::OPTIONS as $key => $val){
            if(isset($data[$key]) && (int)$data[$key] == 1){
                $base_coefficient = TariffPrf::getTariffValue($programs['values'], 1, $key);
                if(getFloatFormat($base_coefficient) > 0){
                    $coefficient = getFloatFormat($coefficient) + getFloatFormat($base_coefficient);
                }
            }

        }

        return getFloatFormat($coefficient);
    }


}