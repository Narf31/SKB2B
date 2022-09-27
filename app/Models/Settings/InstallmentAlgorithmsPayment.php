<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;



class InstallmentAlgorithmsPayment extends Model
{
    protected $table = 'installment_algorithms_payment';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function algorithm_list()
    {
        return $this->hasMany(InstallmentAlgorithmsPaymentList::class, 'algorithms_payment_id', 'id');
    }

}
