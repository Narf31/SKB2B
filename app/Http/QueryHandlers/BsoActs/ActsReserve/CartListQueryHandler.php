<?php

namespace App\Http\QueryHandlers\BsoActs\ActsReserve;

use App\Http\QueryHandlers\QueryHandler;

class CartListQueryHandler extends QueryHandler{


    public function bso_cart_type($value){
        if((int)$value > 0){
            $this->builder->where('bso_cart_type', (int)$value);
        }
    }

    public function user_id_from($value){
        if((int)$value > 0){
            $this->builder->where('user_id_from', (int)$value);
        }
    }

    public function user_id_to($value){
        if((int)$value > 0){
            $this->builder->where('user_id_to', (int)$value);
        }
    }

    public function bso_manager_id($value){
        if((int)$value > 0){
            $this->builder->where('bso_manager_id', (int)$value);
        }
    }

    public function tp_id($value){
        if((int)$value > 0){
            $this->builder->where('tp_id', (int)$value);
        }
    }

}