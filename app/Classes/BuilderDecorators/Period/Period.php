<?php

namespace App\Classes\BuilderDecorators\Period;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Period{


    public static function field(Builder $builder, $field_name, Closure $closure){

        $field = new PeriodFieldFilter($field_name, $builder);

        $_field = &$field;

        $closure($_field);

        return $field->builder;
    }



}