<?php

namespace App\Models\Contracts\ObjectInsurer;

use App\Models\BSO\BsoItem;
use App\Models\Directories\InsuranceCompanies;
use App\Models\File;
use App\Models\Security\Security;
use App\Models\User;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehiclePurpose;
use App\Traits\Models\GetRelatedTrait;
use Illuminate\Database\Eloquent\Model;


class ObjectInsurerFlats extends Model
{

    protected $table = 'object_insurer_flats';

    protected $guarded = ['id'];

    public $timestamps = false;



}
