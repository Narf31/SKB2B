<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Models\Directories\Products\Data\Arbitration\ArbitrationCoefficient;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;

class CalcArbitration{



    public static function calc($contract){

        $data = $contract->data;
        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }


        $tariff = self::getTariff($contract);

        if(!$tariff){
            return self::setError($contract, $calculation, 'Ошибка тарификатора!');
        }

        $base_values = null;
        if(isset($tariff['tariff']) && isset($tariff['tariff']['programs']) && isset($tariff['tariff']['programs'][$contract->program->slug]) && isset($tariff['tariff']['programs'][$contract->program->slug]['values'])){
            $base_values = $tariff['tariff']['programs'][$contract->program->slug]['values'];
        }else{
            return self::setError($contract, $calculation, 'Ошибка тарификатора!');
        }

        $base_tariff = null;
        if($base_values){
            if($contract->program->slug == 'yearly'){
                $base_tariff = isset($base_values[1][$data->count_current_procedures])?$base_values[1][$data->count_current_procedures]:null;
            }
            if($contract->program->slug == 'procedural'){

                $begin_date = getDateFormatEn($contract->begin_date);
                $end_date = getDateFormatEn($contract->end_date);
                $_m = dt_diff($begin_date, $end_date);
                $base_tariff = isset($base_values[1][(int)$_m])?$base_values[1][(int)$_m]:null;

            }
        }else{
            return self::setError($contract, $calculation, 'Ошибка тарификатора!');
        }

        $original_tariff = 0;
        $manager_tariff = 0;

        $payment_total = 0;
        if($base_tariff){
            //Опребеляем КВ и повышающий тариф

            $coefficient = self::getCoefficient($contract, $calculation);


            $base_tariff = getFloatFormat($base_tariff)*getFloatFormat($coefficient->tariff);
            $original_tariff = $base_tariff;
            $manager_tariff = $original_tariff;

            $data->update([

                'base_tariff' => getFloatFormat($base_tariff),
                'base_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($base_tariff))),

                'original_tariff' => getFloatFormat($original_tariff),
                'original_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($original_tariff))),

                'manager_tariff' => getFloatFormat($manager_tariff),
                'manager_payment_total' => getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($manager_tariff))),

            ]);

            $payment_total = getFloatFormat(getTotalSumToPrice($contract->insurance_amount, getFloatFormat($manager_tariff)));


        }else{
            return self::setError($contract, $calculation, 'Ошибка тарификатора!');
        }


        if($payment_total > 0){
            $contract->payment_total = $payment_total;
            $calculation->insurance_amount = $contract->insurance_amount;
            $calculation->sum = $payment_total;
            $calculation->state_calc = 1;
            $calculation->json = \GuzzleHttp\json_encode(['tariff' => $manager_tariff]);
            $calculation->messages = '';
            $calculation->save();
            $contract->save();
        }


        if($contract->payment_total > 0){
            if(self::setGeneralSubjects($contract) == true){
                return (boolean)$calculation->createPaymentCalc();
            }
        }


        return true;
    }

    public static function setError($contract, $calculation, $error){
        $contract->payment_total = 0;
        $calculation->insurance_amount = 0;
        $calculation->state_calc = 0;
        $calculation->sum = 0;
        $calculation->json = null;
        $calculation->messages = $error;
        $calculation->save();
        return true;
    }


    public static function setGeneralSubjects($contract)
    {
        //Привязываем контрагентов
        if($contract->insurer_id > 0) {
            GeneralSubjectsInfo::getGeneralSubject($contract->insurer_id);
        }

        return MainScorings::scoring($contract);
    }

    public static function getTariff($contract){

        /*
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
        */

        //Возвращаем значения по умолчанию
        $special_settings = ProductsSpecialSsettings::where('product_id', $contract->product_id)->where('program_id', $contract->program_id)->get()->first();
        if($special_settings){
            if($special_settings->json && strlen($special_settings->json) > 0){
                return \GuzzleHttp\json_decode($special_settings->json, true);
            }
        }


        return null;
    }


    public static function getCoefficient($contract, $calculation)
    {
        $coefficient = new \stdClass();
        $coefficient->tariff = 1;
        $coefficient->title = '';

        $coefficients_group = ArbitrationCoefficient::where('product_id', $contract->product_id)
            ->where('program_id', $contract->program_id)
            ->orderBy('category', 'asc')
            ->orderBy('group', 'asc')
            ->orderBy('tarife', 'asc')
            ->get();

        $coefficients = Coefficients::getCoefficientsValue(Coefficients::getDataContract($contract), $coefficients_group);


        foreach ($coefficients as $coeff){


            $title = "{$coeff['title']}:".titleFloatFormat($coeff['tariff']).", ";
            $_temp = getFloatFormat($coeff['tariff']);

            $coefficient->tariff = $coefficient->tariff * $_temp;
            $coefficient->title .= $title;

        }



        if($coefficient->tariff == 0){
            /* $coefficient->tariff = 1;
             $coefficient->title = 'Коэф:1,00';*/
        }else{
            $coefficient->title = substr($coefficient->title, 0, -2);
        }


        return $coefficient;
    }



}