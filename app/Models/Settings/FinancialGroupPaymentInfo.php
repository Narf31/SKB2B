<?php

namespace App\Models\Settings;

use App\Models\Contracts\Contracts;
use Illuminate\Database\Eloquent\Model;


class FinancialGroupPaymentInfo extends Model
{
    protected $table = 'financial_group_payment_info';

    protected $guarded = ['id'];

    public $timestamps = false;


    public static function getPaymentInfo($insurance_id, $bso_supplier_id, $hold_kv_id, $group_id, $bso_class_id, $need_create = false)
    {
        $payment_info = FinancialGroupPaymentInfo::where('insurance_id', $insurance_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('group_id', $group_id)
            ->where('bso_class_id', $bso_class_id)
            ->get()->first();

        if ($payment_info) return $payment_info;

        if ($need_create) {
            return FinancialGroupPaymentInfo::create([
                'insurance_id' => $insurance_id,
                'bso_supplier_id' => $bso_supplier_id,
                'hold_kv_id' => $hold_kv_id,
                'group_id' => $group_id,
                'bso_class_id' => $bso_class_id
            ]);
        } else {
            return null;
        }

    }

    public static function deleteOthersPaymentInfo($insurance_id, $bso_supplier_id, $hold_kv_id, $bso_class_id)
    {
        FinancialGroupPaymentInfo::where('insurance_id', $insurance_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('bso_class_id','!=', $bso_class_id)
            ->delete();

        return true;


    }

    public static function findPaymentInfo($insurance_id, $bso_supplier_id, $hold_kv_id, $group_id, $bso_class_id)
    {
        $payment_info = FinancialGroupPaymentInfo::where('insurance_id', $insurance_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->whereIn('group_id', [$group_id, 0])
            ->whereIn('bso_class_id', [$bso_class_id, -1])
            ->orderBy('group_id','desc')
            ->orderBy('bso_class_id','desc')
            ->get()->first();

        return $payment_info;
    }
}