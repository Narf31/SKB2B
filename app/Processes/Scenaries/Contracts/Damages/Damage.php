<?php

namespace App\Processes\Scenaries\Contracts\Damages;


use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsInsurer;
use App\Models\Orders\DamageOrder;
use App\Models\Orders\Damages;
use App\Models\Settings\PointsSale;
use App\Models\User;
use App\Processes\Operations\Contracts\Damages\DamagesLogs;
use App\Processes\Operations\Contracts\Object\ContractObject;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Subjects;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Products\CalcFlats;

class Damage {




    public static function create($bso, $user_id, $create_title = '', $comments = ''){

        $agent = $bso->contract->agent;

        $damage = Damages::create([
            'status_id' => 0,
            'type_id' => 1,
            'user_id' => $user_id,
            'bso_id' => $bso->id,
            'position_type_id' => 1,
            'supplier_organization_id' => $bso->supplier_org->id,
            'contract_id' => $bso->contract->id,
            'product_id' => $bso->product_id,
            'agent_id' => $agent->id,
            'agent_organization_id' => $agent->organization_id,
            'agent_parent_id' => $agent->parent_id,
            'agent_curator_id' => $agent->curator_id,
            'insurer_id'=> $bso->contract->insurer->general_subject_id,
            'comments' => $comments,
            'phone' => $bso->contract->insurer->phone,
            'email' => $bso->contract->insurer->email,

        ]);

        DamagesLogs::setLogs($damage->id, $user_id, $damage->status_id, Damages::STATYS[$damage->status_id], 'Создана', $create_title);

        return $damage;

    }

    public static function save($damage_id, $user_id, $status_id, $data){
        $result = (object)['state'=> false, 'msg' => 'Не удалось сохранить данные!'];

        $damage = Damages::find($damage_id);
        if($damage->status_id == $status_id){
            $event_title = 'Изменения данных';
        }else{
            $event_title = 'Смена статуса';
        }

        $damage->city_id = $data->city_id;
        $damage->position_type_id = $data->position_type_id;

        if($damage->position_type_id == 1){
            $damage->point_sale_id = $data->point_sale_id;

            $point_sale = PointsSale::findOrFail($data->point_sale_id);

            $damage->address = $point_sale->address;
            $damage->latitude = $point_sale->latitude;
            $damage->longitude = $point_sale->longitude;

        }else{

            $damage->point_sale_id = null;

            $damage->address = $data->address;
            $damage->latitude = $data->latitude;
            $damage->longitude = $data->longitude;
        }


        $damage->begin_date = setDateTimeFormat($data->date.' '.$data->time);
        $damage->insurer_type_id = $data->insurer_type_id;
        $damage->phone = $data->phone;
        $damage->email = $data->email;
        $damage->comments = $data->comments;

        if($status_id == 1 && $damage->position_type_id != 0){
            $status_id = 2;
        }

        $damage->status_id = $status_id;
        $damage->save();

        DamagesLogs::setLogs($damage->id, $user_id, $damage->status_id, Damages::STATYS[$damage->status_id], $event_title, '');

        $result = (object)['state'=> true, 'msg' => ''];

        return $result;
    }


    public static function save_status_payment($damage_id, $user_id, $status_payments_id, $payments_comments){
        $result = (object)['state'=> false, 'msg' => 'Не удалось сохранить данные!'];

        $event_title = "Изменения статуса платежа - ";

        $damage = Damages::find($damage_id);
        if($damage->info){
            $info = $damage->info;
        }else{
            $info = new DamageOrder();
            $info->order_id = $damage_id;
        }
        $info->status_payments_id = $status_payments_id;
        $info->payments_comments = $payments_comments;

        $info->save();

        $event_title .= DamageOrder::STATUS_PAYMENT[$status_payments_id];


        DamagesLogs::setLogs($damage->id, $user_id, $damage->status_id, Damages::STATYS[$damage->status_id], $event_title, '');


        $result = (object)['state'=> true, 'msg' => ''];

        return $result;
    }

    public static function refresh_payment_total($damage_id)
    {
        $damage = Damages::find($damage_id);
        if($damage->info){
            $info = $damage->info;
        }else{
            $info = new DamageOrder();
            $info->order_id = $damage_id;
        }

        $info->payments_total = ($damage->payments)?$damage->payments->sum('payment_total'):0;
        $info->save();

        return true;
    }


    public static function assign($damage_id, $user_id, $work_user_id){

        $damage = Damages::find($damage_id);
        $users = User::find($work_user_id);
        $damage->status_id = 2;
        $damage->work_user_id = $users->id;
        $damage->work_status_id = 0;
        $damage->save();

        DamagesLogs::setLogs($damage->id, $user_id, $damage->status_id, Damages::STATYS[$damage->status_id], "Назначен - {$users->name}", '', 'bg-yellow');
        return (object)['state'=> true, 'msg' => ''];
    }


    public static function save_work_status($damage_id, $work_user_id, $work_status, $work_comments){
        $result = (object)['state'=> true, 'msg' => ''];
        $damage = Damages::find($damage_id);
        $users = User::find($work_user_id);

        $color = '';
        if($work_status == 1){
            $damage->status_id = 1;
            $color = 'bg-red';
        }
        if($work_status == 2){
            $color = 'bg-green';
            if($damage->position_type_id != 0){
                $work_status = 3;
            }
        }

        if($work_status == 4){
            $damage->status_id = 3;
            $color = 'bg-blue';
        }

        $damage->work_user_id = $work_user_id;
        $damage->work_status_id = $work_status;
        $damage->save();

        DamagesLogs::setLogs($damage->id, $work_user_id, $damage->status_id, Damages::STATYS[$damage->status_id],
            Damages::WORK_STATYS[$work_status]." - {$users->name} $work_comments", '', $color);

        return $result;
    }
}