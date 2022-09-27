<?php

namespace App\Http\QueryHandlers\Analitics\Common;

use App\Http\QueryHandlers\QueryHandler;
use App\Models\Contracts\Payments;

class PaymentsQueryHandler extends QueryHandler{


    //Период
    public function payment_date_type_id($value)
    {
        if (request('date_from') || request('date_to')) {
            switch ($value) {
                case 1:
                    if (!empty(request('date_from'))) {
                        $this->builder->whereDate('payments.payment_data', '>=', date('Y-m-d 00:00:00', strtotime(request('date_from'))));
                    }
                    if (!empty(request('date_to'))) {
                        $this->builder->whereDate('payments.payment_data', '<=', date('Y-m-d 23:59:59', strtotime(request('date_to'))));
                    }
                    //$this->builder->orderBy('payments.payment_data', 'desc');
                    break;
                case 2:
                    if (!empty(request('date_from'))) {
                        $this->builder->whereHas('contract', function ($query) {
                            $query->whereDate('begin_date', '>=', date('Y-m-d 00:00:00', strtotime(request('date_from'))));
                        });
                    }
                    if (!empty(request('date_to'))) {
                        $this->builder->whereHas('contract', function ($query) {
                            $query->whereDate('begin_date', '<=', date('Y-m-d 23:59:59', strtotime(request('date_to'))));
                        });
                    }
                   // $this->builder->orderBy('contracts.sign_date', 'desc');
                    break;
                case 3:
                    if (!empty(request('date_from'))) {
                        $this->builder->whereHas('contract', function ($query) {
                            $query->whereDate('sign_date', '>=', date('Y-m-d 00:00:00', strtotime(request('date_from'))));
                        });
                    }
                    if (!empty(request('date_to'))) {
                        $this->builder->whereHas('contract', function ($query) {
                            $query->whereDate('sign_date', '<=', date('Y-m-d 23:59:59', strtotime(request('date_to'))));
                        });
                    }
                    // $this->builder->orderBy('contracts.sign_date', 'desc');
                    break;

            }
        }
    }




    //Статус оплаты
    public function payment_status_id($value)
    {
        if ((int)$value > -1) {
            $this->builder->where('payments.statys_id', '=', $value);
        }
    }

    //Транзакции
    public function is_deleted($value)
    {
        if ((int)$value > -1) {
            $this->builder->where('payments.is_deleted', '=', $value);
        }
    }

    //Тип транзакции
    public function payment_type_id($value)
    {
        if ($value > -1) {
            $this->builder->where('payments.type_id', '=', $value);
        }
    }

    //Статус договора
    public function contract_status_id($value)
    {
        if ($value != 0) {
            $this->builder->where('contracts.statys_id', '=', $value);
        }else{
            $this->builder->where('contracts.statys_id', '!=', 0);
        }
    }

    //Тип оплаты
    public function payment_type($value)
    {
        if ($value > -1) {
            $this->builder->where('payments.payment_type', '=', $value);
        }
    }

    //Поток оплаты
    public function payment_flow($value)
    {
        if ($value > -1) {
            $this->builder->where('payments.payment_flow', '=', $value);
        }
    }

    //Условие продажи
    public function contract_sales_condition($value)
    {
        if ($value > -1) {
            $this->builder->where('contracts.sales_condition', '=', $value);
        }
    }

    //Точка продаж
    public function point_sale($value)
    {
        if ($value > -1) {
            $this->builder->where('points_sale.id', $value);
        }
    }



    public function user_id($value)
    {
        if ((int)$value > 0) {
            switch (request('user_type')) {
                case 1://Агент
                    $this->builder->where('payments.agent_parent_id', '=', $value);
                    break;
                case 2://Менеджер
                    $this->builder->where('payments.agent_curator_id', '=', $value);
                    break;
                case 3://Продавец
                    $this->builder->where('payments.agent_id', '=', $value);
                    break;
            }
        }
    }


    public function agent_id($value)
    {
        if ((int)$value > 0) {
            $this->builder->where('payments.agent_id', '=', $value);
        }
    }


    public function product_id($value)
    {
        if (isset($value)){
            if(is_array($value) && count($value) > 0) {
                $this->builder->whereIn('contracts.product_id', $value);
            }elseif((int)$value > 0){
                $this->builder->where('contracts.product_id', $value);
            }
        }
    }

    public function org_ids($value)
    {
        if ((int)$value > 0) {
            $this->builder->whereIn('bso_items.org_id', $value);
        }
    }

    public function department_ids($value)
    {
        if (is_array($value) && count($value) > 0) {

            $this->builder->whereIn('departments.id', $value);

        }
    }

    public function contract_bso_title($value)
    {
        if (strlen($value) > 0) {
            $this->builder->where('bso_items.bso_title',  'like', "%{$value}%");
        }
    }

    public function contract_insurer($value)
    {
        if (strlen($value) > 0) {
            $this->builder->where('subjects.title',  'like', "%{$value}%");
        }
    }




}