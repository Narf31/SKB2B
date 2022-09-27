<?php

namespace App\Models\Contracts;

use App\Models\BSO\BsoItem;
use App\Models\Clients\GeneralSubjects;
use App\Models\Directories\InsuranceCompanies;
use App\Models\File;
use App\Models\Security\Security;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Organization
 *
 * @property integer $id
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereTitle($value)
 * @mixin \Eloquent
 * @property integer $next_act
 * @property string $default_purpose_payment
 * @property string $inn
 * @property float $limit_year
 * @property float $spent_limit_year
 * @property integer $is_actual
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereNextAct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereDefaultPurposePayment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereSpentLimitYear($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Organization whereIsActual($value)
 */
class Subjects extends Model
{
    protected $table = 'subjects';

    protected $guarded = ['id'];

    public $timestamps = false;


    const TYPE = [
        0 => 'Физическое лицо',
        1 => 'Юридическое лицо',
        2 => 'Физическое лицо',
        3 => 'Юридическое лицо',
    ];

    const DOC_TYPE = [
        0 => 'Паспорт гражданина РФ',
        1 => 'Загранпаспорт гражданина РФ',
        2 => 'Иностранный паспорт',
        3 => 'РФ В.У.',
        4 => 'Иностранные В.У.',
    ];

    const DOC_TYPE_UL = [
        1169 => 'Свидетельства о регистрации',
    ];


    public function general() {
        return $this->hasOne(GeneralSubjects::class, 'id', 'general_subject_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function data_fl() {
        return $this->hasOne(SubjectsFl::class, 'subject_id', 'id');
    }

    public function data_ul() {
        return $this->hasOne(SubjectsUl::class, 'subject_id', 'id');
    }


    public function get_info()
    {
        $subject = $this;
        $info = null;


        if($subject->type == 0 || $subject->type == 2){
            if($subject->data_fl) $info = $subject->data_fl;
            else $info = new SubjectsFl();
        }

        if($subject->type == 1 || $subject->type == 3){
            if($subject->data_ul) $info = $subject->data_ul;
            else $info = new SubjectsUl();
        }

        $info->subject_id = $this->id;

        return $info;

    }





    public static function saveOrCreateSubject($data)
    {


        $subject = null;

        if(!$subject)
        {
            $subject = Subjects::create(['type' => $data->type, 'phone' => $data->phone, 'email' => $data->email]);
        }

        $subject->saveDataSubject($data);

        return $subject->id;
    }

    public function saveDataSubject($data)
    {
        $subject = $this;



        $citizenship_id = (isset($data->citizenship_id)?$data->citizenship_id:0);
        if(isset($data->is_resident)){
            $citizenship_id = 51;
        }

        $comments = '';
        if(isset($data->comments)){
            $comments = $data->comments;
        }

        if(isset($data->bank_comments)){
            $comments = $data->bank_comments;
        }


        $subject::update([
            'type' => $data->type,
            'email' => isset($data->email)?$data->email:'',
            'phone' => isset($data->phone)?$data->phone:'',
            'is_resident' => (isset($data->is_resident)?1:0),
            'citizenship_id' =>$citizenship_id,
            'comments' =>$comments,
        ]);

        if((int)$data->type == 0 || (int)$data->type == 2)
        {
            $subject::update([
                'title' => $data->fio,
                'doc_type_id' => $data->doc_type,
                'doc_serie' => $data->doc_serie,
                'doc_number' => $data->doc_number,
            ]);

        }else{

            $subject::update([
                'title' => $data->title,
                'inn' => $data->inn,
                'ogrn' => $data->ogrn,
            ]);

        }

        $info = $subject->data();
        $info->updateInfo($data, $subject->id);


        return $subject;
    }


    public function data()
    {
        $subject = $this;
        $info = null;

        if($subject->type == 0 || $subject->type == 2){
            if($subject->data_fl) $info = $subject->data_fl;
            else $info = new SubjectsFl();
        }

        if($subject->type == 1 || $subject->type == 3){
            if($subject->data_ul) $info = $subject->data_ul;
            else $info = new SubjectsUl();
        }

        $info->subject_id = $subject->id;

        return $info;

    }


    public static function saveOrCreateOnlineSubject($data, $subject_id, $user_id)
    {
        if($data->type == 3){
            $data->type = 1;
        }

        if(!$subject = Subjects::where('id', $subject_id)->get()->first())
        {
            $subject = Subjects::create(['type' => $data->type, 'phone' => $data->phone, 'email' => $data->email, 'user_id' => $user_id]);
        }

        return $subject->saveDataSubject($data);
    }



    public static function getSubjectContract($contract, $subject_name){
        $subject = new Subjects();
        if($subject_name == 'insurer'){
            if($contract->insurer) {
                $subject = $contract->insurer;
            }else{
                $subject->save();
                $contract->insurer_id = $subject->id;
                $contract->save();
            }
        }

        if($subject_name == 'owner'){
            if($contract->owner) {
                $subject = $contract->owner;
            }else{
                $subject->save();
                $contract->owner_id = $subject->id;
                $contract->save();
            }
        }

        if($subject_name == 'beneficiar'){
            if($contract->beneficiar) {
                $subject = $contract->beneficiar;
            }else{
                $subject->save();
                $contract->beneficiar_id = $subject->id;
                $contract->save();
            }
        }
        return $subject;
    }

    public static function cloneSubject($subject_old)
    {
        $subject = $subject_old->replicate();
        $subject->save();

        $info = $subject_old->data()->replicate();
        $info->subject_id = $subject->id;
        $info->save();
        return $subject;
    }
}
