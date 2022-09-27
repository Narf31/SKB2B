<?php

namespace App\Http\Controllers\Analitics\Common;

use App\Helpers\PaginationHelper;
use App\Helpers\Visible;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\Analitics\Common\PaymentsQueryHandler;
use App\Http\Requests\Analitics\Common\PaymentListRequest;
use App\Models\Characters\Agent;
use App\Models\Contracts\Payments;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\Products;
use App\Models\Organizations\Organization;
use App\Models\Settings\Department;
use App\Models\User;

class CommonAnalyticsController extends Controller{


    public function index(){

        $insurances = InsuranceCompanies::all();

        $organizations = Organization::all();

        $departments = Department::all();

        $products = Products::all();

        $agents = Agent::all();

        $users = User::all();


        return view('analitics.common.index',[
            'organizations' => $organizations,
            'departments' => $departments,
            'insurances' => $insurances,
            'products' => $products,
            'agents' => $agents,
            'users' => $users,
        ]);

    }


    public function get_payments_list(PaymentListRequest $request){

        $payments = (new PaymentsQueryHandler(Payments::query()))->apply();
        $payments = Visible::apply($payments, 'finance', ['agent_id', 'manager_id']);

        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int)request()->get('page_count') : 10;
        $result = PaginationHelper::paginate($payments, $page, $page_count);

        return [
            'payments' => $result['builder']->get(),
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row'  => $result['max_row'],
            'view_row' => $result['view_row'],
        ];

    }

    public function get_payments_table(PaymentListRequest $request){

        $result = $this->get_payments_list($request);
        $result['html'] = view('analitics.common.table', $result)->render();

        return $result;

    }


}