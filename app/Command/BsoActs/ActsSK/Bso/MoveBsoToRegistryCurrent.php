<?php

namespace App\Command\BsoActs\ActsSK\Bso;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class MoveBsoToRegistryCurrent{

    public function handle(Builder $bso_items, Closure $next){
        $acts_sk_current = -1;

        if(request('event_id') != $acts_sk_current){
            return $next($bso_items);
        }

        $bso_items->update(['acts_sk_id' => $acts_sk_current]);

    }

}