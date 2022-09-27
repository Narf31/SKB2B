<?php

namespace App\Http\QueryHandlers\BsoActs\ActsSK\Contracts;

use App\Http\QueryHandlers\QueryHandler;

class PaymentsListQueryHandler extends QueryHandler{

    public function state_id($value){
        if(is_array($value) && count($value)>0){
            $value = array_map('intval', $value);
            $this->builder->whereIn('statys_id', $value);
        }
    }

    public function product_id($value){
        if(is_array($value) && count($value)>0){
            $value = array_map('intval', $value);
            $this->builder->whereIn('bso_id', function($query) use ($value){
                $query->select('id')->from('bso_items')->whereIn('product_id', $value);
            });
        }
    }

    public function acts_sk_id($value){
        $value = (int)$value;
        if(in_array($value, [0,-1,-2])){
            $this->builder->where('acts_sk_id', $value);
        }
    }

}