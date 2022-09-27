<?php

namespace App\Models\Vehicle;

use App\Models\File;
use App\Models\Security\Security;
use App\Models\Settings\FinancialGroup;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class VehicleColor extends Model
{
    protected $table = 'vehicle_color';

    protected $guarded = ['id'];

    public $timestamps = false;



}
