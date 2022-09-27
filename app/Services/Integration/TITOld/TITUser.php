<?php

namespace App\Services\Integration\TITOld;

use App\Models\Organizations\Organization;
use App\Models\Settings\SettingsSystem;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use Mockery\Exception;

class TITUser
{

    public function getDataInfo($start, $request)
    {

        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Метод не найден";
        $res->count_all = 0 ;

        $select = "SELECT count(*) as count_all FROM `users` where id in(SELECT distinct id_user FROM prod_policies where id_insurance in (17, 52) )";
        $results = TITSend::send($select);

        if($results && isset($results[0])){
            $res->state = 0;
            $res->msg = "";
            $res->count_all = $results[0]->count_all;
        }

        return $res;
    }


    public function updateDataInfo($start, $counts, $request, $count_all)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Тит не настроен!";

        $select = "SELECT * FROM `users` where id in(SELECT distinct id_user FROM prod_policies where id_insurance in (17, 52) )";
        $results = TITSend::send($select);

        if($results){
            self::saveUsers($results);
            $res->state = 1;
            $res->msg = '';
            $res->progressbar = count($results);
            $res->start = count($results);
        }



        return $res;
    }


    public static function getOrganization($export_id)
    {
        $organization = Organization::where('export_id', $export_id)->get()->first();
        if(!$organization){
            $result = TITSend::send("SELECT * FROM `partners` where id = $export_id");
            if($result) $result = $result[0];
            $organization = new Organization();
            $organization->export_id = $export_id;
            $organization->title = $result->name;
            $organization->title_doc = $result->companyFullName;
            $organization->org_type_id = 2;
            $organization->inn = $result->inn;
            $organization->kpp = $result->kpp;
            $organization->email = $result->email;
            $organization->phone = $result->phone;

            $organization->general_manager = $result->companyDirector;
            $organization->fact_address = $result->companyRealStreet;
            $organization->address = $result->companyRealStreet;

            $organization->is_actual = 1;
            if((int)$result->blocked == 1) $organization->is_actual = 0;

            $organization->points_sale_id = 1;
            $organization->financial_group_id = 0;
            $organization->products_sale = \GuzzleHttp\json_encode(['2','4','6']);

            $organization->save();
        }

        return $organization;
    }


    public static function clearUsers(){
        //Пользователи
        \DB::table('users')->where('export_user_id', '>', 0)->delete();
        \DB::table('subjects_physical')->where('is_export', 1)->delete();
        return true;
    }

        //Преносит за 1 минуту
    public static function saveUsers($results)
    {

        $is_parent = 0;

        foreach ($results as $user){
            $organization = self::getOrganization($user->partner_id);

            if((int)$user->partner_id == 521){
                $role_id = 2;
                $department_id = 3;
            }else{
                $role_id = 4;
                $department_id = 5;
            }


            $email = "$user->id@export.com";
            if(isset($user->email) && strlen($user->email) > 3){
                $email = $user->email;
            }else{
                if(isset($user->login) && strlen($user->login) > 3){
                    $email = $user->login;
                }
            }

            $_is_send = 1;
            $check_user = User::where('email', $email)->get()->last();
            if($check_user){
                $email = "duble-{$check_user->id}-{$user->id}-$email-".UUID_V4();
                $_is_send = 0;
            }

            $pass = GeneralSubjectsInfo::createGeneralSubjectPassword(8);

            $password = bcrypt(trim($pass));
            $subject = self::getUserSubject($user);


            \DB::table('users')->insert([
                'name' => "{$user->name}",
                'email' => $email,
                'mobile_phone' => $user->phone,
                'password' => $password,
                'subject_type_id' => 1,
                'subject_id' => $subject->id,
                'role_id' => $role_id,
                'organization_id' => $organization->id,
                'status_user_id' => 1,
                'temp_status_user_id' => ((int)$user->block==0)?0:1,
                'is_parent' => $is_parent,
                'financial_group_id' => 0,
                'department_id' => $department_id,
                'point_sale_id' => $organization->point_sale_id,
                'export_user_id' => $user->id,
            ]);

            User::where('export_user_id', $user->id)->update($organization->getArrayAgentContract());


        }

        $organizations = Organization::where('org_type_id', '!=', 1)->get();
        $curator = User::where('export_user_id', 365)->get()->first();
        foreach ($organizations as $organization){
            $organization->curator_id = $curator->id;
            $organization->save();
            $organization->updateUsersAgentContract();
        }


        return true;
    }


    public static function getUserSubject($info)
    {
        $Fullname = $info->name;
        $_arr = explode(' ', $Fullname);

        $last_name = (isset($_arr[0]))?$_arr[0]:'';
        $first_name = (isset($_arr[1]))?$_arr[1]:'';

        $patronymic = '';
        if(isset($_arr[2])){
            $patronymic = str_replace("$last_name $first_name ", '', $Fullname);
        }



        return Physical::create([
            'second_name' => $last_name,
            'first_name' => $first_name,
            'middle_name' => $patronymic,
            'is_export' => 1,
        ]);
    }






}