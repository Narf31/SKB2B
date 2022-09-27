<?php

namespace App\Models\Contracts;

use App\Classes\Export\TagModels\Contracts\TagContractsInsurer;
use App\Models\Clients\GeneralSubjects;
use App\Models\Settings\Country;
use Illuminate\Database\Eloquent\Model;

class ContractsInsurer extends Model
{
    protected $table = 'contracts_insurer';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TAG_MODEL = TagContractsInsurer::class;


    public function citizenship() {
        return $this->hasOne(Country::class, 'id', 'citizenship_id');
    }

    public function subject(){
        return $this->hasOne(Subjects::class, 'id', 'subject_id');
    }

    public function general() {
        return $this->hasOne(GeneralSubjects::class, 'id', 'general_id');
    }

    const DOC_TYPE = [
        0 => 'Паспорт гражданина РФ',
        2 => 'Иностранный паспорт',
    ];



}
