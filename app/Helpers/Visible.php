<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class Visible{


    /**
     * Накладывает на билдер ограничения по видимости для текущего пользователя
     * @param Builder $builder       -- билдер какой-либо модели,
     * @param $permission_group_name -- id группы пермишенов по которой ограничеваем выборку
     * @param string $user_field     -- названия полей модели в которых содержатся id-шники пользователей по которым определяем видимость
     * @param bool $strict           -- во всех указанных полях $user_field должны быть строго доступные пользователи(true); хотябы в одном(false)
     * @return Builder
     */
    public static function apply(Builder $builder, $permission_group_name, $user_field = 'user_id', $strict = false){

        return self::apply_for_user($builder, $permission_group_name, $user_field, $strict, auth()->user());

    }

    public static function apply_for_user(Builder $builder, $permission_group_name, $user_field = 'user_id', $strict = false, User $user){

        $visibility = $user->role->visibility($permission_group_name);

        $user_field = !is_array($user_field) ? [$user_field] : array_values($user_field);

        $allowed_users = [];

        if($visibility == 1){ //Все в рамках организации
            $allowed_users = $user->getAllOrgUsers()->toArray();
        }
        if($visibility == 2){  //Только свои
            $allowed_users[$user->id] = $user->id;
        }
        if($visibility == 3){ //Только свои и своих подчиненных
            $allowed_users = $user->getSubUsers()->toArray();
            $allowed_users[$user->id] = $user->id;
        }

        //$builder->where('kind_acceptance');

        $allowed_users_ids = array_keys($allowed_users);

        if(is_array($allowed_users_ids) && count($allowed_users_ids)>0){
            $builder->where(function($query) use ($allowed_users_ids, $user_field, $strict){
                foreach($user_field as $k => $field){
                    if($k = 0 || $strict){
                        $query->whereIn($field, $allowed_users_ids);
                    }else{
                        $query->orWhereIn($field, $allowed_users_ids);
                    }
                }
            });
        }

        return $builder;
    }


}