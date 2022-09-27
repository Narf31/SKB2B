<?php

namespace App\Command\BsoActs\ActsSK\Bso;

use App\Models\Directories\BsoSuppliers;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class AddToActBso{

    public function handle(Builder $bso_items, Closure $next){

        $acts_sk_add = 2;

        if(request('event_id') != $acts_sk_add){
            return $next($bso_items);
        }

        $supplier = BsoSuppliers::findOrFail((int)request()->route('supplier_id'));
        $report_act = $supplier->reports_acts()->where('id', (int)request()->get('to_act_sk_id'))->firstOrFail();

        $bso_items->update([
            'acts_sk_id' => $report_act->id,
        ]);

        foreach($bso_items->get() as $bso_item){
            $bso_item->setBsoLog(9);
        }


    }

}