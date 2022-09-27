<?php

namespace App\Http\QueryHandlers\Finance\Invoice;

use App\Http\QueryHandlers\QueryHandler;
use App\Models\Contracts\Payments;

class PaymentsQueryHandler extends QueryHandler{


    public function agent_id($value){
        $this->builder->where('agent_id', '=', $value);
    }








}