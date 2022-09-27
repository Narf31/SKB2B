<?php

namespace App\Models\Users;

use App\Models\Settings\UserBalanceSettings;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Users\Type
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Type whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Users\Type whereTitle($value)
 * @mixin \Eloquent
 */
class UsersBalance extends Model
{

    const TYPE_ID = [
        0 => 'Безнал',
        1 => 'Нал',
    ];

    const EVENT_TYPE_ID = [
        0 => 'Приход',
        1 => 'Списание',
    ];

    protected $table = 'users_balance';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function agent(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function type_balanse(){
        return $this->hasOne(UserBalanceSettings::class, 'id', 'balance_id');
    }

    public function getTransactions()
    {
        $transactions = UsersBalanceTransactions::where('balance_id', $this->id);
        return $transactions;
    }

    public function setTransactions($type_id, $event_type_id, $total_sum, $event_date, $purpose_payment)
    {
        if((int)$event_type_id == 1){
            $total_sum = $total_sum *-1;
        }

        $transaction = UsersBalanceTransactions::create([
            'user_id' => auth()->id(),
            'create_date' => date("Y-m-d H:i:s"),
            'event_date' => $event_date,
            'balance_id' => $this->id,
            'type_id' => $type_id,
            'event_type_id' => $event_type_id,
            'total_sum' => $total_sum,
            'residue' => ($this->balance+$total_sum),
            'purpose_payment' => $purpose_payment,
        ]);

        $this->balance += $total_sum;
        $this->save();

        if($type_id == 1){
            //ЕСЛИ НАЛ ТО ПРИХОД В КАССУ
            $cashbox = auth()->user()->cashbox;
            $cashbox->setTransaction(
                0,
                $event_type_id,
                getFloatFormat($total_sum),
                date("Y-m-d H:i:s"),
                "Зачиисление на баланс {$this->type_balanse->title}:".$purpose_payment,
                auth()->id());

        }

        return $transaction;
    }


}
