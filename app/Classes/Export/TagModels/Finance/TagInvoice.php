<?php

namespace App\Classes\Export\TagModels\Finance;


use App\Classes\Export\TagModels\Contracts\TagPayments;
use App\Classes\Export\TagModels\TagModel;

class TagInvoice extends TagModel{



    public function apply(){

        $replace_arr = [];

        if($invoice = $this->builder->first()){


            $fp = $invoice->payments()->first();

            $replace_arr['number'] = $invoice->id;
            $replace_arr['current_date'] = date('d.m.Y');
            $replace_arr['type'] = $invoice->types_ru('type');
            $replace_arr['org'] = $invoice->org ? $invoice->org->title : "";
            $replace_arr['created_at'] = setDateTimeFormatRu($invoice->created_at);
            $replace_arr['agent'] = $invoice->agent ? $invoice->agent->name : "";
            $replace_arr['courier'] = $this->getCourier();
            $replace_arr['manager'] =  $fp && $fp->manager ? $fp->manager->name : "";
            $replace_arr['create_user'] =  auth()->user()->name;


            $payment_tags = (new TagPayments($invoice->payments()->getQuery()))->apply();

            $replace_arr = array_merge($replace_arr, $payment_tags);
        }

        //dd($replace_arr);
        return $replace_arr;

    }


    public static function doc(){

        $payment_doc = TagPayments::doc();
        $doc = [
            'Теги счёта' => [
                'type' => 'Тип счёта',
                'number' => 'Номер счёта',
                'org' => 'Организация',
                'created_at' => 'Дата выставления',
                'agent' => 'Агент',
                'manager' => 'Менеджер',
                'courier' => 'Курьер',
                'current_date' => 'Текущая дата',
                'create_user' => 'Создал',
            ]
        ];

        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }

        return array_merge($payment_doc, $doc);

    }

    private function getCourier()
    {
        $courier = "";

        $invoice = $this->builder->first();
        $fp = $invoice->payments()->first();

        if($fp->contract && $fp->contract->sales_condition != 0 && $invoice->agent){
            $courier = $invoice->agent->name ;

        }elseif($fp->manager){
            $courier = $fp->manager->name ;

        }

        return $courier;
    }


}