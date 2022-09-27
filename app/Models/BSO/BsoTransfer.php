<?php

namespace App\Models\BSO;

use App\Models\Settings\PointsSale;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BsoTransfer extends Model
{
    public $table = 'bso_transfers';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPES = [
        1 => 'Передача со склада агенту',
        2 => 'Передача БСО на точку продаж',
        3 => 'Передача БСО курьеру',
        4 => 'Передача от агента-агенту',
//        5 => 'Прием БСО от агента',
//        6 => 'Передача БСО в СК',
//        7 => 'Передача БСО на Регион',
//        8 => 'Передача БСО от ТП на ТП',
    ];

    const STATUSES = [
        1 => 'Создан',
        2 => 'Выполнен',
    ];


    public function items(){
        return $this->hasMany(BsoItem::class, 'transfer_id', 'id');
    }

    public function last_items(){
        return $this->hasMany(BsoItem::class, 'last_transfer_id', 'id');

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

    public function courier(){
        return $this->hasOne(User::class, 'id', 'courier_id');
    }

    public function point_sale(){
        return $this->hasOne(PointsSale::class, 'id', 'point_sale_id');
    }

    public function type(){
        if(isset($this->type_id) && $this->type_id>0){
            return BsoTransfer::TYPES[$this->type_id];
        }
        return 'Не установлен';
    }

    public function status(){
        if(isset($this->status_id) && $this->status_id >0){
            return BsoTransfer::STATUSES[$this->status_id];
        }
        return 'Не установлен';
    }




}
