<?php

namespace App\Models\Characters;

use App\Models\BSO\BsoItem;
use App\Models\Contracts\Debts;
use App\Models\Contracts\Payments;
use App\Models\User;
use App\Scopes\AgentScope;

class Agent extends User {

    public $table = 'users';

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new AgentScope);
    }

    public function payments(){
        return $this->hasMany(Payments::class, 'agent_id');
    }

    public function debts(){
        return $this->hasMany(Debts::class, 'agent_id');
    }

    public function bso_items(){
        return $this->hasMany(BsoItem::class, 'agent_id');
    }

    public function getDebtsSummary() {

        $summary = ['all' => 0, 'cash' => 0, 'cashless' => 0, 'sk' => 0];
        $debts = $this->debts();
        foreach ($debts->get() as $debt) {
            $type = $debt->type();
            $summary[$type] += $debt->payment_total;
            $summary['all'] += $debt->payment_total;
        }
        return $summary;
    }



}
