<?php
namespace App\Classes\Export\TagModels\BSO;


use App\Classes\Export\TagModels\Characters\TagUser;
use App\Classes\Export\TagModels\TagModel;

class TagBsoCarts extends TagModel {


    public function apply(){

        $replace_arr = [];

        if($cart = $this->builder->first()){
            
            $replace_arr['id'] = $cart->id;
            $replace_arr['bso_cart_type'] = $cart->point_sale ? $cart->point_sale->title : "";
            $replace_arr['bso_manager'] = $cart->type ? $cart->type->title : "";
            $replace_arr['cart_state'] = $cart->state_car_ru('bso_state_id');
            $replace_arr['curr_tp'] = $cart->curr_point_sale ? $cart->curr_point_sale->title : "";
            $replace_arr['time_create'] = date('d.m.Y', strtotime($cart->time_create));
            $replace_arr['tp_bso_manager'] = $cart->tp_bso_manager ? $cart->tp_bso_manager->name : "";
            $replace_arr['tp'] = $cart->point_sale ? $cart->point_sale->title : "";
            $replace_arr['user_from'] = $cart->user_from ? $cart->user_from->name : "";
            $replace_arr['user_to'] = $cart->user_to ? $cart->user_to->name : "";


            $bso_tags = (new TagBsoItem($cart->bso_items()->getQuery()))->apply();


            $tags_user_from = [];
            $_tags_user_from = (new TagUser($cart->user_from()->getQuery()))->apply();

            foreach($_tags_user_from as $key => $value){
                $tags_user_from["user_from_" . $key] = $value;
            }

            $tags_user_to = [];
            $_tags_user_to = (new TagUser($cart->user_to()->getQuery()))->apply();

            foreach($_tags_user_to as $key => $value){
                $tags_user_to["user_to_" . $key] = $value;
            }


            $replace_arr = array_merge(
                $replace_arr,
                $tags_user_from,
                $tags_user_to,
                $bso_tags
            );
            
        }

        return $replace_arr;
    }



    public static function doc(){
        
        $bso_doc = TagBsoItem::doc();


        $doc_user_from = [];
        foreach(TagUser::doc() as $key => $value){
            $res = [];
            foreach ($value as $_key => $_value){
                $res["user_from_{$_key}"] = $_value;
            }
            $doc_user_from['Пользователь "Передал"'] = $res;
        }



        $doc_user_to = [];
        foreach(TagUser::doc() as $key => $value){
            $res = [];
            foreach ($value as $_key => $_value){
                $res["user_to_{$_key}"] = $_value;
            }
            $doc_user_to['Пользователь "Получил"'] = $res;
        }


        $doc = [
            'Теги передачи БСО' => [
                'bso_cart_type' => 'Тип передачи',
                'bso_manager' => 'Менеджер',
                'cart_state' => 'Статус Передачи',
                'curr_tp' => 'Точка продаж текущая',
                'id' => 'ID передачи',
                'time_create' => 'Когда создана передача',
                'tp_bso_manager' => 'Менеджер точки продаж',
                'tp' => 'Точка продаж',
                'user_from' => 'Выдал',
                'user_to' => 'Принял',
            ]
        ];


        $doc = array_merge($doc, $bso_doc, $doc_user_from, $doc_user_to);


        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }


        return $doc;
    }

}