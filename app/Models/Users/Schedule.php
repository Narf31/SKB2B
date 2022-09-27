<?php

namespace App\Models\Users;

use App\Models\Settings\SalaryState;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    protected $table = 'users_schedules';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $dates = ['datetime_from', 'datetime_to', 'date'];

    public function state(){
        return $this->belongsTo(SalaryState::class, 'state_id');
    }
}
