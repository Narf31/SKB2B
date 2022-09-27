<?php

namespace App\Models\Settings;

use App\Models\BSO\BsoItem;
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
class PointsSale extends Model
{
    protected $table = 'points_sale';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function city() {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'point_sale_id', 'id')->where('transfer_id', '=', '0');
    }

    public static function getPointsSaleAll(){

        $point_sale = PointsSale::query();
        $user = auth()->user();

        $visibility_obj = $user->role->rolesVisibility(5)->visibility;
        if($visibility_obj == 0){

        }else{

            $point_sale->where('id', $user->point_sale_id);
        }

        return $point_sale;

    }

}
