<?php

namespace App\Models\Directories\Products;

use App\Models\Directories\Products;
use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\TypeOrg;
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
class ProductsOfficialDiscount extends Model
{

    const TYPE = [
        1 => 'Процент',
        2 => 'Фиксированная',
    ];

    protected $table = 'products_official_discount';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function product() {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }


    public function getTitleFlatsDiscont()
    {
        $title = '';
        $json = [];
        if($this->json){
            $json = \GuzzleHttp\json_decode($this->json);
        }

        if($json){
            $flats_risks =  $this->product->flats_risks()->whereIn('id', $json)->get();
            foreach ($flats_risks as $risks){
                $title .= $risks->title."<br/>";
            }
        }


        return $title;
    }


}
