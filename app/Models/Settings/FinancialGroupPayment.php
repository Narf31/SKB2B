<?php

namespace App\Models\Settings;

use App\Models\Contracts\Contracts;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\FinancialPolicy
 *
 * @property integer $id
 * @property integer $is_active
 * @property string $title
 * @property integer $types_trailers_id
 * @property string $types_trailers_title
 * @property float $kv_km
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereTypesTrailerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereTypesTrailerTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereKvKm($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereTypesTrailersId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\FinancialPolicy whereTypesTrailersTitle($value)
 */
class FinancialGroupPayment extends Model
{
    protected $table = 'financial_group_payment';

    /*
    const PAYMENTS_TYPE = [
        0 =>[
            "title" => "Квитанциия А7",
            "payment_type" => 0,
            "payment_flow" => 0,
        ],
        1 =>[
            "title" => "Чек онлайн",
            "payment_type" => 0,
            "payment_flow" => 0,
        ],
        2 =>[
            "title" => "Счет брокер",
            "payment_type" => 1,
            "payment_flow" => 0,
        ],
        3 =>[
            "title" => "Счет в ск",
            "payment_type" => 1,
            "payment_flow" => 1,
        ],
        4 =>[
            "title" => "Платежная странииц",
            "payment_type" => 1,
            "payment_flow" => 1,
        ],
    ];
    */

    protected $guarded = ['id'];
    
    public $timestamps = false;


    public function payment_method() {
        return $this->hasOne(PaymentMethods::class, 'id', 'payment_type_id');
    }



    public static function getPayment($insurance_id, $bso_supplier_id, $hold_kv_id, $group_id, $payment_type_id, $bso_class_id)
    {
        return FinancialGroupPayment::where('insurance_id', $insurance_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('group_id', $group_id)
            ->where('bso_class_id', $bso_class_id)
            ->where('payment_type_id', $payment_type_id)->get()->first();
    }

    public static function deleteOthersPayment($insurance_id, $bso_supplier_id, $hold_kv_id, $bso_class_id)
    {
        FinancialGroupPayment::where('insurance_id', $insurance_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('bso_class_id','!=', $bso_class_id)
            ->delete();

        return true;
    }

    public static function isPayment($insurance_id, $bso_supplier_id, $hold_kv_id, $group_id, $payment_type_id, $bso_class_id)
    {
        $select = 0;
        if(FinancialGroupPayment::getPayment($insurance_id, $bso_supplier_id, $hold_kv_id, $group_id, $payment_type_id, $bso_class_id)){
            $select = 1;
        }

        return $select;
    }

    public static function savePayment($insurance_id, $bso_supplier_id, $hold_kv_id, $group_id, $payments, $bso_class_id)
    {
        FinancialGroupPayment::where('insurance_id', $insurance_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('hold_kv_id', $hold_kv_id)
            ->where('bso_class_id', $bso_class_id)
            ->where('group_id', $group_id)->delete();

        if($payments && is_array($payments)){
            foreach ($payments as $key=>$payment){
                $FinancialGroup = FinancialGroupPayment::create([
                    'insurance_id' =>$insurance_id,
                    'bso_supplier_id' =>$bso_supplier_id,
                    'hold_kv_id' =>$hold_kv_id,
                    'group_id' =>$group_id,
                    'bso_class_id' =>$bso_class_id,
                    'payment_type_id' =>$key,
                ]);
            }
        }


        return true;
    }


    public static function getPaymentsProduct($contract_id)
    {

        $result = [];
        $contract = Contracts::find($contract_id);
        $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id);
        $bso_class_id = $hold_kv_product->bso_class_id;

        if($bso_class_id == -1){
            if(isset($contract->bso)){
                $bso_class_id = $contract->bso->bso_class_id;
            }elseif($contract->product->slug == 'osago'){
                $bso_class_id = $contract->data()->first()->is_epolicy;
            }
        };

        // если нет то если это осаго по слагу
        if($hold_kv_product){
            $group_id = (int)$contract->agent->financial_group_id;
            $payment = FinancialGroupPayment::getPaymentsGroup($hold_kv_product->bso_supplier_id, $hold_kv_product->id, $group_id,$bso_class_id);

            if(!$payment || count($payment) == 0){
                $payment = FinancialGroupPayment::getPaymentsGroup($hold_kv_product->bso_supplier_id, $hold_kv_product->id, 0,$bso_class_id);
            }

            if($payment){
                foreach ($payment as $pay){
                    $result[] = $pay->payment_method;
                }
            }
        }

        return $result;
    }


    public static function getPaymentsGroup($bso_supplier_id, $hold_kv_id, $group_id, $bso_class_id)
    {
        if ($bso_class_id == -1) {
            return FinancialGroupPayment::
            where('bso_supplier_id', $bso_supplier_id)
                ->where('hold_kv_id', $hold_kv_id)
                ->where('group_id', $group_id)
                ->select('payment_type_id')
                ->distinct()->get();
        }else {
            return FinancialGroupPayment::
            where('bso_supplier_id', $bso_supplier_id)
                ->where('hold_kv_id', $hold_kv_id)
                ->whereIn('bso_class_id', [$bso_class_id, -1])
                ->where('group_id', $group_id)
                ->select('payment_type_id')
                ->distinct()->get();
        }
    }

}
