<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class GeneralUlOf extends Model
{


    protected $table = 'general_ul_of';

    protected $guarded = ['id'];

    public $timestamps = false;


    public static function getHesh($title){
        $title = str_replace(' ','', $title);
        $title = mb_strtoupper($title, 'UTF-8');

        return md5($title);
    }


    public static function getFindHesh($hash){
        return self::where('hash', $hash)->get()->first();
    }

}
