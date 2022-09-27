<?php
namespace App\Classes\Export\TagModels\BSO;


use App\Classes\Export\TagModels\TagModel;

class TagBsoItem extends TagModel {


    public function apply(){
        $replace_arr = [
            'bso_list' => []
        ];

        foreach ($this->builder->get() as $key => $bso){

            $row = [];

            $row['number'] = $key+1;
            $row['organization'] = $bso->supplier_org ? $bso->supplier_org->title : "";
            $row['insurance'] = $bso->supplier ? $bso->supplier->title : "";
            $row['bso_type'] = $bso->product ? $bso->product->title : "";
            $row['bso_title'] = $bso->bso_title;
            $row['bso_blank_title'] = $bso->bso_blank_title;
            $row['bso_blank_type'] = $bso->type ? $bso->type->title : "";
            $row['location'] = $bso->location ? $bso->location->title : "";
            $row['state'] = $bso->state ? $bso->state->title : "";

            $replace_arr['bso_list'][] = $row;
        }

        $replace_arr['bso_count'] = count($replace_arr['bso_list']);

        return $replace_arr;
    }


    public static function doc(){

        $doc = [

            'Данные БСО<sup style="font-size: 75%;">(общие)</sup>' => [
                'bso_list' => '<b style="color: #333">Тег списка БСО</b>',
                'bso_count' => 'Количество БСО',
            ],

            'Данные БСО<sup style="font-size: 75%;">(список)</sup>' => [
                'number' => '№ п/п',
                'organization' => 'Организация',
                'insurance' => 'Страховая компания',
                'bso_type' => 'Вид страхования',
                'bso_title' => '№ полиса / квит. / сер.карт с',
                'bso_blank_title' => '№ бланка',
                'bso_blank_type' => 'Тип бланка',
                'location' => 'Событие',
                'state' => 'Статус',
            ]
        ];



        foreach($doc as $k => $v){
            asort($doc[$k]);
        }


        return $doc;
    }

}