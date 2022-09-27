<?php

namespace App\Models\Acts;

use App\Classes\Export\TagModels\Acts\TagReportAct;
use App\Models\BSO\BsoItem;
use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSuppliers;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class ReportAct extends Model {

    use ActiveConstTrait;

    const FILES_DOC = 'report_acts/docs';

    const TAG_MODEL = TagReportAct::class;

    protected $table = 'reports_act';

    protected $guarded = ['id'];

    const TYPE = [
        0 => 'БСО',
        1 => 'Договоры'
    ];

    public function bso_supplier(){
        return $this->hasOne(BsoSuppliers::class, 'id', 'bso_supplier_id');
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'acts_sk_id');
    }

    public function create_user(){
        return $this->hasOne(User::class, 'id', 'create_user_id');
    }

    public function payments(){
        return $this->hasMany(Payments::class, 'acts_sk_id');
    }




}