<?php

namespace App\Http\Controllers\Contracts\Prolongation;

use App\Helpers\PaginationHelper;
use App\Http\QueryHandlers\Analitics\Common\PaymentsQueryHandler;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ProlongationController extends Controller {



    public function __construct() {
        $this->middleware('permissions:contracts,prolongation');
        $this->breadcrumbs[] = [
            'label' => 'Договора',
            'url' => 'contracts/search'
        ];
    }

    public function index(Request $request) {

        $users = User::getALLUser();

        return view('contracts.prolongation.index', [
            'users' => $users
        ])->with('breadcrumbs', $this->breadcrumbs);
    }




    public function table(Request $request) {
        $contracts = Contracts::getContractsQuery();
        $contracts->leftJoin('bso_items', 'bso_items.id', '=', 'contracts.bso_id');
        $contracts->leftJoin('subjects as insurer', 'insurer.id', '=', 'contracts.insurer_id');


        $date_mond_id = (int)$request->date_mond_id;
        $payment_date_type_id = (int)$request->payment_date_type_id;
        $data_name = 'contracts.end_date';
        if($payment_date_type_id == 1){
            $data_name = 'contracts.end_date';
        }
        if($payment_date_type_id == 2){
            $data_name = 'contracts.begin_date';
        }

        if($date_mond_id <= 9) $date_mond_id = '0'.$date_mond_id;
        $contracts->where($data_name, 'like', "%-{$date_mond_id}-%");


        if(isset($request->contract_status_id) && $request->contract_status_id > 0){
            $contracts->where('contracts.statys_id', $request->contract_status_id);
        }

        if(isset($request->product_id) && $request->product_id != -1){
            $contracts->where('contracts.product_id', $request->product_id);
        }

        if(isset($request->contract_insurer) && strlen($request->contract_insurer) > 3){
            $contracts->where('insurer.title', 'like', "%{$request->contract_insurer}%");
        }

        if(isset($request->contract_bso_title) && strlen($request->contract_bso_title) > 3){
            $contracts->where('bso_items.bso_title', 'like', "%{$request->contract_bso_title}%");
        }


        $contracts->orderBy($data_name, 'asc');
        $contracts->select(["contracts.*"]);



        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = 50;

        $result = PaginationHelper::paginate($contracts, $page, $page_count);
        $contracts = $result['builder']->get();


        return [
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'html' => view('contracts.prolongation.table', [
                'contracts' => $contracts,
            ])->render(),
        ];

    }



}
