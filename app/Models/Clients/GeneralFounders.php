<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class GeneralFounders extends Model
{


    protected $table = 'general_founders';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TYPE = [
        1 => 'Учередители',
        2 => 'Бенефициары',
    ];

    public function general_founders(){
        return $this->hasOne(GeneralSubjects::class, 'id','general_founders_id');
    }


    public function saveData($data)
    {

        $this->general_founders_id = $data->general_founders_id;
        $this->share = getFloatFormat($data->share);
        $this->share_sum = getFloatFormat($data->share_sum);
        $this->save();

        return true;
    }


}
