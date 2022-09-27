<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;


class Subpermission extends Model
{
    protected $table = 'permissions_sub';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function permission(){
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function subpermission_roles(){
        return $this->hasMany(RoleSubpermissions::class, 'subpermission_id');
    }



}