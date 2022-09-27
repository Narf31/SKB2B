<?php

namespace App\Models\Directories\Products\Data\DGO;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class BaseRateDgo extends Model
{
    protected $table = 'dgo_baserate';

    protected $guarded = ['id'];

    public $timestamps = false;



    public static function saveBaseRateList($data)
    {
        BaseRateDgo::query()->delete();
        foreach ($data as $key => $payment){
            BaseRateDgo::create([
                'insurance_amount' => getFloatFormat($key),
                'payment_total' => getFloatFormat(($payment['payment_total'])),

            ]);
        }

        return true;
    }


    public static function getBaseRateList($insurance_amount)
    {
        $result = new \stdClass();
        $result->payment_total  = '';
        $rate = BaseRateDgo::where('insurance_amount', $insurance_amount)->get()->first();
        if($rate){
            $result->payment_total = $rate->payment_total;
        }

        return $result;
    }


}
