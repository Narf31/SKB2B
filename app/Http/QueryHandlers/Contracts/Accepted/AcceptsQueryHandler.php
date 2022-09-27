<?php

namespace App\Http\QueryHandlers\Contracts\Accepted;

use App\Classes\BuilderDecorators\Period\Period;
use App\Classes\BuilderDecorators\Period\PeriodFieldFilter;
use App\Http\QueryHandlers\QueryHandler;
use Illuminate\Support\Str;

class AcceptsQueryHandler extends QueryHandler {

    public function bso_title($value){
        if(!empty($value)){

            $this->builder->whereIn('contract_id', function($q) use ($value){

                $q->select('id')->from('contracts')->where('bso_title', 'like', "%{$value}%");

            });

        }
    }

    public function org_id($value){
        if((int)$value>0){

            $this->builder->whereIn('contract_id', function($q) use ($value){

                $q->select('id')->from('contracts')->whereIn('bso_supplier_id', function($_q) use ($value){

                    $_q->select('id')->from('bso_suppliers')->where('purpose_org_id', (int)$value);

                });

            });

        }
    }

    public function bso_supplier_id($value){
        if((int)$value>0){

            $this->builder->whereIn('contract_id', function($q) use ($value){

                $q->select('id')->from('contracts')->where('bso_supplier_id', (int)$value);

            });

        }
    }

    public function type_id($value){
        if((int)$value >= 0){

            $this->builder->where('kind_acceptance', (int)$value);

        }
    }

    public function manager_id($value){
        if((int)$value>0){

            $this->builder->whereIn('contract_id', function($q) use ($value){

                $q->select('id')->from('contracts')->where('manager_id', (int)$value);

            });

        }
    }

    public function date_from($value){
        if(!empty($value)){

            Period::field($this->builder, 'accept_date', function(PeriodFieldFilter $pff) use ($value){

                $pff->from_date($value);

            });

        }
    }

    public function date_to($value){
        if(!empty($value)) {

            Period::field($this->builder, 'accept_date', function (PeriodFieldFilter $pff) use ($value) {

                $pff->to_date($value);

            });

        }
    }
}
