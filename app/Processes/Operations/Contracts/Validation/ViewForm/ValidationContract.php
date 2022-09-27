<?php

namespace App\Processes\Operations\Contracts\Validation\ViewForm;




class ValidationContract {

    public static function main ($contract) {
        $errors = self::commonContract($contract);

        if($contract->product->slug == 'osago') {
            $_temp_error = self::osago($contract);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }

        if($contract->product->slug == 'kasko') {
            $_temp_error = self::kasko($contract);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }
        return $errors;
    }


    public static function commonContract($contract){
        $errors=[];
        //проверка общих условий договора

        if(date("Y-m-d \00:\00:\00") > $contract->begin_date){
            $errors[] = 'Дата начала договора не может быть задним числом!';
        }

        if($contract->begin_date > $contract->end_date){
            $errors[] = 'Дата окончания договора не может быть предшествовать дате начала!';
        }

        return $errors;
    }

    public static function osago($contract){
        $errors=[];

        if(isset($contract->data->period_beg2)){
            if((!isset($contract->data->period_end2)) ||
                (isset($contract->data->period_beg3) && !isset($contract->data->period_end3))){
                $errors[] = 'Не указана дата окончания периода';
            }

            if( $contract->data->period_beg1 >= $contract->data->period_end1 ||
                $contract->data->period_beg2 >= $contract->data->period_end2 ||
                (isset($contract->data->period_beg3) && $contract->data->period_beg3 >= $contract->data->period_end3 )){
                $errors[] = 'Даты окончания периодов не могут предшествовать датам начала!';
            }

            if( $contract->data->period_beg2 < $contract->data->period_end1 ||
                (isset($contract->data->period_beg3) && $contract->data->period_beg3 < $contract->data->period_end2)){
                $errors[] = 'Даты периодов не могут пересекаться!';
            }
            if((isset($contract->data->period_beg3) && $contract->data->period_end3 > $contract->end_date) ||
                (!isset($contract->data->period_beg3) && $contract->data->period_end2 > $contract->end_date) ||
                $contract->data->period_beg1 < $contract->begin_date){
                $errors[] = 'Даты периодов не могут превышать дат договора!';
            }
        }

        return $errors;
    }

    public static function kasko($contract){
        $errors=[];

        if(isset($contract->data->insurance_term)){
            if($contract->data->insurance_term != 12){
                if($contract->installment_algorithms){
                    $quantity = (int)$contract->installment_algorithms->info->quantity;

                    if($contract->data->insurance_term == 6){
                        if($quantity == 2 || $quantity == 1){

                        }else{
                            $errors[] = 'Алгоритм расрочки 100% или 50%/50% на не годовой договор!';
                        }
                    }else{
                        $errors[] = 'Алгоритм расрочки 100% на не годовой договор!';
                    }

                }
            }
        }

        return $errors;
    }
}