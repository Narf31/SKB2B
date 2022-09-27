<?php

namespace App\Http\Controllers\BSO;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\BSO\AgentBsoListQueryHandler;
use App\Models\BSO\BsoItem;
use App\Models\Characters\Agent;
use Illuminate\Http\Request;

class InventoryAgentsController extends Controller {

    public function __construct() {
        $this->middleware('permissions:bso,inventory_agents');
        $this->breadcrumbs[] = [
            'label' => 'Инвентаризация по агентам',
            'url' => 'bso/inventory_agents'
        ];
    }

    public function index() {
        if(auth()->user()->role->visibility('bso') == 2){
            return $this->details();
        }
        return view('bso.inventory.agents.index');
    }

    public function agents_list(){

        $request = request()->all();

        $bso_items = BsoItem::getBso();
        $bso_items->where('location_id', 1);
        $agents = Agent::query();


        if (isset($request['agent_id']) && $request['agent_id'] >= 0) {
            $agents->where('id', '=', $request['agent_id']);
        }
        if (isset($request['nop_id']) && $request['nop_id'] >= 0) {
            $agents->where('users.curator_id', '=', $request['nop_id']);
        }

        $agents = $agents->get()->keyBy('id');


        $bso_items = $bso_items->whereIn('agent_id', $agents->pluck('id')->toArray());

        if (isset($request['point_sale_id']) && $request['point_sale_id'] >= 0) {
            $bso_items->where('point_sale_id', '=', $request['point_sale_id']);
        }

        if (isset($request['type_bso_id']) && $request['type_bso_id'] >= 0) {
            $bso_items->where('type_bso_id', '=', $request['type_bso_id']);
        }

        $bso_items = $bso_items->get()->groupBy('agent_id');

        foreach($agents as $k => $agent){
            if(!$bso_items->has($k)){
                $agents->forget($k);
            }
        }

        return [
            'agents' => $agents,
            'bso_items' => $bso_items
        ];
    }


    public function get_agents_table(){
        $data = $this->agents_list();
        $data['html'] = view('bso.inventory.agents.agents_table', $data)->render();
        return $data;
    }

    public function details() {

        $type = request()->has('types') ? request()->get('types') : 'bso_in';
        switch ($type) {
            case 'bso_in';
                $title = 'Всего';
                break;
            case 'bso_in_30';
                $title = 'Всего старых (более 30 дней)';
                break;
            case 'bso_in_90';
                $title = 'Всего старых (более 90 дней)';
                break;
            default;
                $title = '';
        }

        $this->breadcrumbs[] = [
            'label' => $title,
        ];


       // $data = $this->details_list();


        return view('bso.inventory.agents.details', [
            'breadcrumbs' => $this->breadcrumbs,
            //'data' => $data,
            'title' => $title
        ]);
    }


    public function details_list(){


        $this->validate(request(), [
            "agent_id"      => "integer",
            "point_sale_id" => "integer",
            "type_bso_id"   => "integer",
            "nop_id"        => "integer",
            "types"         => "string",
        ]);

        $bso_items = (new AgentBsoListQueryHandler(BsoItem::getBso()))->apply();
        $bso_items->where('location_id', 1);
        $page = request('PAGE') > 0 ? (int)request('PAGE') : 1;
        $page_count = request('page_count') > 0 ? (int)request('page_count') : 10;
        $page_count = request('page_count') == -1 ? 9999 : $page_count;

        $result = PaginationHelper::paginate($bso_items, $page, $page_count);

        $acts = $result['builder']->orderBy('time_create', 'desc')->get();

        return [
            'acts' => $acts,
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
        ];

    }


    public function get_details_table() {

        $data = $this->details_list();
        $data['html'] = view('bso.inventory.agents.details_table', $data)->render();
        return $data;
    }


}
