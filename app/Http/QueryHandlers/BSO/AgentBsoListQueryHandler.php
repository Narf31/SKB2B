<?php

namespace App\Http\QueryHandlers\BSO;

use App\Http\QueryHandlers\QueryHandler;

class AgentBsoListQueryHandler extends QueryHandler{



    public function agent_id($value){
        if($value > 0){
            $this->builder->where('agent_id', $value);
        }
    }

    public function point_sale_id($value){
        if($value>0){
            $this->builder->where('point_sale_id', $value);
        }
    }


    public function type_bso_id($value){
        if($value>0){
            $this->builder->where('type_bso_id', $value);
        }

    }

    public function nop_id($value){
        if($value>0){
            $this->builder->whereIn('agent_id', function($query) use ($value){
                $query->select('id')->from('users')->where('curator_id', $value);
            });
        }
    }


    public function types($value){
        switch($value){
            case 'bso_in_30':
                $this->builder->where('time_create', '<=', date('Y-m-d H:i:s', time()-60*60*24*30));
                break;

            case 'bso_in_90':
                $this->builder->where('time_create', '<=', date('Y-m-d H:i:s', time()-60*60*24*90));
                break;
        }
    }
}