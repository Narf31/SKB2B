<?php

namespace App\Models\Contracts;

use App\Classes\Notification\ContractLogNotifier;
use App\Classes\Notification\NotificationManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContractsLogs extends Model {

    protected $table = 'contracts_logs';
    protected $guarded = ['id'];

    const NOTIFIER = ContractLogNotifier::class;

    protected static function boot(){

        parent::boot();
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public static function setContractLogs($contract_id, $user_id, $status_id, $status_title, $text = '', $json = null)
    {
        ContractsLogs::create([
            'contract_id' => (int)$contract_id,
            'user_id' => (int)$user_id,
            'status_id' => (int)$status_id,
            'status_title' => $status_title,
            'text' => $text,
            'json' => $json,
        ]);

        return true;
    }

}
