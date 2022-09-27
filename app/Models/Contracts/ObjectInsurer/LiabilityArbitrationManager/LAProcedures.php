<?php

namespace App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager;

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


class LAProcedures extends Model
{

    protected $table = 'la_procedures';

    protected $guarded = ['id'];

    public $timestamps = false;


    const BANKRUPTCY_PROCEDURES = [
        0 => 'Конкурсное производство',
        1 => 'Наблюдение',
        2 => 'Финансовое оздоровление',
        3 => 'Внешнее управление',
        4 => 'Мировое соглашение',
    ];

    const BANKRUPTCY_PROCEDURES_ROD = [
        0 => 'конкурсного производства',
        1 => 'наблюдения',
        2 => 'финансового оздоровления',
        3 => 'внешнего управления',
        4 => 'мирового соглашения',
    ];


}
