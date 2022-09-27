<?php

namespace App\Models\Directories\Products\Data\DGO;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ObjectInsurer;
use Illuminate\Database\Eloquent\Model;


class Dgo extends Model
{
    protected $table = 'products_dgo';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TERRIRORY = [
        1 => 'РФ',
        2 => 'РФ, СНГ, Европа',
    ];

    const INSURANCE_AMOUNT = [
        500000 => '500 000',
        1000000 => '1 000 000',
        1500000 => '1 500 000',
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
