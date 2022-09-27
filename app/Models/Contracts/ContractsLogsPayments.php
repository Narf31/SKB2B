<?php

namespace App\Models\Contracts;

use App\Classes\Notification\ContractLogNotifier;
use App\Classes\Notification\NotificationManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContractsLogsPayments extends Model {

    protected $table = 'contracts_logs_payments';
    protected $guarded = ['id'];


    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public static function setContractLogsPayments($contract_id, $user_id, $payment_total, $text = '', $json = null)
    {
        ContractsLogsPayments::create([
            'contract_id' => (int)$contract_id,
            'user_id' => (int)$user_id,
            'payment_total' => $payment_total,
            'text' => $text,
            'json' => $json,
        ]);

        return true;
    }

}
