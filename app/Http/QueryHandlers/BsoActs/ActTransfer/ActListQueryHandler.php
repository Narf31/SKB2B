<?php

namespace App\Http\QueryHandlers\BsoActs\ActTransfer;

use App\Http\QueryHandlers\QueryHandler;

class ActListQueryHandler extends QueryHandler{


    public function type_id($value){
        if((int)$value >= 0){
            $this->builder->where('type_id', (int)$value);
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