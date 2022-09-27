<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;


class RoleSubpermissions extends Model
{
    protected $table = 'roles_subpermissions';

    protected $guarded = [];
    public $incrementing = false;

    public $timestamps = false;


    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }



}