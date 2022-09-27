<?php

namespace App\Classes\Export\TagModels\Finance;


use App\Classes\Export\TagModels\BSO\TagBsoItem;
use App\Classes\Export\TagModels\TagModel;
use App\Models\BSO\BsoItem;

class TagReservation extends TagModel{



    public function apply(){

        $replace_arr = [];

        if($reservation = $this->builder->first()){

            $replace_arr['comment'] = isset($reservation->data['comment']) ? $reservation->data['comment'] : "";
            $replace_arr['payer_inn'] = isset($reservation->data['payer_inn']) ? $reservation->data['payer_inn'] : "";
            $replace_arr['payer_title'] = isset($reservation->data['payer_title']) ? $reservation->data['payer_title'] : "";
            $replace_arr['payer_kpp'] = isset($reservation->data['payer_kpp']) ? $reservation->data['payer_kpp'] : "";
            $replace_arr['payer_name'] = isset($reservation->data['payer_name']) ? $reservation->data['payer_name'] : "";
            $replace_arr['payer_address'] = isset($reservation->data['payer_address']) ? $reservation->data['payer_address'] : "";
            $replace_arr['recipient_inn'] = isset($reservation->data['recipient_inn']) ? $reservation->data['recipient_inn'] : "";
            $replace_arr['recipient_kpp'] = isset($reservation->data['recipient_kpp']) ? $reservation->data['recipient_kpp'] : "";
            $replace_arr['recipient_name'] = isset($reservation->data['recipient_name']) ? $reservation->data['recipient_name'] : "";
            $replace_arr['recipient_address'] = isset($reservation->data['recipient_address']) ? $reservation->data['recipient_address'] : "";

            $bso_items = BsoItem::query()->whereIn('bso_title', array_column($reservation->data['bso'], 'bso_number'));

            $bso_tags  = (new TagBsoItem($bso_items))->apply();

            $replace_arr = array_merge($replace_arr, $bso_tags);

        }

        return $replace_arr;

    }


    public static function doc(){

        $bso_doc = TagBsoItem::doc();
        $doc = [
            'Теги резерва' => [
                'comment' => 'Комментарий',
                'payer_inn' => 'Инн плательщика',
                'payer_title' => 'Название организации плательщика',
                'payer_kpp' => 'КПП плательщика',
                'payer_name' => 'ФИО плательщика',
                'payer_address' => 'Адрес плательщика',
                'recipient_inn' => 'Инн получателя',
                'recipient_kpp' => 'КПП получателя',
                'recipient_name' => 'ФИО получателя',
                'recipient_address' => 'Адрес получателя',
            ]
        ];

        foreach ($doc as $k => $v){
            asort($doc[$k]);
        }

        return array_merge($bso_doc, $doc);

    }


}