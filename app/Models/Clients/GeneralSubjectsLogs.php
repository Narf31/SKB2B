<?php

namespace App\Models\Clients;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class GeneralSubjectsLogs extends Model
{


    protected $table = 'general_subjects_logs';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function setLogs($general_subject_id, $status_title)
    {

        GeneralSubjectsLogs::create([
            'general_subject_id' => $general_subject_id,
            'user_id' => auth()->id(),
            'text' => "$status_title",
            'date_sent' => getDateTime(),
        ]);

        return true;
    }


}
