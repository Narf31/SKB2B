<?php

namespace App\Traits\Models;


use Illuminate\Support\Str;
use ReflectionObject;

trait ActiveConstTrait{

    function __call($method, $parameters){

        $no_ru_metod = rtrim($method,'u');
        $no_ru_metod = rtrim($no_ru_metod,'r');
        $no_ru_metod = rtrim($no_ru_metod,'_');

        if(!method_exists($this, $method) && $no_ru_metod != $method){

            $constants = (new ReflectionObject($this))->getConstants();
            $name_supposed = Str::upper($no_ru_metod);

            if(isset($constants[$name_supposed])){

                $column = count($parameters)>0 ? current($parameters) : $no_ru_metod . '_id';

                if(isset($constants[$name_supposed][$this->{$column}])){
                    return $constants[$name_supposed][$this->{$column}];
                }else{
                    return false;
                }
            }
        }

        return parent::__call($method, $parameters);

    }



}