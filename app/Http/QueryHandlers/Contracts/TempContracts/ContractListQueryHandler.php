<?php

namespace App\Http\QueryHandlers\Contracts\TempContracts;

use App\Classes\BuilderDecorators\Period\Period;
use App\Classes\BuilderDecorators\Period\PeriodFieldFilter;
use App\Http\QueryHandlers\QueryHandler;

class ContractListQueryHandler extends QueryHandler {

    public function statys($value) {
        $this->builder->whereIn('statys_id', [$value]);
    }

    public function sk($value) {
        if (is_array($value)) {
            $value = array_filter($value);
            if ($value) {
                $this->builder->whereIn('insurance_companies_id', $value);
            }
        } else {
            if ($value > 0) {
                $this->builder->where('insurance_companies_id', $value);
            }
        }
    }

    public function bso_title($value) {
        if ($value != "") {
            $this->builder->where('bso_title', 'like', "%{$value}%");
        }
    }

    public function product($value) {
        if ($value > 0) {
            $this->builder->where('product_id', $value);
        }
    }

    public function insurer($value) {
        if ($value != "") {
            $this->builder->whereIn('insurer_id', function($query) use ($value){
                $query->select('id')->from('subjects')->where('title', 'like', "%{$value}%");
            });
        }
    }

    public function agent($value) {
        if ($value > 0) {
            $this->builder->where('agent_id', $value);
        }
    }

    public function conclusion_date_from($value) {
        if ($value != '') {
            Period::field($this->builder, 'sign_date', function (PeriodFieldFilter $period) use ($value) {
                $period->from_date($value);
            });
        }
    }

    public function conclusion_date_to($value) {
        if ($value != '') {
            Period::field($this->builder, 'sign_date', function(PeriodFieldFilter $period) use ($value) {
                $period->to_date($value);
            });
        }
    }

    public function contract_type($value) {
        if ($value > 0) {
            $this->builder->where('kind_acceptance', $value);
        }
    }

}
