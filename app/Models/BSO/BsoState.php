<?php

namespace App\Models\BSO;

use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use Illuminate\Database\Eloquent\Model;

class BsoState extends Model
{
    CONST STATUS_COLORS = [
        0 => 'bg-white',
        1 => 'bg-gray ',
        2 => 'bg-green',
        3 => 'bg-red',
        4 => 'bg-yellow',
        5 => 'bg-blue',
    ];
    
    public $table = 'bso_states';

    protected $guarded = ['id'];

    public $timestamps = false;




}
