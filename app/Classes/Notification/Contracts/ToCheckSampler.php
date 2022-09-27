<?php
namespace App\Classes\Notification\Contracts;

use App\Models\Contracts\Contracts;

class ToCheckSampler{

    public static function sample(Contracts $contract){

        //ищем последний лог отправки в коррекцию и смотрим кто отправил
        if($log = $contract->logs()->orderByDesc('id')->where('status_id', 2)->first()){
            return collect([$log->user]);
        }
        return collect();
    }
}