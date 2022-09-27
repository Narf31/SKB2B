<?php

namespace App\Http\Controllers\BSO\Transfer;

use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoCarts;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\User;
use Illuminate\Http\Request;

class TransferFinishBsoController extends Controller
{


    public function transfer_bso_act_agent(Request $request)
    {
        $act = BsoActs::findOrFail($request->bso_act_id);
        $bsos = BsoLogs::where('bso_act_id', $act->id)->groupby('bso_id')->distinct()->get();


        $bso_cart = new BsoCarts();
        $bso_cart->bso_cart_type = 1;
        $bso_cart->time_create = $act->time_create;
        $bso_cart->user_id_from = $act->user_id_from;
        $bso_cart->bso_manager_id = $act->bso_manager_id;
        $bso_cart->tp_id = $act->tp_id;
        $bso_cart->user_id_to = $request->agent_id;
        $bso_cart->save();

        foreach ($bsos as $tempBSO){
            $bso_items = $tempBSO->bso;
            $bso_items->is_reserved = 1;
            $bso_items->bso_cart_id = $bso_cart->id;
            $bso_items->save();
        }

        return redirect("/bso/transfer/transfer_bso/?bso_cart_id={$bso_cart->id}");
    }


    public function transfer_bso(Request $request)
    {
        $bso_cart_id = (int)$request->bso_cart_id;

        if ( $bso_cart_id == 0 ) {
            return response();
        }

        $bso_items = BsoItem::where('bso_cart_id', $bso_cart_id)->get();

        if ( (int) $bso_items->count() == 0 ) {
            return response('empty cart');
        }

        $object = BsoCarts::find($bso_cart_id);
        $user_id_to        = $object->user_id_to;
        $bso_cart_type     = $object->bso_cart_type;
        $tp_id             = $object->tp_id;
        $tp_new_id         = $object->tp_new_id;
        $tp_bso_manager_id = $object->tp_bso_manager_id;

        $bso_manager_id = (int) auth()->id();
        $log_time       = $time_create = date( 'Y-m-d H:i:s' );
        $ip_address     = $_SERVER['REMOTE_ADDR'];


        $bso_acts = BsoActs::select((\DB::raw('max(act_number_int) as max_act_number')))->get()->first();
        if($bso_acts->max_act_number){
            $act_number_int = (int)$bso_acts->max_act_number + 1;
        }else{
            $act_number_int = 1;
        }

        $bso_items = BsoItem::where('bso_cart_id', $bso_cart_id);

        $act_number = str_pad( $act_number_int, 6, '0', STR_PAD_LEFT );

        // Передача со склада агенту
        if ( $bso_cart_type == 1) {
            $location_from = 0; // Склад БСО
            $location_to   = 1; // Агент
            $bso_state_id  = 0;
            $user_org_id = User::find($user_id_to)->organization_id;


            $acts = BsoActs::create([
                'time_create' => $time_create,
                'type_id' => '1',
                'user_id_from' => $bso_manager_id,
                'user_id_to' => $user_id_to,
                'bso_manager_id' => $bso_manager_id,
                'location_from' => $location_from,
                'location_to' => $location_to,
                'bso_state_id' => $bso_state_id,
                'act_number' => $act_number,
                'act_number_int' => $act_number_int,
                'bso_cart_id' => $bso_cart_id,
                'act_name' => 'Акт передачи БСО агенту',
                'tp_id' => $tp_id,
            ]);

            $acts->setItemsCarts($bso_cart_id);

            $bso_act_id = $acts->id;

            // добавляем в лог передачу от сотрудника БСО -> агенту

            $sql = "insert into bso_logs (log_time, bso_id, bso_act_id, bso_state_id, bso_location_id, bso_user_from, bso_user_to, user_id, ip_address)
	SELECT '$log_time', id, '$bso_act_id', '$bso_state_id', '1', '$bso_manager_id', '$user_id_to', '$bso_manager_id', '$ip_address' from bso_items where bso_cart_id=$bso_cart_id ";
            \DB::insert($sql);

            // обновляем статусы БСО -> агенту
            $bso_items->update([
                'point_sale_id' => $tp_id,
                'location_id' => $location_to,
                'state_id' => $bso_state_id,
                'user_id' => $user_id_to,
                'agent_id' => $user_id_to,
                'user_org_id' => $user_org_id,
                'agent_organization_id' => $user_org_id,
                'bso_cart_id' => 0,
                'is_reserved' => 0,
                'realized_act_id' => 0,
                'last_operation_time' => $log_time,
                'transfer_to_agent_time' => $log_time
            ]);



        }

        // Передача БСО на точку продаж НОВАЯ
        if ( $bso_cart_type == 2 ) {
            $location_from = 0; // Склад БСО
            $location_to   = 0; // ТП
            $bso_state_id  = 0;


            $acts = BsoActs::create([
                'time_create' => $time_create,
                'type_id' => '2',
                'user_id_from' => $bso_manager_id,
                'user_id_to' => $tp_bso_manager_id,
                'bso_manager_id' => $bso_manager_id,
                'location_from' => $location_from,
                'location_to' => $location_to,
                'bso_state_id' => $bso_state_id,
                'act_number' => $act_number,
                'act_number_int' => $act_number_int,
                'bso_cart_id' => $bso_cart_id,
                'tp_id' => $tp_new_id,
                'act_name' => 'Акт передачи БСО на точку продаж'
            ]);

            $acts->setItemsCarts($bso_cart_id);

            $bso_act_id = $acts->id;


            // добавляем в лог передачу от сотрудника БСО -> агенту
            $sql = "insert into bso_logs (log_time, bso_id, bso_act_id, bso_state_id, bso_location_id, bso_user_from, bso_user_to, user_id, ip_address)
	SELECT '$log_time', id, '$bso_act_id', state_id, '0', '$bso_manager_id', '$tp_bso_manager_id', '$bso_manager_id', '$ip_address' from bso_items where bso_cart_id=$bso_cart_id ";
            \DB::insert($sql);

            // обновляем статусы БСО -> ТП
            $bso_items->update([
                'point_sale_id' => $tp_new_id,
                'location_id' => 0,
                'user_id' => 0,
                'user_org_id' => 0,
                'agent_id' => 0,
                'bso_cart_id' => 0,
                'is_reserved' => 0,
                'last_operation_time' => $log_time
            ]);

        }


        // обновляем статус корзины
        $object->cart_state_id = 1;
        $object->save();

        return response($acts->id);


    }

}
