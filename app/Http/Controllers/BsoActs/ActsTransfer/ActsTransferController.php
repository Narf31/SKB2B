<?php

namespace App\Http\Controllers\BsoActs\ActsTransfer;

use App\Http\QueryHandlers\BsoActs\ActTransfer\ActListQueryHandler;
use App\Http\Controllers\Controller;
use App\Helpers\PaginationHelper;
use App\Models\BSO\BsoActs;

class ActsTransferController extends Controller
{


    public function __construct(){
        $this->middleware('permissions:bso_acts,acts_transfer');
    }


    public function index(){
        return view('bso_acts.acts_transfer.index');
    }

    public function get_list(){

        $acts = BsoActs::getActs();
        $acts = (new ActListQueryHandler($acts))->allowEmpty()->apply();

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
        $data['html'] = view('bso_acts.acts_transfer.table', $data)->render();
        return $data;
    }


}
