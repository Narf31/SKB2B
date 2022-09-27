<?php

namespace App\Models\BSO;

use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use App\Models\Settings\TemplateCategory;
use Illuminate\Database\Eloquent\Model;

class BsoCartType extends Model
{
    public $table = 'bso_cart_types';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPE_TO_TEMPLATE_CATEGORY = [
        0 => 7,
        1 => 4,
        2 => 5,
        3 => 3,
        4 => 6,
        5 => 7,
        6 => 8,
        7 => 9,
        8 => 10,
        9 => 11,
    ];

    public static function getAllTypes(){

        return BsoCartType::where('published', 1)->ordeBy('ordering', "asc");

    }


    public function template_category(){
        if(isset(self::TYPE_TO_TEMPLATE_CATEGORY[$this->id])){
            return TemplateCategory::query()->where('id', self::TYPE_TO_TEMPLATE_CATEGORY[$this->id])->get()->first();
        }
        return false;
    }


}
