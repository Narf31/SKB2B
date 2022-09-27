<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\Data\VZR;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\CurrencyValue;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use Illuminate\Support\Str;

class CalcVzr{



    public static function calc($contract){

        $calculation = $contract->data;
        $calculation_res = $contract->calculation;
        if(!$calculation_res){
            $calculation_res = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }

        $tariff = CalcVzr::getTariff($contract);

        if(!$tariff){
            return false;
        }

        $result = new \stdClass();
        $result->payment_curr_total = getFloatFormat(0);
        $result->payment_total = getFloatFormat(0);
        $result->info = [];

        //type_agr_id = 1 Однократный = $tariff['programs'][0]
        if($calculation->type_agr_id == 1){
            $programs = $tariff['programs'][0];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->programs_id, $calculation->amount);

            if(getFloatFormat($base_coefficient) > 0){
                $base_coefficient = getFloatFormat($base_coefficient) + self::getDopTarife($tariff, $contract, $calculation);
            }


            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->amount);

            if($calc){
                $contract->insurance_amount = $calc->insurance_amount;
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }

        }
        //type_agr_id = 2 Годовой = $tariff['programs'][1]
        if($calculation->type_agr_id == 2){
            $programs = $tariff['programs'][1];

            $base_payment_total = TariffVzr::getTariffValue($programs['values'], $calculation->amount, $calculation->day_to);
            $calc = self::getCalcProgramYear($contract, $calculation, $programs, $base_payment_total, $calculation->amount);

            if($calc){
                $contract->insurance_amount = $calc->insurance_amount;
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }


            /*

            $calc = new \stdClass();
            $calc->title = $programs['title'];

            $calc->payment_curr_total = TariffVzr::getTariffValue($programs['values'], $calculation->amount, $calculation->day_to);

            $calc->discont = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['discount']);
            $calc->allowances = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['allowances']);

            $result->insurance_curr_amount = getFloatFormat($calculation->amount);
            $result->insurance_amount = getFloatFormat($result->insurance_curr_amount)*getFloatFormat($currency->amount);

            $result->payment_tariff = ($result->payment_tariff+$result->allowances) - $result->discont;
            $result->payment_curr_total = getTotalSumToPrice($price, $result->payment_tariff);
            $result->discont_total = getTotalSumToPrice($price, $result->discont);
            $result->allowances_total = getTotalSumToPrice($price, $result->allowances);

            $result->payment_total = getFloatFormat($result->payment_curr_total)*getFloatFormat($currency->amount);



            $base_payment_curr_total = TariffVzr::getTariffValue($programs['values'], $calculation->amount, $calculation->day_to);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->amount);
            if($calc){
                $contract->insurance_amount = $calc->insurance_amount;
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }

            */
        }

        //Задержка рейса
        if((int)$calculation->flight_delay_program > 0){
            $programs = $tariff['programs'][2];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->flight_delay_program, $calculation->flight_delay_amount);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->flight_delay_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }


        //Опоздание на рейс
        if((int)$calculation->missed_flight_program > 0){
            $programs = $tariff['programs'][3];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->missed_flight_amount, $calculation->missed_flight_program);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->missed_flight_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        //Багаж
        if((int)$calculation->baggage_program > 0){
            $programs = $tariff['programs'][4];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->baggage_amount, $calculation->baggage_program);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->baggage_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        //Отмена поездки
        if((int)$calculation->cancel_trip_program > 0){
            $programs = $tariff['programs'][5];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->cancel_trip_program, 1);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        //Гражданская ответственность
        if((int)$calculation->сivil_responsibility_program > 0){
            $programs = $tariff['programs'][6];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->сivil_responsibility_amount, $calculation->сivil_responsibility_program);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->сivil_responsibility_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        //Юридическая помощь
        if((int)$calculation->legal_aid_program > 0){
            $programs = $tariff['programs'][7];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->legal_aid_amount, $calculation->legal_aid_program);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->legal_aid_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        //Отмена экскурсии
        if((int)$calculation->cancel_tour_program > 0){
            $programs = $tariff['programs'][8];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->cancel_tour_amount, $calculation->cancel_tour_program);
            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->cancel_tour_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
                $result->payment_total += getFloatFormat($calc->payment_total);
                $result->info[] = $calc;
            }
        }

        //Несчастный случай
        if((int)$calculation->ns_program > 0){
            $programs = $tariff['programs'][9];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], $calculation->ns_amount, $calculation->ns_program);

            if(getFloatFormat($base_coefficient) > 0){
                $dop_tarif = self::getDopTarife($tariff, $contract, $calculation);
                if(getFloatFormat($dop_tarif) > 0){
                    $base_coefficient = getFloatFormat($base_coefficient) * $dop_tarif;
                }
            }

            $calc = self::getCalcProgram($contract, $calculation, $programs, $base_coefficient, $calculation->ns_amount);
            if($calc){
                $result->payment_curr_total += getFloatFormat($calc->payment_curr_total);
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


    public static function getCalcProgramYear($contract, $calculation, $programs, $base_payment_total, $price)
    {
        if(strlen($base_payment_total) == 0){
            return null;
        }

        $currency = CurrencyValue::getActualValue($calculation->currency_id, getDateFormatEn($contract->sign_date));
        if(!$currency){
            return null;
        }

        $result = new \stdClass();
        $result->title = $programs['title'];

        $result->insurance_curr_amount = getFloatFormat($price);
        $result->insurance_amount = getFloatFormat($result->insurance_curr_amount)*getFloatFormat($currency->amount);



        $result->payment_curr_total = getFloatFormat($base_payment_total);
        $result->payment_tariff = getPriceToTotalSum($result->insurance_curr_amount, $result->payment_curr_total);

        $result->discont = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['discount']);
        $result->allowances = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['allowances']);
        $result->payment_tariff = ($result->payment_tariff+$result->allowances) - $result->discont;

        $result->discont_total = getTotalSumToPrice($price, $result->discont);
        $result->allowances_total = getTotalSumToPrice($price, $result->allowances);

        $result->payment_curr_total = getTotalSumToPrice($price, $result->payment_tariff);

        $result->payment_total = getFloatFormat($result->payment_curr_total)*getFloatFormat($currency->amount);

        return $result;
    }

    public static function getCalcProgram($contract, $calculation, $programs, $base_tariff, $price)
    {

        if(strlen($base_tariff) == 0){
            return null;
        }


        $currency = CurrencyValue::getActualValue($calculation->currency_id, getDateFormatEn($contract->sign_date));
        if(!$currency){
            return null;
        }

        $count_day = (int)$calculation->count_day;


        $result = new \stdClass();
        $result->title = $programs['title'];

        $result->payment_tariff = getFloatFormat($base_tariff);



        $result->discont = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['discount']);
        $result->allowances = self::getDiscontOrAllowancesProgram($contract, $calculation, $programs['allowances']);

        $result->insurance_curr_amount = getFloatFormat($price);
        $result->insurance_amount = getFloatFormat($result->insurance_curr_amount)*getFloatFormat($currency->amount);


        $result->payment_tariff = ($result->payment_tariff+$result->allowances) - $result->discont;
        $result->payment_curr_total = getFloatFormat($count_day * $result->payment_tariff);
        $result->discont_total =  getFloatFormat($count_day * $result->discont);
        $result->allowances_total = getFloatFormat($count_day * $result->allowances);//getTotalSumToPrice($price, $result->allowances);
        $result->payment_total = getFloatFormat($result->payment_curr_total)*getFloatFormat($currency->amount);



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


        if($сountry){
            arsort($сountry);
            if(strlen($calculation->сountry_json) > 3){
                $сountry_json = \GuzzleHttp\json_decode($calculation->сountry_json);
                $tariff += self::_getTariffCountry($сountry, $сountry_json);
            }
        }



        return $tariff;
    }

    public static function _getTariffCountry($сountry, $сountry_json)
    {
        foreach ($сountry as $key => $_c){
            foreach ($сountry_json as $_cj){
                if((int)$_cj == (int)$key){
                    return $_c;
                }
            }
        }
        return getFloatFormat(0);
    }


    public static function getTariffDopWhere($contract, $calculation, $where)
    {
        if((int)$where->сountry_id > 0){

            /*
            if($where->сountry_id == $calculation->сountry_id)
            {
                return getFloatFormat($where->tariff);
            }
            */

            return getFloatFormat(0);
        }else{
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

        }


        return 0;
    }


    public static function getDopTarife($tariff, $contract, $calculation)
    {
        $coefficient = 0;



        if((int)$calculation->sport_id > 0){
            $programs = $tariff['programs'][10];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], (int)$calculation->sport_id, 1);
            if(getFloatFormat($base_coefficient) > 0){
                $coefficient = getFloatFormat($coefficient) + getFloatFormat($base_coefficient);
            }
        }

        if((int)$calculation->profession_id > 0){
            $programs = $tariff['programs'][11];
            $base_coefficient = TariffVzr::getTariffValue($programs['values'], (int)$calculation->profession_id, 1);
            if(getFloatFormat($base_coefficient) > 0){
                $coefficient = getFloatFormat($coefficient) + getFloatFormat($base_coefficient);
            }
        }


        $programs = $tariff['programs'][12];

        $data = $calculation->toArray();
        foreach (VZR::OPTIONS as $key => $val){
            if(isset($data[$key]) && (int)$data[$key] == 1){
                $base_coefficient = TariffVzr::getTariffValue($programs['values'], 1, $key);
                if(getFloatFormat($base_coefficient) > 0){
                    $coefficient = getFloatFormat($coefficient) + getFloatFormat($base_coefficient);
                }
            }

        }

        return getFloatFormat($coefficient);
    }


}