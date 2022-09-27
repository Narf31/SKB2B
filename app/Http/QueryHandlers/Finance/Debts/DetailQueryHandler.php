<?php

namespace App\Http\QueryHandlers\Finance\Debts;

use App\Http\QueryHandlers\QueryHandler;
use App\Models\Contracts\Debts;

class DetailQueryHandler extends QueryHandler{

    public function overdue($value){
        Debts::whereOverdue($value, $this->builder);
    }

    public function type_ru($value){
        Debts::whereType($value, $this->builder);
    }

}