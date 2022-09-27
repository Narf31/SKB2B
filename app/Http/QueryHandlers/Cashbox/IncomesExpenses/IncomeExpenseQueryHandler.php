<?php

namespace App\Http\QueryHandlers\Cashbox\IncomesExpenses;

use App\Http\QueryHandlers\QueryHandler;
use Carbon\Carbon;

class IncomeExpenseQueryHandler extends QueryHandler{

    public function category_id($value){
        if($value > 0){
            $this->builder->where('category_id', $value);
        }
    }

    public function type_id($value){
        if($value > 0){
            $this->builder->whereHas('category', function($query) use ($value){
                return $query->where('type', '=', $value);
            });
        }
    }

    public function status_id($value){
        if($value > 0){
            $this->builder->where('status_id', $value);
        }
    }
    public function payment_id($value){
        if($value >= 0){
            $this->builder->where('payment_type', $value);
        }
    }

    public function date_from($value){
        if($value > 0){
            $this->builder->where('date', '>', Carbon::parse($value)->startOfDay());
        }
    }

    public function date_to($value){
        if($value > 0){
            $this->builder->where('date', '<', Carbon::parse($value)->startOfDay());
        }
    }


}