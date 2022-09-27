<?php

namespace App\Models\Directories\Products\Data\Kasko;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $next_act
 * @property string $default_purpose_payment
 * @property string $inn
 * @property float $limit_year
 * @property float $spent_limit_year
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereNextAct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereDefaultPurposePayment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereSpentLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereIsActual($value)
 */
class KaskoService extends Model
{
    protected $table = 'kasko_service';

    protected $guarded = ['id'];

    public $timestamps = false;


    const SERVIVES = [
        'is_emergency_commissioner' => 'Аварийный Комиссар',
        'is_evacuation' => 'Эвакуация ТС при ДТП',
        'is_collection_certificates' => 'Сбор справок в случае необходимости',
    ];


    public static function getServiceSum($product_id, $program_id, $service_name)
    {
        $service = self::query();
        $service->where('product_id', $product_id);
        $service->where('program_id', $program_id);
        $service->where('service_name', $service_name);
        return $service->get()->first();
    }



}
