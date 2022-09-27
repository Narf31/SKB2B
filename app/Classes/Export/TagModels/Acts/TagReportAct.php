<?php

namespace App\Classes\Export\TagModels\Acts;

use App\Classes\Export\TagModels\BSO\TagBsoItem;
use App\Classes\Export\TagModels\Contracts\TagPayments;
use App\Classes\Export\TagModels\TagModel;

class TagReportAct extends TagModel{

    public function apply()
    {
        $replace_arr = [];
        if($act = $this->builder->first()){

            $replace_arr['act_number'] = $act->id;
            $replace_arr['title'] = $act->title;
            $replace_arr['create_user'] = $act->create_user ? $act->create_user->name : "";
            $replace_arr['bso_supplier'] = $act->bso_supplier ? $act->bso_supplier->title : "";
            $replace_arr['signatory_org'] = $act->signatory_org;
            $replace_arr['signatory_sk_bso_supplier'] = $act->signatory_sk_bso_supplier;
            $replace_arr['accept_status'] = $act->accept_status ? 'Да' : 'Нет';
            $replace_arr['insurance'] = $act->bso_supplier && $act->bso_supplier->insurance ? $act->bso_supplier->insurance->title : "";



            /**
             * Даты
             */
            $replace_arr['created_at'] = getDateFormatRu($act->created_at);
            $replace_arr['report_year'] = $act->report_year;
            $replace_arr['report_month'] = $act->report_month;
            $replace_arr['report_date_start'] =  getDateFormatRu($act->report_date_start) != '01.01.1970' ? getDateFormatRu($act->report_date_start) : "";
            $replace_arr['report_date_end'] =  getDateFormatRu($act->report_date_end) != '01.01.1970' ? getDateFormatRu($act->report_date_end) : "";


            if($act->type_id == 0){
                $item_tags = (new TagBsoItem($act->bso_items()->getQuery()))->apply();
            }else{
                $item_tags = (new TagPayments($act->payments()->getQuery()))->apply();
            }
            $replace_arr = array_merge($replace_arr, $item_tags);
        }


        return $replace_arr;
    }


    public static function doc(){



        $doc = [
            'Доступные теги акта<sup style="font-size: 75%;">(общие)</sup>' => [
                'act_number' => 'Номер',
                'title' => 'Название',
                'insurance' => 'Страховая',
                'created_at' => 'Сформирован',
                'create_user' => 'Пользователем',
                'bso_supplier' => 'Страховая компания',
                'report_year' => 'Отчетный период(год)',
                'report_month' => 'Отчетный период(месяц)',
                'report_date_start' => 'Дата заключения договора с',
                'report_date_end' => 'Дата заключения договора по',
                'signatory_org' => 'Подписант организации',
                'signatory_sk_bso_supplier' => 'Подписант поставщика',
                'accept_status' => 'Акцептован',
            ]
        ];

        if(self::process()->get('category') == 'contracts_to_sk'){
            $doc = array_merge($doc, TagPayments::doc());
        }


        if(self::process()->get('category') == 'sk_bso_to_sk'){
            $doc = array_merge($doc, TagBsoItem::doc());
        }


        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }



        return $doc;

    }


}