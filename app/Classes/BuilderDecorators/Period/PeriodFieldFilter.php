<?php

namespace App\Classes\BuilderDecorators\Period;


use Illuminate\Database\Eloquent\Builder;

class PeriodFieldFilter{

    public $field_name;
    public $builder;

    public function __construct($field_name, Builder $builder){
        $this->field_name = $field_name;
        $this->builder = $builder;
    }

    public function from_date($str_date){
        $this->builder = $this->builder->whereDate($this->field_name, '>=', date('Y-m-d H:i:s', strtotime($str_date)));
    }

    public function to_date($str_date){
        $this->builder = $this->builder->whereDate($this->field_name, '<=', date('Y-m-d H:i:s', strtotime($str_date)));
    }

    public function year($year){
        $this->builder = $this->builder->whereRaw("year({$this->field_name}) = ?", [(int)$year]);
    }

    public function month($month){
        $this->builder = $this->builder->whereRaw("month({$this->field_name}) = ?", [(int)$month]);
    }

    public function day($day){
        $this->builder = $this->builder->whereRaw("day({$this->field_name}) = ?", [(int)$day]);
    }



}