<?php
namespace App\Classes\Export\TagModels;


use App\Classes\Export\ExportProcess;
use Illuminate\Database\Eloquent\Builder;

class TagModel{

    protected $builder;
    public $replace_arr;

    public function __construct(Builder $builder){

        $process = ExportProcess::getProcess();
        $process->param[$this->get_name()] = $this;

        $this->builder = $builder;
        $this->replace_arr = $this->apply();


    }

    public function apply(){
        return [];
    }

    public static function doc(){
        return [];
    }

    public function get_result(){
        return $this->replace_arr;
    }


    public function get_name(){
        $path = explode('\\', get_class($this));
        return array_pop($path);
    }


    public static function process(){
        return ExportProcess::getProcess();
    }

}