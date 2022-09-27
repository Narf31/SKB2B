<?php

namespace App\Http\QueryHandlers\Analitics\Total;

use App\Classes\BuilderDecorators\Period\Period;
use App\Classes\BuilderDecorators\Period\PeriodFieldFilter;
use App\Http\QueryHandlers\QueryHandler;

class TurnQueryFilter extends QueryHandler{


    public function year($value){
        Period::field($this->builder, 'payment_data', function(PeriodFieldFilter $filter) use ($value){
            $filter->year($value);
        });
    }

    public function month($value){
        Period::field($this->builder, 'payment_data', function(PeriodFieldFilter $filter) use ($value){
            $filter->month($value);
        });
    }

    public function from($value){
        Period::field($this->builder, 'payment_data', function(PeriodFieldFilter $filter) use ($value){
            $filter->from_date($value);
        });
    }

    public function to($value){
        Period::field($this->builder, 'payment_data', function(PeriodFieldFilter $filter) use ($value){
            $filter->to_date($value);
        });
    }
}