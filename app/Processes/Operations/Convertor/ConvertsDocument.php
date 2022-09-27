<?php

namespace App\Processes\Operations\Convertor;


use Illuminate\Support\Str;

class ConvertsDocument{

    public static function word_to_pdf($source_file, $newtemppath){
        try {

            exec('unoconv -f pdf '.$source_file.' 2>&1', $output, $return);
            //dd(exec('unoconv -f pdf '.$source_file.' 2>&1', $output, $return));

        } catch (\Exception $e) {
            return false;
        }


        return is_file($newtemppath);
    }

    public static function xlsx_to_pdf($source_file, $newtemppath){
        try {

            exec('unoconv -f pdf '.$source_file.' 2>&1', $output, $return);


        } catch (\Exception $e) {
            return false;
        }


        return is_file($newtemppath);
    }

}