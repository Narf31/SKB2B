<?php

namespace App\Classes\Notification;

class NotificationManager{

    public static function handle($log){

        $log_model = get_class($log);
        $notifier = defined("{$log_model}::NOTIFIER") ? $log_model::NOTIFIER : false;

        if($notifier){
            (new $notifier($log))->notify();
        }

    }



}