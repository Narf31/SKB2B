<?php
namespace App\Classes\Export\TagModels\Contracts;

use App\Classes\Export\TagModels\TagModel;

class TagContracts extends TagModel {


    public function apply(){

        $replace_arr = [];

        $contract = $this->builder->get()->first();


        $replace_arr['test_tag'] = 'Тееест  1233 444';
        $replace_arr['test_tag1'] = 'Тееест  1233 444 s gfdg ';
        $replace_arr['test_tag2'] = 'Тееест  1233 444 sdf  sdf';
        $replace_arr['test_tag3'] = 'Тееест  1233 444$$$^^&&';
        $replace_arr['test_tag4'] = 'Тееест sdhjkl 1233 444  f';
        $replace_arr['test_tag5'] = 'Тееестsdfsdf  1233 444';
        $replace_arr['test_tag6'] = 'Тееест  1233 44hgjhllk;lk4';


        return $replace_arr;
    }



    public static function doc(){

        $doc = [

        ];

        return $doc;
    }









}
