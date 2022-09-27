<?php

namespace App\Processes\Operations\Contracts\Damages;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Orders\OrdersLogs;
use App\Models\User;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use Illuminate\Support\Str;

class DamagesLogs{


    public static function setLogs($order_id, $user_id, $status_id, $status_title, $event_title, $create_title ='', $color ='')
    {
        OrdersLogs::create([
            'order_id' => $order_id,
            'created_at' => getDateTime(),
            'status_id' => $status_id,
            'user_id' => $user_id,
            'create_title' => $create_title,
            'status_title' => $status_title,
            'event_title' => $event_title,
            'color' => $color,
        ]);

        return true;
    }



}