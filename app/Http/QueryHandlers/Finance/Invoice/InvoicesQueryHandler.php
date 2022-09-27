<?php

namespace App\Http\QueryHandlers\Finance\Invoice;

use App\Http\QueryHandlers\QueryHandler;
use App\Models\Contracts\Payments;

class InvoicesQueryHandler extends QueryHandler{


    public function agent_id($value){
        $this->builder->where('agent_id', '=', $value);
    }

    public function type($value){
        $this->builder->where('type', '=', $value);

    }

    public function status_id($value){
        $this->builder->where('status_id', '=', $value);
    }






}