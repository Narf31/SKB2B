<?php

namespace App\Models\Orders;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicyGroup;
use App\Models\Directories\Products;
use App\Models\File;
use App\Models\Reports\ReportOrders;
use App\Models\Security\Security;
use App\Models\Settings\City;
use App\Models\Settings\FinancialGroup;
use App\Models\Settings\PointsSale;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Damages extends Model
{

    const FILES_DOC = 'orders/damages';

    protected $table = 'orders';

    protected $guarded = ['id'];

    public $timestamps = false;


    const STATYS = [
        0 => 'Временные',
        1 => 'На распределение',
        2 => 'В работе',
        3 => 'На согласовании',
        4 => 'На оплате',
        5 => 'Архив',
    ];

    const POSITION_TYPE = [
        0 => 'Выезд эксперта',
        1 => 'Офис компании',
        2 => 'Самостоятельно',
    ];

    const INSURER_TYPE = [
        0 => 'Не установлено',
        1 => 'Потерпевший',
        2 => 'Виновник',
    ];

    const WORK_STATYS = [
        0 => 'Назначен',
        1 => 'Отказался',
        2 => 'В работе',
        3 => 'Приехал',
        4 => 'Закончил',
    ];

    public function bso() {
        return $this->hasOne(BsoItem::class, 'id', 'bso_id');
    }

    public function contract(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function agent() {
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function curator() {
        return $this->hasOne(User::class, 'id', 'agent_curator_id');
    }

    public function logs() {
        return $this->hasMany(OrdersLogs::class, 'order_id', 'id')->orderBy('created_at', 'desc');
    }

    public function scans() {
        return $this->belongsToMany(File::class, 'orders_scans', 'order_id', 'file_id');
    }

    public function city() {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function point_sale() {
        return $this->hasOne(PointsSale::class, 'id', 'point_sale_id');
    }

    public function info() {
        return $this->hasOne(DamageOrder::class, 'order_id', 'id');
    }

    public function payments() {
        return $this->hasMany(DamageOrderPayments::class, 'order_id', 'id');
    }

    public function chats(){
        return $this->hasMany(OrdersChat::class, 'order_id');
    }

    public function work_user() {
        return $this->hasOne(User::class, 'id', 'work_user_id');
    }


    public static function getDamages()
    {
        $damage = Damages::where('orders.type_id', 1);


        return $damage;
    }

    public static function getDamagesId($id)
    {
        $damage = Damages::getDamages()->where('orders.id', $id)->get()->first();
        if(!$damage) abort(403);

        return $damage;
    }

    public static function getClientDamagesId($id, $insurer_id)
    {
        $damage = Damages::getDamages()->where('orders.insurer_id', $insurer_id)->where('orders.id', $id)->get()->first();
        if(!$damage) abort(503);

        return $damage;
    }



    public static function getPath($order_id)
    {
        $path = '/app/' . Damages::FILES_DOC .'/'.(int)($order_id/1000)."/{$order_id}/";

        $_temp_path = explode('/', $path);
        $__path_dir = storage_path().'/';
        for ($i=1;$i<count($_temp_path)-1;$i++){
            $__path_dir .= $_temp_path[$i].'/';
            if(!is_dir($__path_dir)){
                mkdir($__path_dir, 0777, true);
            }

        }
        return $path;
    }


}
