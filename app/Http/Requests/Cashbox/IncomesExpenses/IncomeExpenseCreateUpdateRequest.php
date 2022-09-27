<?php

namespace App\Http\Requests\Cashbox\IncomesExpenses;

use Illuminate\Foundation\Http\FormRequest;

class IncomeExpenseCreateUpdateRequest extends FormRequest
{

    public function authorize(){
        return true;
    }

    public function rules(){

        return [
            "category_id" => "integer|required",
            "status_id"   => "integer|required",
            "date"        => "date",
            "sum"         => "numeric|required|min:1",
            "commission"  => "numeric|min:1",
            "comment"     => "string",
        ];

    }
}
