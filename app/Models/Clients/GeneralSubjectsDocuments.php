<?php

namespace App\Models\Clients;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Contracts\SubjectsFlDocType;
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

class GeneralSubjectsDocuments extends Model
{


    protected $table = 'general_subjects_documents';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPE = [
        0 => 'Паспорт гражданина РФ',
        1 => 'Водительское удостоверение РФ',
        2 => 'Водительское удостоверение ГТН',
        3 => 'Водительское удостоверение иностранного государства',
        4 => 'Военный билет солдата (матроса, сержанта, старшины) РФ',
        5 => 'Временное удостоверение личности гражданина РФ',
        6 => 'Удостоверение личности офицера РФ',
        7 => 'Иностранный паспорт',
        8 => 'Загранпаспорт гражданина РФ',
        9 =>  'Свидетельство о государственной регистрации (запись в ЕГРЮЛ)'
    ];

    public function general() {
        return $this->hasOne(GeneralSubjects::class, 'id', 'general_subject_id');
    }

    public function type_doc() {
        return $this->hasOne(SubjectsFlDocType::class, 'isn', 'type_id');
    }



}
