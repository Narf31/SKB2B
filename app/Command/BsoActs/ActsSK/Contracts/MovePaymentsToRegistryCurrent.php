<?php

namespace App\Command\BsoActs\ActsSK\Contracts;


use Closure;
use Illuminate\Database\Eloquent\Builder;

class MovePaymentsToRegistryCurrent{

    public function handle(Builder $payments, Closure $next){
        $acts_sk_current = -1;

        if(request('event_id') != $acts_sk_current){
            return $next($payments);
        }

        $payments->update(['acts_sk_id' => $acts_sk_current]);

    }

}