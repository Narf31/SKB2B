<?php

namespace App\Models\Cashbox;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CashboxTransactions extends Model {

    protected $table = 'cashbox_transactions';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }


}
