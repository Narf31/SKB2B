<?php

namespace App\Models\Orders;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
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

class Pso extends Model
{

    const FILES_DOC = 'orders/pso';

    protected $table = 'orders';

    protected $guarded = ['id'];

    public $timestamps = false;


    const STATYS = [
        0 => 'Временные',
        1 => 'На распределение',
        2 => 'В работе',
        3 => 'На согласовании',
        4 => 'Согласовано',
        5 => 'Запрет',
    ];

    const POSITION_TYPE = [
        0 => 'Выезд эксперта',
        1 => 'Офис компании',
    ];



    const WORK_STATYS = [
        0 => 'Назначен',
        1 => 'Отказался',
        2 => 'В работе',
        3 => 'Приехал',
        4 => 'Закончил',
        5 => 'Запрет',
        6 => 'Согласовано',

    ];

    public function bso() {
        return $this->hasOne(BsoItem::class, 'id', 'bso_id');
    }

    public function contract(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function product(){
        return $this->hasOne(Products::class, 'id', 'product_id');
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

    public function insurer() {
        return $this->hasOne(Subjects::class, 'id', 'insurer_id');
    }

    public function work_user() {
        return $this->hasOne(User::class, 'id', 'work_user_id');
    }


    public static function getPsos()
    {
        return self::where('orders.type_id', 0);
    }

    public static function getPsoId($id)
    {
        $pso = self::getPsos()->where('orders.id', $id)->get()->first();
        if(!$pso) abort(403);

        return $pso;
    }

    public static function getClientPsoId($id, $insurer_id)
    {
        $pso = self::getPsos()->where('orders.insurer_id', $insurer_id)->where('orders.id', $id)->get()->first();
        if(!$pso) abort(503);

        return $pso;
    }



    public static function getPath($order_id)
    {
        $path = '/app/' . self::FILES_DOC .'/'.(int)($order_id/1000)."/{$order_id}/";

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
