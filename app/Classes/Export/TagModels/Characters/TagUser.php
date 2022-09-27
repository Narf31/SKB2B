<?php
namespace App\Classes\Export\TagModels\Characters;

use App\Classes\Export\TagModels\TagModel;

class TagUser extends TagModel {

    public function apply(){

        $replace_arr = [
            'name' => '',
            'status_user' => '',
            'subject_type' => '',
            'passport_series' => '',
            'passport_number' => '',
            'title' => '',
            'ogrn' => '',
            'inn' => '',
            'bik' => '',
            'bank' => '',
            'rs' => '',
            'email' => '',
            'work_phone' => '',
            'mobile_phone' => '',
            'role' => '',
            'organization' => '',
            'department' => '',
            'point_sale' => '',
            'is_parent' => '',
            'parent' => '',
            'agent_contract_title' => '',
            'agent_contract_begin_date' => '',
            'agent_contract_end_date' => '',
        ];

        if($user = $this->builder->first()) {

            $replace_arr['name'] = $user->name;
            $replace_arr['status_user'] = $user->status_user_ru('status_user_id');
            $replace_arr['subject_type'] = $user->subject_type_id == 1 ? "Физическое лицо" : "Юридическое лицо";


            $replace_arr['passport_series'] = $user->info && $user->subject_type_id == 1 ? $user->info->passport_series : "";
            $replace_arr['passport_number'] = $user->info && $user->subject_type_id == 1 ? $user->info->passport_number : "";


            $replace_arr['title'] = $user->info && $user->subject_type_id == 2 ? $user->info->title : "";
            $replace_arr['ogrn'] = $user->info && $user->subject_type_id == 2 ? $user->info->ogrn : "";
            $replace_arr['inn'] = $user->info && $user->subject_type_id == 2 ? $user->info->inn : "";
            $replace_arr['bik'] = $user->info && $user->subject_type_id == 2 ? $user->info->bik : "";
            $replace_arr['bank'] = $user->info && $user->subject_type_id == 2 ? $user->info->bank : "";
            $replace_arr['rs'] = $user->info && $user->subject_type_id == 2 ? $user->info->rs : "";

            $replace_arr['email'] = $user->email;
            $replace_arr['work_phone'] = $user->work_phone;
            $replace_arr['mobile_phone'] = $user->mobile_phone;
            $replace_arr['role'] = $user->role ? $user->role->title : "";
            $replace_arr['organization'] = $user->organization ? $user->organization->title_doc : "";
            $replace_arr['department'] = $user->department ? $user->department->title : "";
            $replace_arr['point_sale'] = $user->point_sale ? $user->point_sale->title : "";
            $replace_arr['is_parent'] = $user->is_parent ? "Да" : "Нет";
            $replace_arr['parent'] = $user->parent ? $user->parent->name : "";


            $replace_arr['agent_contract_title'] = $user->agent_contract_title;
            $replace_arr['agent_contract_begin_date'] = date('d.m.Y', strtotime($user->agent_contract_begin_date));
            $replace_arr['agent_contract_end_date'] = date('d.m.Y', strtotime($user->agent_contract_end_date));

        }
        return $replace_arr;
    }


    public static function doc(){

        $doc = [
            'Теги пользователя' => [
                'name' => 'ФИО',
                'status_user' => 'Статус',
                'subject_type' => 'Тип субъекта',
                'passport_series' => '(Физ)Серия паспорта',
                'passport_number' => '(Физ)Номер паспорта',
                'title' => '(Юр)Название',
                'ogrn' => '(Юр)ОГРН',
                'inn' => '(Юр)ИНН',
                'bik' => '(Юр)БИК',
                'bank' => '(Юр)Банк',
                'rs' => '(Юр)РС',
                'email' => 'Email',
                'work_phone' => 'Рабочий телефон',
                'mobile_phone' => 'Мобильный телефон',
                'role' => 'Роль',
                'organization' => 'Организация',
                'department' => 'Подразделение',
                'point_sale' => 'Точка продаж',
                'is_parent' => 'Является руководителем',
                'parent' => 'Руководитель',
                'agent_contract_title' => 'Номер агентского договора',
                'agent_contract_begin_date' => 'Агентский договор с',
                'agent_contract_end_date' => 'Агентский договор по',
            ]

        ];

        foreach($doc as $k => $v){
             asort($doc[$k]);
        }


        return $doc;
    }

}