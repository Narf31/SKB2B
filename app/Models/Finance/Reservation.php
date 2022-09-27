<?php

namespace App\Models\Finance;

use App\Classes\Export\TagModels\Finance\TagReservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Reservation extends Model
{
    use ValidatesRequests;

    protected $table = 'reservations';
    protected $guarded = ['id'];
    protected $casts = ['data' => 'array'];

    const TAG_MODEL = TagReservation::class;

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
