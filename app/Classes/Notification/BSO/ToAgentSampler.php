<?php
namespace App\Classes\Notification\Contracts;

use App\Models\BSO\BsoItem;

class ToAgentSampler{

    public static function sample(BsoItem $bso_item){
        return $bso_item->agent ? collect([$bso_item->agent]) : collect([]);
    }
}