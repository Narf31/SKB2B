<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Model;

class GeneralPodftFl extends Model
{


    protected $table = 'general_podft_fl';

    protected $guarded = ['id'];

    public $timestamps = false;

    const MAIN_TYPE_EMPLOYMENT = [
        0 => 'Не выбрано',
        1 => 'Трудовая деятельность (работа на основании трудового договора)',
        2 => 'Является ИП',
        3 => 'Частная практика - Самозанятый',
        4 => 'Ведение домашнего хозяйства',
        5 => 'Иное с текстом',
    ];

    const JOB_CREDENTIALS = [
        0 => 'Не выбрано',
        1 => 'Лицо не влияющее на решение (ЛНР)',
        2 => 'Лицо влияющее на решение (ЛВР)',
        3 => 'Лицо принимающее решение (ЛПР)',
        4 => 'Ведение домашнего хозяйства',
        5 => 'Иное',
    ];

    const JOB_TYPE_ACTIVITY = [
        0 => 'Не выбрано',
        1 => 'Административно-управленческая',
        2 => 'Финансово-экономическая',
        3 => 'Информационно-техническая',
        4 => 'Юридическая',
        5 => 'Производство',
        6 => 'Продажи и сбыт',
        7 => 'Обслуживающая',
    ];


    public function general(){
        return $this->hasOne(GeneralSubjects::class, 'id','general_subject_id');
    }

    public function general_organization(){
        return $this->hasOne(GeneralSubjects::class, 'id','general_organization_id');
    }


    public function saveData($data)
    {

        $this->financial_business_objectives = $data->financial_business_objectives;
        $this->information_business_reputation = $data->information_business_reputation;
        $this->alleged_nature_relationship = $data->alleged_nature_relationship;
        $this->origin_ds_other_property = $data->origin_ds_other_property;
        $this->purpose_establishing_relationship = $data->purpose_establishing_relationship;
        $this->financial_position = $data->financial_position;


        $this->is_executor_state_municipal = isset($data->is_executor_state_municipal)?1:0;
        $this->is_recipient_grants = isset($data->is_recipient_grants)?1:0;
        $this->is_participant_targeted_programs_national = isset($data->is_participant_targeted_programs_national)?1:0;
        $this->is_recipient_state_support = isset($data->is_recipient_state_support)?1:0;

        $this->main_type_employment_id = (int)$data->main_type_employment_id;
        $this->main_type_employment_text = $data->main_type_employment_text;
        $this->general_organization_id = (int)$data->general_organization_id;

        $this->job_department_subdivision = $data->job_department_subdivision;
        $this->job_phone = $data->job_phone;
        $this->job_position = $data->job_position;
        $this->job_credentials_id = (int)$data->job_credentials_id;
        $this->job_type_activity_id = (int)$data->job_type_activity_id;
        $this->save();

        return true;
    }


}
