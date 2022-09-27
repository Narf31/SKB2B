<?php

namespace App\Http\QueryHandlers\Finance\Debts;

use App\Http\QueryHandlers\QueryHandler;

class AgentQueryHandler extends QueryHandler{

    public function agent_id($value){
        if($value>0){
            $this->builder->where('id', '=',$value);
        }
    }

    public function parent_agent_id($value){
        if($value>0){
            $this->builder->where('parent_id', '=', $value);
        }
    }

}