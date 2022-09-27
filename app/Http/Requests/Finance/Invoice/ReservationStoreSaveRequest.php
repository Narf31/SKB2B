<?php

namespace App\Http\Requests\Finance\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreSaveRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {

        return [
            'data' => 'array',
            'data.comment' => 'string',
            'data.payer_inn' => 'required|inn|integer',
            'data.payer_kpp' => 'required|kpp|integer',
            'data.payer_name' => 'required|string',
            'data.payer_address' => 'required|string',
            'data.recipient_inn' => 'required|inn|integer',
            'data.recipient_kpp' => 'required|kpp|integer',
            'data.recipient_name' => 'required|string',
            'data.recipient_address' => 'required|string',
            'data.bso.*.bso_number' => 'string|exists:bso_items,bso_title',
            'data.bso.*.bso_sum' => 'filled|numeric',
        ];
    }

    public function messages() {
        return [
            'data.bso.*.bso_sum.numeric' => 'Некорректно введена сумма БСО',
            'data.bso.*.bso_sum.filled' => 'Введите сумму для каждого БСО',
            'data.bso.*.bso_number.exists' => 'Бсо не найдено',
            'data.payer_inn.required' => 'Заполните Инн плательщика',
            'data.payer_inn.inn' => 'Инн плательщика заполнено неверно',
            'data.payer_kpp.required' => 'Заполните КПП плательщика',
            'data.payer_kpp.kpp' => 'КПП плательщика заполнено неверно',
            'data.recipient_inn.required' => 'Заполните Инн получателя',
            'data.recipient_inn.inn' => 'Инн получателя заполнено неверно',
            'data.recipient_kpp.required' => 'Заполните КПП получателя',
            'data.recipient_kpp.kpp' => 'КПП получателя заполнено неверно',
        ];
    }

}
