<?php

namespace App\Command\BsoActs\ActsSK\Contracts;


use Closure;
use Illuminate\Database\Eloquent\Builder;

class MovePaymentsToRegistryNext{

    public function handle(Builder $payments, Closure $next){
        $acts_sk_next = -2;

        if(request('event_id') != $acts_sk_next){
            return $next($payments);
        }

        $payments->update(['acts_sk_id' => $acts_sk_next]);

    }

}