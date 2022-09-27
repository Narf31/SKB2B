<?php

namespace App\Models\Contracts;

use App\Classes\Notification\ContractLogNotifier;
use App\Classes\Notification\NotificationManager;
use App\Models\Directories\Products;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContractsSupplementary extends Model {

    protected $table = 'contracts_supplementary';
    protected $guarded = ['id'];

    public $timestamps = false;

    const STATUS = [
        -1 => 'Удален',
        0 => 'Создан',
        1 => 'В проверке',
        2 => 'Согласование',
        3 => 'На оплате',
        4 => 'Выпущен',
    ];

    public function product() {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function matching(){
        return $this->hasOne(Matching::class, 'id', 'matching_id');
    }

    public function getViewStateOnline(){

        return $this->status_id == 0 ? 'edit' : 'view';
    }

    public function data(){
        $_class = $this->getClassPoductData();
        if($_class){
            return $this->hasOne($_class, 'supplementary_id', 'id');
        }
        return null;
    }

    public function getClassPoductData(){
        $_class = null;

        if($this->product->slug == 'liabilityArbitrationManager'){
            $_class = Products\Data\Supplementary\LiabilityArbitrationManager::class;
        }


        return $_class;
    }
}
