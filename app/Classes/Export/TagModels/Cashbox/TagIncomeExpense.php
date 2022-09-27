<?php
namespace App\Classes\Export\TagModels\Cashbox;


use App\Classes\Export\TagModels\TagModel;

class TagIncomeExpense extends TagModel {


    public function apply(){

        $replace_arr = [];
        $income_expense = $this->builder->first();

        $replace_arr['category_type'] = $income_expense->category ? $income_expense->category->type_ru('type') : "";
        $replace_arr['category_id'] = $income_expense->category ? $income_expense->category->title : "";
        $replace_arr['payment_type'] = $income_expense->payment_type_ru('payment_type');
        $replace_arr['sum'] = $income_expense->sum;
        $replace_arr['date'] = getDateFormatRu($income_expense->date);
        $replace_arr['commission'] = $income_expense->commission;
        $replace_arr['comment'] = $income_expense->comment;

        return $replace_arr;

    }


    public static function doc(){

        $doc = [
            'Теги расходов/доп. доходов' => [
                'category_type' => 'Тип',
                'category_id' => 'Категория',
                'payment_type' => 'Тип оплаты',
                'sum' => 'Сумма',
                'date' => 'Дата платежа',
                'commission' => 'Комиссия',
                'comment' => 'Комментарий',
            ]
        ];

        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }

        return $doc;
    }

}