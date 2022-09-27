<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Bank whereIsActual($value)
 */
class InstallmentAlgorithmsPaymentList extends Model
{
    protected $table = 'installment_algorithms_payment_list';

    protected $guarded = ['id'];

    public $timestamps = false;


    public static function createValue($algorithms_payment_id, $algorithms_payment, $algorithms_month)
    {

        $result = new \stdClass();
        $result->title = '';
        $result->quantity = 0;

        $total = 0;

        $count_pay = count($algorithms_payment)-1;

        foreach ($algorithms_payment as $key => $algorithms_p) {

            $algorithm_list = InstallmentAlgorithmsPaymentList::create([
                'algorithms_payment_id' => $algorithms_payment_id,
                'payment' => getFloatFormat($algorithms_payment[$key]),
                'month' => (int)$algorithms_month[$key],
            ]);

            $total += getFloatFormat($algorithms_payment[$key]);

            $result->title .= getFloatFormat($algorithms_payment[$key]).'%';
            if($count_pay > $key) $result->title .=' - ';

            $result->quantity = $key+1;
        }

        $result->title .= " = $total%";

        return $result;
    }


}
