<?php

namespace App\Models\Reports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReportPaymentSum extends Model {

    public $table = 'report_payment_sum';
    public $guarded = ['id'];

    const TYPES = [
        1 => 'Приход',
        0 => 'Списание',
    ];

    public function report(){
        return $this->hasOne(ReportOrders::class, 'id', 'report_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }



}