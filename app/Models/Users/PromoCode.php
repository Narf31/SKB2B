<?php

namespace App\Models\Users;

use App\Models\Contracts\Contracts;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Permission
 *
 * @property integer $id
 * @property string $title
 * @property integer $group_id
 * @property-read \App\Models\Settings\PermissionGroup $group
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Permission whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Permission whereGroupId($value)
 * @mixin \Eloquent
 */
class PromoCode extends Model
{
    protected $table = 'users_promocode';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function contract()
    {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }



    public static function getPromoCode($code)
    {
        $promo = PromoCode::where('is_actual', 1)
            ->where('valid_date', '>', date('Y-m-d'))
            ->where('title', $code)
            ->get()->first();
        return $promo;
    }

    public static function generateCode($max=8)
    {
        $chars="1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
        $size=StrLen($chars)-1;

        $code=null;

        while($max--) $code.=$chars[rand(0,$size)];


        if(!PromoCode::getPromoCode($code)){
            return $code;
        }

        return PromoCode::generateCode($max);
    }


}