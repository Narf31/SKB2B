<?php

namespace App\Models\Clients;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicyGroup;
use App\Models\Directories\Products;
use App\Models\File;
use App\Models\Reports\ReportOrders;
use App\Models\Security\Security;
use App\Models\Settings\City;
use App\Models\Settings\FinancialGroup;
use App\Models\Settings\PointsSale;
use App\Models\Settings\TypeOrg;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GeneralSubjectsAddress extends Model
{


    protected $table = 'general_subjects_address';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPE = [
        0 => 'born',
        1 => 'register',
        2 => 'fact',
    ];


    const TYPE_NAME = [
        0 => 'Место рождения',
        1 => 'Адрес регистрации',
        2 => 'Адрес фактический',
    ];



}
