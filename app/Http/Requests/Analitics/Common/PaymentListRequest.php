<?php

namespace App\Http\Requests\Analitics\Common;

use Illuminate\Foundation\Http\FormRequest;

class PaymentListRequest extends FormRequest
{

    public function authorize(){
        return true;
    }

    public function rules(){

        return [
            'agent_id'             => 'integer',
            'nop_id'               => 'integer',
            'manager_id'           => 'integer',
            'payment_user_id'      => 'integer',
            'payment_status_id'    => 'integer',
            'transaction_type'     => 'integer',
            'terms_sale'           => 'integer',
            'personal_selling'     => 'integer',
            'submit_receiver'      => 'integer',
            'is_deleted'           => 'integer',
            'type_id'              => 'integer',
            'per_page'             => 'integer',
            'filial_id'            => 'integer',
            'region_id'            => 'integer',
            'payment_date_type_id' => 'integer',

            'payment_type'         => 'string|in:sk,cash,cashless,-1',

            'date_from'            => 'date',
            'date_to'              => 'date',


            'product_id'           => 'array',
            'product_id.*'           => 'integer',

            'insurance_ids'        => 'array',
            'insurance_ids.*'      => 'integer',

            'org_ids'              => 'array',
            'org_ids.*'            => 'integer',

            'department_ids'       => 'array',
            'department_ids.*'     => 'integer',

        ];

    }
}
