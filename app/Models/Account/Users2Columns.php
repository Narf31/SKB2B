<?php


namespace App\Models\Account;

use Illuminate\Database\Eloquent\Model;

class Users2Columns extends Model
{
    public $table = 'users2columns';

    public $timestamps = false;

    public function table_columns()
    {
        return $this->hasMany('table_columns', 'id', 'column_id');
    }
}