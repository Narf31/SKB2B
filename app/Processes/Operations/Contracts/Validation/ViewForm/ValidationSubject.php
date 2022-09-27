<?php

namespace App\Processes\Operations\Contracts\Validation\ViewForm;




class ValidationSubject {

    public static function main ($subject) {

        if($subject->type == 0) return self::fl($subject);
        if($subject->type == 1 || $subject->type == 3) return self::ul($subject);

    }

    public static function fl($subject){
        $errors=[];
        if($subject->birthdate > date("Y-m-d \00:\00:\00") ){
            $errors[] = 'Дата рождения не может превышать текущую дату!';
        }

        if($subject->birthdate > $subject->doc_date ){
            $errors[] = 'Дата выдачи документа не может предшествовать дате рождения!';
        }
        return $errors;
    }

    public static function ul($subject){
        $errors=[];

        return $errors;
    }

    public static function driver($insurer){
        $errors=[];

        if($insurer->birthdate > date("Y-m-d \00:\00:\00", strtotime(date("Y-m-d")." -14 years")) ){
            $errors[] = 'Некорректная дата рождения водителя, возраст не может быть меньше 14 лет!';
        }

        if($insurer->exp_date < date("Y-m-d \00:\00:\00", strtotime($insurer->birthdate." +14 years")) ){
            $errors[] = 'Некорректная дата начала стажа, возраст не может быть меньше 14 лет!';
        }

        if($insurer->exp_date > date("Y-m-d \00:\00:\00") ){
            $errors[] = 'Дата начала стажа не может превышать текущую дату!';
        }

        if($insurer->doc_date > date("Y-m-d \00:\00:\00") ){
            $errors[] = 'Дата выдачи В.У. не может превышать текущую дату!';
        }

        if($insurer->exp_date > $insurer->doc_date ){
            $errors[] = 'Дата выдачи В.У. не может предшествовать дате начала стажа!';
        }

        return $errors;
    }
}