<?php

namespace App\Http\QueryHandlers\Cashbox\Invoice;

use App\Classes\BuilderDecorators\Period\Period;
use App\Classes\BuilderDecorators\Period\PeriodFieldFilter;
use App\Http\QueryHandlers\QueryHandler;

class InvoicesQueryHandler extends QueryHandler{


    public function agent_id($value){
        if($value > 0){
            $this->builder->where('agent_id', (int)$value);
        }
    }

    public function type($value){
        if($value>0){

            if(!request('invoice_number')){
                $this->builder->where('payment_method_id', $value);
            }

        }
    }

    public function status($value){
        if((int)$value != -1){
            $this->builder->where('status_id', (int)$value);
        }
    }

    public function date_from($value){
        if ($value != '') {
            Period::field($this->builder, 'created_at', function (PeriodFieldFilter $period) use ($value) {
                $period->from_date($value);
            });
        }
    }

    public function date_to($value){
        if ($value != '') {
            Period::field($this->builder, 'created_at', function (PeriodFieldFilter $period) use ($value) {
                $period->to_date($value);
            });
        }
    }


}