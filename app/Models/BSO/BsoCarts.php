<?php

namespace App\Models\BSO;

use App\Classes\Export\TagModels\BSO\TagBsoCarts;
use App\Models\Settings\PointsSale;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class BsoCarts extends Model
{

    use ActiveConstTrait;

    public $table = 'bso_carts';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TAG_MODEL = TagBsoCarts::class;


    const STATE_CAR = [
        0 => 'В работе',
        1 => 'Передан агенту',
    ];


    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'bso_cart_id', 'id')->orderBy('bso_items.bso_title');
    }


    public function user_to(){
        return $this->hasOne(User::class, 'id', 'user_id_to');
    }

    public function user_from(){
        return $this->hasOne(User::class, 'id', 'user_id_from');
    }


    public function bso_manager(){
        return $this->hasOne(User::class, 'id', 'bso_manager_id');
    }

    public function tp_bso_manager(){
        return $this->hasOne(User::class, 'id', 'tp_bso_manager_id');
    }

    public function point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'tp_id');
    }

    public function new_point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'tp_new_id');
    }

    public function curr_point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'tp_id');
    }

    public function courier(){
        return $this->hasOne(User::class, 'id', 'courier_id');
    }

    public function type(){
        return $this->hasOne(BsoCartType::class, 'id', 'bso_cart_type');
    }

    public static function getCars(){
        $act = BsoCarts::query();

        //Проверка доступа

        //Проверка доступа
        if(auth()->user()->role->rolesVisibility(5)->visibility == 0){

        }else{

            //Видимость на всю организацию и свои СДЕЛАТЬ

            //Видимость только на свои

            $act->where(function ($query) {
                $query->where('user_id_from', auth()->id())
                    ->orWhere('user_id_to', auth()->id())
                    ->orWhere('bso_manager_id', auth()->id());
            });

        }

        return $act;

    }

    public static function getCarsId($id){
        $act = BsoCarts::getCars();
        return $act->where('id', $id)->get()->first();
    }


    public function removeBSOCart($bso_id = 0, $bso_type_id = 0)
    {
        $bso_items = BsoItem::where('bso_cart_id', $this->id);
        if($bso_id > 0){
            $bso_items->where('id', $bso_id);
        }

        if($bso_type_id > 0){
            $bso_items->where('type_bso_id', $bso_type_id);
        }


        $bso_items->update(['is_reserved' => 0, 'bso_cart_id' => 0]);
        return true;

    }


}
