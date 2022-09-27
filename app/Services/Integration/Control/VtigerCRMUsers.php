<?php

namespace App\Services\Integration\Control;

use App\Models\Subject\Physical;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use function GuzzleHttp\Psr7\str;
use Mockery\Exception;

class VtigerCRMUsers
{

    public function getDataInfo($start, $request)
    {
        if(isset($request) && isset($request->delete) && $request->delete == 1){
            //Пользователи
            \DB::table('users')->where('export_user_id', '>', 0)->delete();
            \DB::table('subjects_physical')->where('is_export', 1)->delete();
        }

        return VtigerCRMSend::getDataInfo('user', "view=info");
    }

    public function updateDataInfo($start, $counts, $request, $count_all)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "VtigerCRM не настроен!";

        $response = VtigerCRMSend::getDataInfo('user', "view=updata&start={$start}&counts={$counts}&count_all={$count_all}");
        if($response->result){
            $this->setUsers($response->result, $request);
            $res->state = $response->state;
            $res->msg = $response->msg;
            $res->progressbar = $response->progressbar;
            $res->start = $response->start;

        }

        return $res;
    }

    public function connectionDataInfo()
    {
        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "Пользователи связаны";

        //Проставляем руководителей
        $parent_users = User::where('export_parent_id', '>', 0);
        $parent_users->select(['export_parent_id']);
        $users = User::query();
        $users->whereRaw('`export_user_id` IN (' . $parent_users->toSql() . ')', $parent_users->getBindings());

        foreach ($users->get() as $user){
            $user->is_parent = 1;
            if((int)$user->export_parent_id > 0){
                $parent = self::getExportUserId($user->export_parent_id);
                if($parent){
                    $user->parent_id = $parent->id;
                }
            }
            $user->save();
        }

        return $res;
    }


    public static function getExportUserId($id, $type = 'export_user_id')
    {
        return User::where($type, $id)->get()->first();
    }


    private function setUsers($users, $request)
    {
        foreach ($users as $user){
            $email = "$user->id@export.com";
            if(isset($user->email1) && strlen($user->email1) > 3){
                $email = $user->email1;
            }


            $check_user = User::where('email', $email)->get()->last();
            if($check_user){
                //$check_user->export_user_id = $user->id;
                //$check_user->export_parent_id = $user->reports_to_id;
                //$check_user->save();
                $email = "duble-{$check_user->id}-{$user->id}-$email";
            }

            $role_id = 9;
            $organization_id = 1;

            $pass = GeneralSubjectsInfo::createGeneralSubjectPassword(8);

            $temp_email = explode("@", $email);
            if($temp_email[1] == 'riks-ins.ru'){
                $pass = $email;
            }

            $password = bcrypt(trim($pass));
            $subject = $this->getUserSubject($user);
            $is_parent = 0;

            \DB::table('users')->insert([
                'name' => "{$user->last_name} {$user->first_name}",
                'email' => $email,
                'password' => $password,
                'subject_type_id' => 1,
                'subject_id' => $subject->id,
                'role_id' => $role_id,
                'organization_id' => $organization_id,
                'status_user_id' => 1,
                'is_parent' => $is_parent,
                'financial_group_id' => 0,
                'department_id' => 7,
                'point_sale_id' => 1,
                'sales_condition' => 1,
                'export_user_id' => $user->id,
                'export_parent_id' => (int)$user->reports_to_id,
            ]);


        }
        return true;
    }

    public function getUserSubject($info)
    {
        $second = $info->last_name;

        $first = $info->first_name;
        $first_arr = explode(' ', $first);
        $middle = '';
        if(isset($first_arr) && isset($first_arr[1]) && strlen($first_arr[1]) > 0){
            $first = $first_arr[0];
            $middle = $first_arr[1];
        }


        return Physical::create([
            'second_name' => $second,
            'first_name' => $first,
            'middle_name' => $middle,
            'is_export' => 1,
        ]);
    }


}