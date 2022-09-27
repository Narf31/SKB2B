<?php

namespace App\Command\BsoActs\ActsSK\Bso;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class MoveBsoToRegistryNext{

    public function handle(Builder $bso_items, Closure $next){
        $acts_sk_next = -2;

        if(request('event_id') != $acts_sk_next){
            return $next($bso_items);
        }

        $bso_items->update(['acts_sk_id' => $acts_sk_next]);

    }

}