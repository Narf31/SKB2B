<?php

namespace App\Models\Settings;

use App\Models\BSO\BsoLogs;
use App\Models\Contracts\ContractsLogs;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    public $table = 'notifications';
    public $guarded = ['id'];
    public $timestamps = false;


    public function bso_log(){
        return $this->hasOne(BsoLogs::class, 'id', 'bso_log_id');
    }

    public function contract_log(){
        return $this->hasOne(ContractsLogs::class, 'id', 'contract_log_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function setNotificationContract($user_id, $contract, $txt)
    {
        $notification = Notification::create([
            'user_id' => $user_id,
            'is_read' => 0,
            'create_date' => getDateTime(),
            'url' => "/contracts/online/{$contract->id}",
        ]);

        $notification->msg = $txt;
        $notification->save();
        return true;
    }

    public static function setNotificationContractUnder($user_id, $matching, $txt)
    {
        $notification = Notification::create([
            'user_id' => $user_id,
            'is_read' => 0,
            'create_date' => getDateTime(),
            'url' => "/matching/underwriting/{$matching->id}",
        ]);

        $notification->msg = $txt;
        $notification->save();
        return true;
    }

}