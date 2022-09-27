<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class GeneralInteractionsConnections extends Model
{


    protected $table = 'general_interactions_connections';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPE = [
        1 => 'СРО',
        2 => 'Группа',
    ];

    public function general_organization(){
        return $this->hasOne(GeneralSubjects::class, 'id','general_organization_id');
    }


    public function saveData($data)
    {

        $this->general_organization_id = $data->general_organization_id;
        $this->job_position = $data->job_position;
        $this->date_from = strlen($data->date_from > 9)?getDateFormatEn($data->date_from):null;
        $this->date_to = strlen($data->date_to > 9)?getDateFormatEn($data->date_to):null;
        $this->save();

        return true;
    }


}
