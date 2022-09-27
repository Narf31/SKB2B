<?php

namespace App\Command\BsoActs\ActsSK\Bso;

use Closure;
use App\Models\Acts\ReportAct;
use Illuminate\Database\Eloquent\Builder;

class CreateActBso{

    public function handle(Builder $bso_items, Closure $next){

        $act_sk_create = 1;

        if(request('event_id') != $act_sk_create){
            return $next($bso_items);
        }

        $report_act = ReportAct::create([
            'type_id' => 0, // бсо
            'title' => request()->get('report_name'),
            'bso_supplier_id' => request()->route('supplier_id'),
            'report_year' => request()->get('report_year'),
            'report_month' => request()->get('report_month'),
            'create_user_id' => auth()->id(),
            'report_date_start' => date('Y-m-d H:i:s', strtotime(request()->get('report_date_start'))),
            'report_date_end' => date('Y-m-d H:i:s', strtotime(request()->get('report_date_end'))),
        ]);


        $bso_items->update([
            'acts_sk_id' => $report_act->id,
        ]);

        foreach($bso_items->get() as $bso_item){
            $bso_item->setBsoLog(9);
        }

    }

}