<?php
namespace App\Classes\Export\TagModels\BSO;


use App\Classes\Export\TagModels\Characters\TagUser;
use App\Classes\Export\TagModels\TagModel;

class TagBsoActs extends TagModel {


    public function apply(){

        $replace_arr = [];

        if($act = $this->builder->first()){

            $replace_arr['act_number'] = $act->act_number;
            $replace_arr['time_create'] = getDateFormatRu($act->time_create);
            $replace_arr['type'] = $act->type ? $act->type->title : "";
            $replace_arr['bso_state'] = $act->bso_state ? $act->bso_state->title : "";
            $replace_arr['point_sale'] = $act->point_sale ? $act->point_sale->title : "";
            $replace_arr['user_from'] = $act->user_from ? $act->user_from->name : "";
            $replace_arr['user_to'] = $act->user_to ? $act->user_to->name : "";
            $replace_arr['bso_manager'] = $act->bso_manager ? $act->bso_manager->name : "";
            $replace_arr['act_org'] = $act->act_org ? $act->act_org->title_doc : "";

            $bso_tags = (new TagBsoItem($act->get_bsos()))->apply();


            $tags_user_from = [];
            $_tags_user_from = (new TagUser($act->user_from()->getQuery()))->apply();

            foreach($_tags_user_from as $key => $value){
                $tags_user_from["user_from_" . $key] = $value;
            }

            $tags_user_to = [];
            $_tags_user_to = (new TagUser($act->user_to()->getQuery()))->apply();

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
            $doc_user_from['???????? ???????????????????????? "??????????????"'] = $res;
        }


        $doc_user_to = [];
        foreach(TagUser::doc() as $key => $value){
            $res = [];
            foreach ($value as $_key => $_value){
                $res["user_to_{$_key}"] = $_value;
            }
            $doc_user_to['???????? ???????????????????????? "??????????????"'] = $res;
        }


        $doc = [
            '?????????????????? ???????? ????????<sup style="font-size: 75%;">(??????????)</sup>' => [
                'act_number' => '?????????? ????????',
                'time_create' => '????????/??????????',
                'type' => '?????? ????????',
                'bso_state' => '???????????? ??????',
                'point_sale' => '?????????? ????????????',
                'user_from' => '??????????',
                'user_to' => '??????????????',
                'bso_manager' => '??????????????????',
                'act_org' => '?????????????????????? ?????? ????????',
            ],
        ];

        $doc = array_merge($doc, $bso_doc, $doc_user_to, $doc_user_from);

        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }



        return $doc;
    }

}