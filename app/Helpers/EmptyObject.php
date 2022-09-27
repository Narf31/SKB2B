<?php

namespace App\Helpers;

class EmptyObject{

    public function __get($name){
        return "";
    }
}