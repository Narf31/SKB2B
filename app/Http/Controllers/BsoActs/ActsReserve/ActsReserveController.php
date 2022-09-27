<?php

namespace App\Http\Controllers\BsoActs\ActsReserve;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\BsoActs\ActsReserve\CartListQueryHandler;
use App\Models\BSO\BsoCarts;

class ActsReserveController extends Controller
{


    public function __construct(){
        $this->middleware('permissions:bso_acts,acts_reserve');
    }


    public function index(){
        return view('bso_acts.acts_reserve.index');
    }

    public function get_list(){

        $acts = BsoCarts::getCars();
        $acts->where('cart_state_id', 0);

        $acts = (new CartListQueryHandler($acts))->apply();

        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int)request()->get('page_count') : 10;

        $result = PaginationHelper::paginate($acts, $page, $page_count);

        $acts = $result['builder']->orderBy('time_create', 'desc')->get();

        return [
            'acts' => $acts,
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
        ];

    }

    public function get_acts_table(){
        $data = $this->get_list();
        $data['html'] = view('bso_acts.acts_reserve.table', $data)->render();
        return $data;
    }

}
