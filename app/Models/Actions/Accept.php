<?php

namespace App\Models\Actions;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class Accept extends Model{

    use ActiveConstTrait;

    public $table = 'accepts';

    public $timestamps = false;

    public $guarded = ['id'];

    const KIND_ACCEPTANCE = [
        0 => 'Условный',
        1 => 'Безусловный',
    ];


    public function contract(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function payment(){
        return $this->hasOne(Payments::class, 'id', 'payment_id');
    }

    public function accept_user(){
        return $this->hasOne(User::class, 'id', 'accept_user_id');
    }

    public function parent_user(){
        return $this->hasOne(User::class, 'id', 'parent_user_id');
    }

}