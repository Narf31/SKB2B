<?php

namespace App\Models\Finance;

use App\Classes\Export\TagModels\Finance\TagInvoice;
use App\Models\Contracts\Payments;
use App\Models\Organizations\Organization;
use App\Models\Settings\PaymentMethods;
use App\Models\Settings\UserBalanceSettings;
use App\Models\User;
use App\Models\File;
use App\Models\Users\UsersBalance;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    use ActiveConstTrait;
    protected $table = 'invoices';
    protected $guarded = ['id'];

    const FILES_DOC = 'invoice/';

    const TAG_MODEL = TagInvoice::class;


    const STATUSES = [
        1 => 'Не оплачен',
        2 => 'Оплачен'
    ];

    const TYPES = [
        'cash' => 'Наличный',
        'cashless' => 'Безналичный',
    ];

    const CREATE_TYPES = [
        1 =>'Автоматическое создание счёта',
        2 =>'Создание счёта на одно юр. лицо',
        3 =>'Создание счёта на несколько юр.лиц'
    ];

    const TYPE_INVOICE_PAYMENT = [
        1 => 'Наличный Брокер',
        2 => 'Безналичный Брокер',
        3 => 'Наличный СК',
        4 => 'Безналичный СК',
    ];



    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function agent(){
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function invoice_payment_user(){
        return $this->hasOne(User::class, 'id', 'invoice_payment_user_id');
    }

    public function payments(){
        return $this->hasMany(Payments::class, 'invoice_id', 'id');
    }

    public function org(){
        return $this->hasOne(Organization::class, 'id', 'org_id');
    }

    public function doc(){
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public function payment_method(){
        return $this->hasOne(PaymentMethods::class, 'id', 'payment_method_id');
    }




    public function getInvoceDefaultPaymentType(){
        if($this->type == 'sk') return 4;
        if($this->type == 'cashless') return 4;
        return 4;
    }

    public static function getInvoiceId($id){
        //ПРОВЕРКА ДОСТУПА
        $invoice = Invoice::where("id", $id);

        return $invoice->get()->first();
    }


    public function refreshInvoice()
    {
        $invoice_payment_total = 0;

        foreach ($this->payments as $payment)
        {
            $invoice_payment_total = $payment->invoice_payment_total;
        }

        $this->invoice_payment_total = $invoice_payment_total;
        $this->save();
        return 1;
    }

    public static function searchType($payment_type, $payment_flow)
    {

        $result = new \stdClass();
        $result->TYPES = 'cashless';
        $result->TYPE_INVOICE_PAYMENT = 1;

        if($payment_type == 0) $result->TYPES = 'cash';

        if($payment_flow == 0 && $payment_type == 0) $result->TYPE_INVOICE_PAYMENT = 1;
        if($payment_flow == 1 && $payment_type == 0) $result->TYPE_INVOICE_PAYMENT = 3;


        if($payment_flow == 0 && $payment_type != 0) $result->TYPE_INVOICE_PAYMENT = 2;
        if($payment_flow == 1 && $payment_type != 0) $result->TYPE_INVOICE_PAYMENT = 4;


        return $result;
    }


    public function getInfoSum()
    {
        $result = new \stdClass();
        $result->total = 0;
        $result->total_kv_agent = 0;
        $result->total_sum = 0;

        foreach($this->payments as $key => $payment)
        {
            $result->total += $payment->payment_total;
            $result->total_kv_agent += $payment->financial_policy_kv_bordereau_total;
            $result->total_sum += $payment->getPaymentAgentSum();
        }

        return $result;
    }

}
