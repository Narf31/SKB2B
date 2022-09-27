<?php

namespace App\Http\Requests\Cashbox\IncomesExpenses;

use Illuminate\Foundation\Http\FormRequest;

class IncomesExpensesListRequest extends FormRequest
{

    public function authorize(){
        return true;
    }

    public function rules(){

        return [
            'PAGE' => 'integer',
            'page_count' => 'integer',
            'category_id' => 'integer',
            'status_id' => 'integer',
            'payment_id' => 'integer',
            'date_from' => 'date',
            'date_to' => 'date',
        ];

    }
}
