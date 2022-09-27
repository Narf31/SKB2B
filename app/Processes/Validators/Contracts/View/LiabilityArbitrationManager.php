<?php

namespace App\Processes\Validators\Contracts\View;


use App\Processes\Operations\Contracts\Products\CalcLiabilityArbitrationManager;
use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Tariff\Settings\Product\TariffLiabilityArbitrationManager;
use App\Processes\Tariff\Settings\Product\TariffMigrants;

class LiabilityArbitrationManager {




    public function __construct()
    {

    }


    public function getArrayConfig($contract, $data)
    {
        $disabled = [
            'tariff' => 0
        ];


        $tariff = CalcLiabilityArbitrationManager::getTariff($contract);

        $date_contract = (object)$data->contract;
        $arbitration = (object)$date_contract->liability_arbitration_manager;

        if((int)$arbitration->type_agr_id == 1){
            $programs = $tariff['programs'][0];
            $base_coefficient = TariffLiabilityArbitrationManager::getTariffValue($programs['values'], 1, (int)$arbitration->count_current_procedures);
            $disabled = [
                'tariff' => getFloatFormat($base_coefficient)
            ];
        }

        if((int)$arbitration->type_agr_id == 2){


            $begin_date = getDateFormatEn($date_contract->begin_date);
            $end_date = getDateFormatEn($date_contract->end_date);

            $_m = dt_diff($begin_date, $end_date);

            $programs = $tariff['programs'][1];

            $base_coefficient = TariffLiabilityArbitrationManager::getTariffValue($programs['values'], 1, (int)$_m);

            $disabled = [
                'tariff' => getFloatFormat($base_coefficient),
            ];
        }



        return $disabled;
    }


    public function checkTariff($values, $date_month)
    {



        return getFloatFormat(0);
    }

    public function getValidDataToMatching($contract)
    {

        $errors = [];
        $result = new \stdClass();
        $result->state = true;


        //Проверяем что все введено

        $arbitration = $contract->data;

        if(!$arbitration->cro){
            $result->state = false;
            $errors[] = 'Укажите СРО';
        }

        if(!$arbitration->general_insurer){
            $result->state = false;
            $errors[] = 'Укажите страхователя';
        }

        if(getFloatFormat($contract->insurance_amount) <= 0){
            $result->state = false;
            $errors[] = 'Укажите Страховую сумму';
        }

        if($arbitration->type_agr_id){

            if($arbitration->type_agr_id == 1){

                /*
                if($arbitration->general_insurer){

                    $count = count($arbitration->general_insurer->procedures);
                    if($arbitration->count_current_procedures == 2){
                        if($count < 1){
                            $result->state = false;
                            $errors[] = 'Укажите процедуры';
                        }
                    }

                    if($arbitration->count_current_procedures == 3){
                        if($count < 6){
                            $result->state = false;
                            $errors[] = 'Укажите процедуры';
                        }
                    }
                    if($arbitration->count_current_procedures == 4){
                        if($count < 11){
                            $result->state = false;
                            $errors[] = 'Укажите процедуры';
                        }
                    }
                    if($arbitration->count_current_procedures == 5){
                        if($count < 15){
                            $result->state = false;
                            $errors[] = 'Укажите процедуры';
                        }
                    }


                }
                */


            }

            if($arbitration->type_agr_id == 2){
                if((int)$arbitration->procedure_id <= 0){
                    $result->state = false;
                    $errors[] = 'Укажите процедуру';
                }
            }

        }


        //Если все ок
        if($result->state == true){
            $result->errors = ['errors' => []];
        }else{
            $result->errors = ['errors' => $errors];
        }


        return $result;
    }



}