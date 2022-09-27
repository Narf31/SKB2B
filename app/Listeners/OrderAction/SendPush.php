<?php

namespace App\Listeners\OrderAction;

use App\Events\OrderAction;
use App\Models\Mobile\Token;
use App\Services\Foreign\FireBase\Android;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPush
{

    public function handle(OrderAction $event)
    {

        $user = $event->user;

        \Log::info('into event');

        $token = Token::where('user_id', $user->id)->first();

        \Log::info('FCM Token'. $token->fcm_token);

        $fcm = new Android();
        $fcm->push($token->fcm_token);


    }
}
