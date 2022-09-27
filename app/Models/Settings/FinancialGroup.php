<?php

namespace App\Models\Settings;

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
class FinancialGroup extends Model
{
    protected $table = 'financial_group';

    protected $guarded = ['id'];
    
    public $timestamps = false;



}
