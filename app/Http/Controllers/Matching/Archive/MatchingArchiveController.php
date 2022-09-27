<?php

namespace App\Http\Controllers\Matching\Archive;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Matching;
use App\Models\Contracts\UnderwritingCheckUserLog;
use App\Models\Settings\Notification;
use Illuminate\Http\Request;

class MatchingArchiveController extends Controller
{

    public function __construct() {
        $this->middleware('permissions:matching,archive');
        $this->breadcrumbs[] = [
            'label' => 'Архив',
            'url' => 'matching/underwriting'
        ];
    }

    public function index(Request $request)
    {


        return view('matching.archive.index', [

        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function table(Request $request)
    {

        $matching = Matching::whereIn('status_id', [4, 5]);

        if (!empty(request('date_from'))) {
            $matching->whereDate('check_date', '>=', date('Y-m-d 00:00:00', strtotime(request('date_from'))));
        }
        if (!empty(request('date_to'))) {
            $matching->whereDate('check_date', '<=', date('Y-m-d 23:59:59', strtotime(request('date_to'))));
        }

        if(isset($request->product_id) && $request->product_id != -1){
            $matching->where('product_id', $request->product_id);
        }

        if(isset($request->type_id) && $request->type_id != -1){
            $matching->where('type_id', $request->type_id);
        }

        if(isset($request->check_user_id) && $request->check_user_id != -1){
            $matching->where('check_user_id', $request->check_user_id);
        }

        if(isset($request->initiator_user_id) && $request->initiator_user_id != -1){
            $matching->where('initiator_user_id', $request->initiator_user_id);
        }

        if(isset($request->contract_insurer) && strlen($request->contract_insurer) > 3){
            $matching->where('insurer_title', 'like', "%{$request->contract_insurer}%");
        }


        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = 50;

        $result = PaginationHelper::paginate($matching, $page, $page_count);
        $matchings = $result['builder']->get();


        return [
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'html' => view('matching.archive.table', [
                'matchings' => $matchings,
            ])->render(),
        ];


    }

}
