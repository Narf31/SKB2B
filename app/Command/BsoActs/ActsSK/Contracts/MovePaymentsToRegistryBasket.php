<?php

namespace App\Command\BsoActs\ActsSK\Contracts;


use Closure;
use Illuminate\Database\Eloquent\Builder;

class MovePaymentsToRegistryBasket{

    public function handle(Builder $payments, Closure $next){
        $acts_sk_basket = 0;

        if(request('event_id') != $acts_sk_basket){
            return $next($payments);
        }

        $payments->update(['acts_sk_id' => $acts_sk_basket]);

    }

}