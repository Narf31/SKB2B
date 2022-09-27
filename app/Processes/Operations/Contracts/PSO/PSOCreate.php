<?php

namespace App\Processes\Operations\Contracts\PSO;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Orders\OrdersLogs;
use App\Models\Orders\Pso;
use App\Models\User;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use Illuminate\Support\Str;

class PSOCreate{


    public static function create($contract, $user)
    {

        $inspection = Pso::create([
            'type_id' => 0,
            'status_id' => 0,
            'user_id' => $user->id,
            'contract_id' => $contract->id,
            'product_id' => $contract->product_id,
            'agent_id' => $contract->agent_id,
            'agent_organization_id' => $contract->agent_id,
            'agent_parent_id' => $contract->agent_id,
            'agent_curator_id' => $contract->agent_id,
            'position_type_id' => 0,
            'insurer_title' => $contract->insurer->title,
            'insurer_id' => $contract->insurer_id,
            'insurer_type_id' => $contract->insurer->type,
            'phone' => $contract->insurer->phone,

        ]);


        return $inspection;
    }



}