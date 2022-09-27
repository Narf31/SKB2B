<?php

namespace App\Models\BSO;

use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use Illuminate\Database\Eloquent\Model;

class BsoType extends Model
{
    public $table = 'type_bso';

    protected $guarded = ['id'];

    public $timestamps = false;

    public static function getAllTypes(){

        return self::all()->where('title', '!=', '');

    }

    public function serie(){
        return $this->hasMany(BsoSerie::class, 'type_bso_id');
    }


    public function dop_serie(){
        return $this->hasMany(BsoDopSerie::class, 'type_bso_id');
    }


    public static function getDistinctType()
    {
        $type_bso =  BsoType::where('title', '!=', '');

        return $type_bso->distinct('product_id');
    }

}
