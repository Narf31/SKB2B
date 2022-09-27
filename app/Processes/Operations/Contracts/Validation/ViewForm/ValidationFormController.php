<?php

namespace App\Processes\Operations\Contracts\Validation\ViewForm;




use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;

class ValidationFormController {

    public static function main ($contract) {
        $result = (object)['state'=> true, 'msg' => 'Данные успешно сохранены!'];

        //if($contract->product->category->template != 'auto') return $result;

        $errors = [];

        //проверка условий договора
        $_temp_error = ValidationContract::main($contract);
        if(sizeof($_temp_error)) {
            $errors = array_merge($errors,$_temp_error);
            $_temp_error = null;
        }

        if($contract->product->category->template == 'auto'){
            //проверка контрагентов

            $_temp_error = self::defaultContractSubjects($contract);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }

            //проверка ТС

            $object = isset($contract->object_insurer_auto) ? $contract->object_insurer_auto : new ObjectInsurerAuto();
            $_temp_error = ValidationAuto::main($object,$contract->product->slug);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }


        if(count($errors)>0){
            $result->state = false;
            $result->msg = '';
            $result->errors = $errors;
        }

        return $result;
    }

    public static function defaultContractSubjects ($contract) {
        $errors=[];

        //проверка страхователя
        $_temp_error = ValidationSubject::main($contract->insurer);
        if(sizeof($_temp_error)) {
            $errors = array_merge($errors,$_temp_error);
            $_temp_error = null;
        }
        if((int)$contract->owner_id > 0 && $contract->owner_id!=$contract->insurer_id) {
            $_temp_error = ValidationSubject::main($contract->owner);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }

        if((int)$contract->beneficiar_id > 0 && $contract->beneficiar_id!=$contract->insurer_id){
            if($contract->beneficiar_id!=$contract->owner_id){
                $_temp_error = ValidationSubject::main($contract->beneficiar);
                if(sizeof($_temp_error)) {
                    $errors = array_merge($errors,$_temp_error);
                    $_temp_error = null;
                }
            }
        }

        foreach ($contract->contracts_insurers as $insurer){
            $_temp_error = ValidationSubject::driver($insurer);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }

        return $errors;
    }
}