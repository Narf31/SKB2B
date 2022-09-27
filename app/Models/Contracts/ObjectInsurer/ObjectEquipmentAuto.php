<?php

namespace App\Models\Contracts\ObjectInsurer;

use App\Models\Settings\Country;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehiclePurpose;
use Illuminate\Database\Eloquent\Model;


class ObjectEquipmentAuto extends Model
{

    protected $table = 'object_equipment';

    protected $guarded = ['id'];

    public $timestamps = false;



}
