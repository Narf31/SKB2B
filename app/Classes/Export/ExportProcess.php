<?php

namespace App\Classes\Export;

class ExportProcess{

    private static $process = null;

    public $param = [];


    public static function getProcess(){
        if (self::$process === null) {
            $process = new self();
            self::$process = &$process;
        }
        return self::$process;
    }



    public function has($key){
        return isset($this->param[$key]);
    }

    public function get($key){
        return $this->has($key) ? $this->param[$key] : false;
    }

    public function set($key, $val){
        $this->param[$key] = $val;
    }
}