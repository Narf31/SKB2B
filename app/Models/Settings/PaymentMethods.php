<?php

namespace App\Models\Settings;

use App\Models\File;
use App\Services\PaymentAgent\IntegrationPaymentAgent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Bank whereIsActual($value)
 */
class PaymentMethods extends Model
{
    public $table = 'payment_methods';
    public $timestamps = false;
    public $guarded = [];

    const TEMPLATES_FOLDER = "payment_methods/template";

    const ACCESS_BSO = [
        0 => 'Все',
        1 => 'Бумажные',
        2 => 'Электронные'
    ];

    const KEY_TYPE = [
        0 => 'Квитанция БСО',
        1 => 'ОФД',
        2 => 'Без подтверждения',
        3 => 'Счет',
        4 => 'Платежная страница',
        5 => 'Промо-код'
    ];

    const CONTROLL = [
        0 => 'Фронт',
        1 => 'СК',
    ];

    public function template(){
        return $this->hasOne(File::class, 'id', "file_id");
    }



    public static function searchMethod($payment_type, $payment_flow)
    {
        $method = PaymentMethods::where('is_actual', 1);
        $method->where('payment_type', $payment_type);
        $method->where('payment_flow', $payment_flow);

        return $method->get()->last();

    }

    public function getStatePayment($invoice)
    {
        $state_payment = 1;

        if($this->key_type == 1){// ОФД
            //IntegrationPaymentAgent::getOfdReceipt($invoice);
        }

        if($this->key_type == 4 && (int)$this->control_type == 0){// Платежная страница
            $state_payment = 0;
            IntegrationPaymentAgent::getBillingPage($invoice);
        }

        if($this->key_type == 4 && (int)$this->control_type == 1){// Платежная страница СК
            $state_payment = 0;
        }


        if($this->key_type == 3){// Счет
            $state_payment = 0;
            if($this->payment_flow == 0){//Счет Брокер
                $state_payment = 1;
            }
        }

        return $state_payment;
    }




}
