<?php

namespace App\Models\Directories\Products\Data\GAP;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class BaseRateGap extends Model
{
    protected $table = 'gap_baserate';

    protected $guarded = ['id'];

    public $timestamps = false;


    const CONF_GAP = [
        0 => ['amount_from' => '0', 'amount_to' => '450000'],
        1 => ['amount_from' => '450001', 'amount_to' => '1000000'],
        2 => ['amount_from' => '1000001', 'amount_to' => '1500000'],
        3 => ['amount_from' => '1500001', 'amount_to' => '2100000'],
        4 => ['amount_from' => '2100001', 'amount_to' => '2500000'],
        5 => ['amount_from' => '2500001', 'amount_to' => '3000000'],
        6 => ['amount_from' => '3000001', 'amount_to' => '4500000'],
        7 => ['amount_from' => '4500001', 'amount_to' => '6000000'],
        8 => ['amount_from' => '6000001', 'amount_to' => '8500000'],
        9 => ['amount_from' => '8500001', 'amount_to' => '10000000'],
        10 => ['amount_from' => '10000001', 'amount_to' => '12000000'],
        11 => ['amount_from' => '12000001', 'amount_to' => '15000000'],
        12 => ['amount_from' => '15000001', 'amount_to' => '18000000'],
        13 => ['amount_from' => '18000001', 'amount_to' => '24000000'],
    ];


    public static function saveBaseRateList($program_id, $data)
    {
        BaseRateGap::where('program_id', $program_id)->delete();
        foreach ($data as $key => $_conf){
            $info = self::CONF_GAP[$key];

            BaseRateGap::create([
                'program_id' => $program_id,
                'conf_key' => $key,
                'amount_from' => getFloatFormat(($info['amount_from'])),
                'amount_to' => getFloatFormat(($info['amount_to'])),
                'max_amount' => getFloatFormat(($_conf['max_amount'])),
                'net_premium' => getFloatFormat(($_conf['net_premium'])),
                'marketing_kv' => getFloatFormat(($_conf['marketing_kv'])),
                'technical_payment' => getFloatFormat(($_conf['net_premium'])) + getFloatFormat(($_conf['marketing_kv'])),

            ]);


        }
        return true;
    }


    public static function getBaseRateList($program_id, $_conf)
    {
        $result = new \stdClass();
        $result->max_amount = '';
        $result->net_premium  = '';
        $result->marketing_kv = '';
        $result->technical_payment = '';
        $rate = BaseRateGap::where('program_id', $program_id)->where('conf_key', $_conf)->get()->first();
        if($rate){
            $result->max_amount = $rate->max_amount;
            $result->net_premium  = $rate->net_premium;
            $result->marketing_kv = $rate->marketing_kv;
            $result->technical_payment = $rate->technical_payment;
        }

        return $result;
    }


}
