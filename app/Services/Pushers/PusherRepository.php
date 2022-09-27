<?php

namespace App\Services\Pushers;


use App\Models\Contracts\ContractsChat;
use Pusher\Pusher;

class PusherRepository
{
    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
    }

    public function triggerContractChat(ContractsChat $chat)
    {

        return $this->pusher->trigger(["chat-contract-{$chat->contract_id}",'unread-messages'], 'new-message', [
            'sender_id' => $chat->sender_id,
            'text'      => $chat->text,
            'sender'    => $chat->sender->name,
            'date'      => $chat->date_sent->format('d.m.Y H:i'),
            'status'    => $chat->status,
            'is_player' => $chat->is_player,
            'is_file'   => $chat->is_file,
            'file'      => $chat->getFileArray(),
        ]);
    }


    public function triggerContractContent($contract_id, $event)
    {
        $this->pusher->trigger(["chat-contract-{$contract_id}",'unread-messages'], 'new-event-view', $event);
    }


}