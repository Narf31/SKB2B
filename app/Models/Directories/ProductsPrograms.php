<?php

namespace App\Models\Directories;

use App\Models\Directories\Products\ProductsSpecialSsettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;


class ProductsPrograms extends Model{

    protected $table = 'product_programs';

    protected $guarded = ['id'];

    public $timestamps = false;

    const SLUG = [
        'kasko' => ['calculator'=>'Калькулятор', 'standard'=>'Стандарт', 'drive'=>'Драйв'],
        'arbitration' => ['yearly'=>'Годовой', 'procedural'=>'Процедурный']
    ];

    public function product() {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function special_settings()
    {
        return $this->hasOne(ProductsSpecialSsettings::class,  'program_id');
    }



}
