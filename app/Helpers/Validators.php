<?php

namespace App\Helpers;

class Validators {

    /**
     * Валидация ИНН
     * Ошибки:
     * - ИНН может состоять только из 10 или 12 цифр
     * - Неправильное контрольное число
     * @param type $inn
     * @return boolean
     */
    public static function validateInn($inn) {
        $inn = (string) $inn;
        if (!$inn) {
            
        } elseif (preg_match('/[^0-9]/', $inn)) {
            
        } elseif (!in_array($inn_length = strlen($inn), [10, 12])) {
            
        } else {
            $check_digit = function($inn, $coefficients) {
                $n = 0;
                foreach ($coefficients as $i => $k) {
                    $n += $k * (int) $inn{$i};
                }
                return $n % 11 % 10;
            };
            switch ($inn_length) {
                case 10:
                    $n10 = $check_digit($inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
                    if ($n10 === (int) $inn{9}) {
                        return true;
                    }
                    break;
                case 12:
                    $n11 = $check_digit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
                    $n12 = $check_digit($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
                    if (($n11 === (int) $inn{10}) && ($n12 === (int) $inn{11})) {
                        return true;
                    }
                    break;
            }
        }
        return false;
    }

    /**
     * Валидация КПП
     * Ошибки:
     * - КПП может состоять только из 9 знаков (цифр или заглавных букв латинского алфавита от A до Z)
     * - Неправильный формат КПП
     * @param type $kpp
     * @return boolean
     */
    public static function validateKpp($kpp) {
        $kpp = (string) $kpp;
        if (!$kpp) {
            
        } elseif (strlen($kpp) !== 9) {
            
        } elseif (!preg_match('/^[0-9]{4}[0-9A-Z]{2}[0-9]{3}$/', $kpp)) {
            
        } else {
            return true;
        }
        return false;
    }

}
