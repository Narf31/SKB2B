<?php

namespace App\Http\QueryHandlers\BsoActs\ActsSK;

use App\Http\QueryHandlers\QueryHandler;

class ActsListQueryHandler extends QueryHandler{

    public function month($value){
        if($value > 0){
            $this->builder->where('report_month', (int)$value);
        }
    }

    public function year($value){
        if($value > 0){
            $this->builder->where('report_year', (int)$value);
        }
    }

    public function type($value){
        if($value >= 0){
            $this->builder->where('type_id', (int)$value);
        }
    }

    public function title($value){
        if(!empty($value)){
            $this->builder->where('title', 'like', "%{$value}%");
        }
    }

}