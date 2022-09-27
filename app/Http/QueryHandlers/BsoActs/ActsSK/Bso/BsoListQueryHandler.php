<?php

namespace App\Http\QueryHandlers\BsoActs\ActsSK\Bso;

use App\Http\QueryHandlers\QueryHandler;

class BsoListQueryHandler extends QueryHandler{

    public function state_id($value){
        if(is_array($value) && count($value)>0){
            $value = array_map('intval', $value);
            $this->builder->whereIn('state_id', $value);
        }
    }

    public function product_id($value){
        if(is_array($value) && count($value)>0){
            $value = array_map('intval', $value);
            $this->builder->whereIn('product_id', $value);
        }
    }

    public function acts_sk_id($value){
        $value = (int)$value;
        if(in_array($value, [0,-1,-2])){
            $this->builder->where('acts_sk_id', $value);
        }
    }

}