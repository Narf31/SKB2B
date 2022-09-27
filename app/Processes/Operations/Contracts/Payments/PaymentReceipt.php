<?php

namespace App\Processes\Operations\Contracts\Payments;


use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Contracts\Payments;
use App\Models\Settings\PaymentMethods;
use Illuminate\Support\Str;

class PaymentReceipt{


    public static function attach($payment, $data){

        //если метод оплаты - квитанция + её указали
        if(isset($data->bso_receipt_id) && $data->bso_receipt_id > 0){

            //и если она существует
            if($bso_receipt = BsoItem::getBsoId($data->bso_receipt_id)){

                $bso_receipt->update([
                    'contract_id' => $payment->contract->id,
                    'state_id' => 2,
                ]);
                BsoLogs::setLogs($bso_receipt->id, $bso_receipt->state_id, $bso_receipt->location_id);


                $payment->update([
                    'bso_receipt' => $bso_receipt->bso_title,
                    'bso_receipt_id' => $bso_receipt->id,
                    'bso_not_receipt' => 0,
                ]);

            }

        }else{

            //если без квитанции
            $payment->update([
                'bso_receipt_id' => 0,
                'bso_not_receipt' => 1,
            ]);

        }

        return $payment;

    }


}