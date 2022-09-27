<?php

namespace App\Models\Cashbox;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Cashbox extends Model {

    protected $table = 'cashbox';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function setTransaction($type_id, $event_type_id, $total_sum, $event_date, $purpose_payment, $user_id)
    {
        if((int)$event_type_id == 1){
            $total_sum = $total_sum *-1;
        }

        $transaction = CashboxTransactions::create([
            'user_id' => $user_id,
            'event_date' => $event_date,
            'cashbox_id' => $this->id,
            'type_id' => $type_id,
            'event_type_id' => $event_type_id,
            'total_sum' => $total_sum,
            'residue' => ($this->balance+$total_sum),
            'purpose_payment' => $purpose_payment,
        ]);

        $this->balance += $total_sum;
        $this->save();

        return true;
    }


}
