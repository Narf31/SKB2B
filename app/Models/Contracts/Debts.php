<?php

namespace App\Models\Contracts;

use App\Models\User;
use App\Scopes\DebtScope;

class Debts extends Payments{

    public $table = 'payments';

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new DebtScope());
    }


    public static function getDebtsAgent(User $user)
    {
        //Если агент закреплен за организацией
        if(isset($user->organization) && $user->organization->org_type->is_provider == 0){
            $debts = Debts::where('agent_organization_id', $user->organization_id);
        }else{
            $debts = Debts::where('agent_id', $user->id);
        }

        return $debts = $debts->get();

    }


}