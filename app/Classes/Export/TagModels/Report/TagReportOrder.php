<?php

namespace App\Classes\Export\TagModels\Report;


use App\Classes\Export\TagModels\Contracts\TagPayments;
use App\Classes\Export\TagModels\TagModel;
use App\Models\Reports\ReportOrders;

class TagReportOrder extends TagModel{

    public $report = false;

    public function apply()
    {
        $replace_arr = [];

        if($report = $this->report = $this->builder->first()){

            /**
             * Даты
             */
            $replace_arr['report_date'] = date('d.m.Y');
            $replace_arr['report_date_ru'] = "«". date('m') ."» ".getMonthById(date('n'), 'rod')." ".date('Y')."г.";
            $replace_arr['report_year'] = $report->report_year;
            $replace_arr['report_month'] = $report->report_month;
            $replace_arr['report_date_start'] = strtotime($report->report_date_start) > 0 ? setDateTimeFormatRu($report->report_date_start) : "";
            $replace_arr['report_date_end'] = strtotime($report->report_date_end) > 0 ? setDateTimeFormatRu($report->report_date_end) : "";
            $replace_arr['period_date_start'] = $this->getPeriodDateStart();
            $replace_arr['period_date_end'] = $this->getPeriodDateEnd();
            $replace_arr['created_at'] = setDateTimeFormatRu($report->created_at);
            $replace_arr['accepted_at'] = setDateTimeFormatRu($report->accepted_at);



            /**
             * Участники
             */
            $replace_arr['general_manager'] = $report->bso_supplier && $report->bso_supplier->purpose_org ? $report->bso_supplier->purpose_org->general_manager : "";
            $replace_arr['create_user'] = $report->create_user ? $report->create_user->name : "";
            $replace_arr['signatory_org'] = $report->signatory_org;
            $replace_arr['signatory_sk_bso_supplier'] = $report->signatory_sk_bso_supplier;
            $replace_arr['accept_user'] = $report->accept_user ? $report->accept_user->name : "";




            /**
             * Цены
             */
            $replace_arr['payment_total'] = titleFloatFormat($report->payment_total);
            $replace_arr['bordereau_total'] = titleFloatFormat($report->bordereau_total);
            $replace_arr['dvoy_total'] = titleFloatFormat($report->dvoy_total);
            $replace_arr['amount_total'] = titleFloatFormat($report->amount_total);
            $replace_arr['to_transfer_total'] = titleFloatFormat($report->to_transfer_total);
            $replace_arr['to_return_total'] = titleFloatFormat($report->to_return_total);





            $replace_arr['report_number'] = $report->id;
            $replace_arr['title_doc'] = $report->bso_supplier && $report->bso_supplier->purpose_org ? $report->bso_supplier->purpose_org->title_doc : "";
            $replace_arr['title'] = $report->title;
            $replace_arr['accept_status'] = ReportOrders::STATE[$report->accept_status];
            $replace_arr['comments'] = $report->comments;



            $payment_tags = (new TagPayments($report->getPayments()))->apply();

            $replace_arr = array_merge($replace_arr, $payment_tags);
        }


        return $replace_arr;
    }


    public static function doc(){

        $payment_doc = TagPayments::doc();

        $doc = [
            'Доступные теги отчёта<sup style="font-size: 75%;">(общие)</sup>' => [
                'report_number' => 'Номер отчёта',
                'report_date_ru' => 'Дата выпуска отчёта в формате "«01» января 2000 г."',
                'report_date' => 'Дата выпуска отчёта в формате "01.01.2000"',
                'title_doc' => 'Название организации',
                'general_manager' => 'Генеральный директор',
                'title' => 'Название',
                'created_at' => 'Когда сформирован',
                'create_user' => 'Кем сформирован',
                'report_year' => 'Отчетный период (год)',
                'report_month' => 'Отчетный период (месяц)',
                'period_date_start' => 'Дата начала отчётного периода',
                'period_date_end' => 'Дата окончания отчётного периода',
                'report_date_start' => 'Договора с',
                'report_date_end' => 'Договора по',
                'signatory_org' => 'Подписант организации',
                'signatory_sk_bso_supplier' => 'Подписант поставщика',
                'accept_status' => 'Статус',
                'accepted_at' => 'Когда акцептован',
                'accept_user' => 'Кем акцептован',
                'payment_total' => 'СП',
                'bordereau_total' => 'Комиссия Бордеро',
                'dvoy_total' => 'Комиссия ДВОУ',
                'amount_total' => 'Комиссия Общая',
                'to_transfer_total' => 'Сумма к перечислению',
                'to_return_total' => 'Сумма к возврату',
                'comments' => 'Комментарий',
            ]
        ];

        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }

        return array_merge($doc, $payment_doc);

    }









    public function getPeriodDateStart(){

        return "«01» ".getMonthById($this->report->report_month, 'rod')." ".$this->report->report_year." г.";

    }




    public function getPeriodDateEnd(){

        $last_month_day = cal_days_in_month(CAL_GREGORIAN, $this->report->report_month, $this->report->report_year);
        return "«{$last_month_day}» ".getMonthById($this->report->report_month, 'rod')." {$this->report->report_year} г.";

    }



}