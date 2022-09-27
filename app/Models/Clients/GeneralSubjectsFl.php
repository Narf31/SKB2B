<?php

namespace App\Models\Clients;

use App\Models\Contracts\Contracts;
use App\Models\Orders\Damages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GeneralSubjectsFl extends Model
{


    protected $table = 'general_subjects_fl';

    protected $guarded = ['id'];

    public $timestamps = false;

    const PROFESSIO = [
        0 => 'Не выбрано',
        1 => 'Арбитражный управляющий',
        2 => 'Депутат гос. думмы',
        3 => 'Студент',
    ];


    public function saveFrame($request)
    {

        $this->birthdate = getDateFormatEn($request->birthdate);


        $this->sex = $request->sex;
        $this->inn = $request->inn;
        $this->snils = $request->snils;
        $this->profession_id = (int)$request->profession_id;
        $this->save();
        return true;
    }


}
