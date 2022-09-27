<?php

namespace App\Models\Orders;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicyGroup;
use App\Models\Directories\Products;
use App\Models\File;
use App\Models\Reports\ReportOrders;
use App\Models\Security\Security;
use App\Models\Settings\FinancialGroup;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrdersChat extends Model
{


    protected $table = 'orders_chat';

    protected $guarded = ['id'];

    protected $dates = ['date_sent', 'date_receipt'];

    const STATUS_SENT = 0;
    const STATUS_RECEIPT = 1;

    const PLAYER = 1;
    const EMPLOYEE = 0;

    public $timestamps = false;

    public function contract(){
        return $this->belongsTo(Contracts::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function player()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function getStatusTitleAttribute()
    {
        return $this->status == self::STATUS_RECEIPT ? 'Прочитано' : 'Не прочитано';
    }


    public static function saveMsg($order_id, $text, $is_player = 0)
    {
        $chat = new OrdersChat();
        $chat->order_id = $order_id;
        $chat->sender_id = auth()->id();
        $chat->text = $text;
        $chat->date_sent = getDateTime();
        $chat->status = 0;
        $chat->is_player = $is_player;
        $chat->save();

        //if($is_player == 0){
        //$Pusher = new PusherRepository();
        //$Pusher->triggerContractChat($chat);
        //}






        return $chat;
    }
}
