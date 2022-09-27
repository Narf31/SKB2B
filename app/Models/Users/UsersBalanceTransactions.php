<?php

namespace App\Models\Users;

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
class UsersBalanceTransactions extends Model
{

    protected $table = 'users_balance_transactions';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }


}
