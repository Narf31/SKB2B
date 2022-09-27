<?php

namespace App\Processes\Operations\Contracts\Validation\ViewForm;




class ValidationAuto {

    public static function main ($object,$type) {
        $errors = self::commonAuto($object);

        if($type == 'osago') {
            $_temp_error = self::osago($object);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }

        if($type == 'kasko') {
            $_temp_error = self::kasko($object);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }

        if($type == 'gap') {
            $_temp_error = self::gap($object);
            if(sizeof($_temp_error)) {
                $errors = array_merge($errors,$_temp_error);
                $_temp_error = null;
            }
        }
        return $errors;
    }

    public static function commonAuto($object)
    {
        $errors = [];
        //проверка общих характеристик ТС

        if ($object->car_year > date("Y")) {
            $errors[] = 'Год выпуска не может быть больше текущего года!';
        }

        if ($object->vin != 'ОТСУТСТВУЕТ') {
            if(strlen($object->vin) < 15) {
                $errors[] = 'VIN указан не полностью!';
            }
            if (preg_match("/[^A-HJ-NPR-Z0-9]+/",$object->vin)) {
                $errors[] = 'VIN содержит недопустимые символы!';
            }
        }



        if ($object->docdate > date("Y-m-d \00:\00:\00")) {
            $errors[] = 'Дата выдачи документа ТС не может превышать текущую дату!';
        }

        if (date("Y", strtotime($object->docdate)) < $object->car_year) {
            $errors[] = 'Дата выдачи документа ТС не может предшествовать году выпуска ТС!';
        }

        if (!empty($object->dk_number)) {
            if (strlen($object->dk_number) < 15) {
                $errors[] = 'Номер диагностической карты указан не полностью!';
            }

            if ($object->dk_date_from > date("Y-m-d \00:\00:\00")) {
                $errors[] = 'Дата начала диагностической карты не может превышать текущую дату!';
            }

            if ($object->docdate > $object->dk_date_from) {
                $errors[] = 'Дата начала диагностической карты не может быть предшествовать дате выдачи документа ТС!';
            }

            if ($object->dk_date_from > $object->dk_date_to) {
                $errors[] = 'Дата окончания диагностической карты не может быть предшествовать дате начала!';
            }
        }

        return $errors;
    }

    public static function osago($object){
        $errors=[];

        if ($object->power < 1) {
            $errors[] = 'Мощность не может быть нулевой!';
        }

        return $errors;
    }

    public static function kasko($object){
        $errors=[];

        //if ($object->power < 1) {
        //    $errors[] = 'Мощность не может быть нулевой!';
        //}

        return $errors;
    }

    public static function gap($object){
        $errors=[];

        if((date('Y')-(int)$object->car_year) > 5){
            $errors[] = 'ТС до 5 лет';
        }

        if(getFloatFormat($object->mileage) >= 100000){
            $errors[] = 'ТС до 100 000 км.пробега';
        }


        return $errors;
    }


}