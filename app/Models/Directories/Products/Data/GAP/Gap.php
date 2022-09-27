<?php

namespace App\Models\Directories\Products\Data\GAP;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
use Illuminate\Database\Eloquent\Model;


class Gap extends Model
{
    protected $table = 'products_gap';

    protected $guarded = ['id'];

    public $timestamps = false;

    const OPTION = [
        1 => 'АВТО ССТ GAP - 1 год',
        2 => 'АВТО ССТ GAP - 2 года',
        3 => 'АВТО ССТ GAP - 3 года',
        4 => 'ФИНАНС ССТ GAP - 1 год',
        5 => 'ФИНАНС ССТ GAP - 2 год',
        6 => 'ФИНАНС ССТ GAP - 3 год',
        7 => 'ФИНАНС ССТ GAP - 4 год',
        8 => 'ФИНАНС ССТ GAP - 5 лет',
        9 => 'Пролонгация GAP',
        10 => 'BMW M/Mercedes AMG',
    ];

    const KV_AGENT = [
        30 => '30%',
        50 => '50%',
        60 => '60%',
        70 => '70%',
        75 => '75%',
    ];




    public function contract() {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function setDefault()
    {

        $contract = $this->contract;
        $oi = ObjectInsurer::create(['type' => 1]);
        $contract->object_insurer_id = $oi->id;
        $contract->save();

        return true;
    }



}
