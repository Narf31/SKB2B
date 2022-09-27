<?php

namespace App\Models\Directories;

use App\Models\Settings\ProgramsOnlineList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;


class ProductsInfo extends Model{

    protected $table = 'products_info';

    protected $guarded = ['id'];

    public $timestamps = true;



}
