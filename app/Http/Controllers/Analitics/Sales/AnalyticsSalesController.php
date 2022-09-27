<?php

namespace App\Http\Controllers\Analitics\Sales;

use App\Helpers\PaginationHelper;
use App\Helpers\Visible;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\Analitics\Common\PaymentsQueryHandler;
use App\Models\Contracts\Payments;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\Products;
use App\Models\Organizations\Organization;
use App\Models\Settings\Department;
use App\Models\Settings\PointsSale;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class AnalyticsSalesController extends Controller
{
    // Для выгрузки Excel через POST
    const TEMP_EXPORT_STORAGE_FOLDER = 'analitics_sales';



    public function __construct() {

        $this->middleware('permissions:analitics,sales');

        $this->breadcrumbs[] = [
            'label' => 'Аналитика - Продаж',
            'url' => 'analitics/sales'
        ];
    }

    public function index()
    {


        $organizations = Organization::getOrgProvider()->orderBy("title")->get();

        $departments = Department::all();

        $products = Products::orderBy("title")->get();

        $points_sale = PointsSale::all();

        $result['count_pagination'] = [
            -1 => 'Все', 25 => '25', 50 => '50', 100 => '100', 150 => '150'
        ];


        return view('analitics.sales.index', [
            'organizations' => $organizations,
            'points_sale' => $points_sale,
            'departments' => $departments,
            'products' => $products,
            'table_key' => self::TEMP_EXPORT_STORAGE_FOLDER,
            'count_pagination' => $result['count_pagination']
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function get_payments_builder(Request $request)
    {
        $payments = Payments::getPayments();
        $payments->leftJoin('contracts', 'contracts.id', '=', 'payments.contract_id')
            ->leftJoin('bso_items', 'bso_items.id', '=', 'payments.bso_id')

            ->leftJoin('users as agent', 'agent.id', '=', 'bso_items.agent_id')
            ->leftJoin('organizations as agent_organizations', 'agent_organizations.id', '=', 'agent.organization_id')

            ->leftJoin('users as user_sale', 'user_sale.id', '=', 'contracts.agent_id')

            ->leftJoin('users as curator', 'curator.id', '=', 'agent.curator_id')


            ->leftJoin('points_sale as points_sale', 'points_sale.id', '=', 'bso_items.point_sale_id')
            ->leftJoin('departments', 'departments.id', '=', 'agent.department_id')


            ->leftJoin('products', 'products.id', '=', 'bso_items.product_id')
            ->leftJoin('citys', 'citys.id', '=', 'points_sale.city_id')

            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('subjects as insurer', 'insurer.id', '=', 'contracts.insurer_id')

            ->leftJoin('users as invoice_payment_user', 'invoice_payment_user.id', '=', 'invoices.invoice_payment_user_id')

            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'payments.payment_method_id')

            ->leftJoin('organizations', 'organizations.id', '=', 'bso_items.org_id')
            ->leftJoin('financial_policies', 'financial_policies.id', '=', 'payments.financial_policy_id')
            ->leftJoin('installment_algorithms', 'installment_algorithms.id', '=', 'contracts.installment_algorithms_id')


            ->leftJoin('installment_algorithms_payment as installment_algorithms_list', 'installment_algorithms_list.id', '=', 'installment_algorithms.algorithm_id')


            ->leftJoin('reports_orders', 'reports_orders.id', '=', 'payments.reports_dvou_id')
            ->where('contracts.statys_id', '>', 0);


        //Ограничения по базе
        //$payments = Visible::apply($payments, 'finance', ['payments.agent_id', 'payments.manager_id']);
        $payments = (new PaymentsQueryHandler($payments))->allowEmpty()->apply();

        return $payments;

    }

    public function get_payments_table(Request $request)
    {

        $payments = $this->get_payments_builder($request);

        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int)request()->get('page_count') : -1;

        $query[] = array();
        $query['fields'] = [];
        $user_columns = Auth::user()->getTableColumns('analitics_sales', false);
        foreach ($user_columns as $column) {
            if ($column['is_as'] == 1) {
                $query['fields'][] = "{$column['column_key']} as {$column['as_key']}";
            }else{
                $query['fields'][] = "{$column['column_key']}";
            }
        }

        foreach($query['fields'] as &$v){ $v = DB::raw($v); }




        $payments_sql = clone $payments;

        switch ($request->payment_date_type_id) {
            case 1:
                $payments->orderBy('payments.payment_data', 'desc');
                break;
            case 2:
                $payments->orderBy('contracts.sign_date', 'desc');
                break;
        }

        $result = PaginationHelper::paginate($payments, $page, $page_count);

        $result['builder']->select($query['fields']);
        $payments = $result['builder']->get();


        return [
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'html' => view('analitics.sales.table', [
                'payments' => $payments,
                'payments_sql' => $payments_sql,
                'user_columns' => $user_columns,
                'is_xls' => 0
            ])->render(),
        ];

    }

    public function get_payments_table_to_excel(Request $request)
    {

        $payments = $this->get_payments_builder($request);

        $query[] = array();
        $query['fields'] = [];
        $user_columns = Auth::user()->getTableColumns('analitics_sales', false);
        foreach ($user_columns as $column) {
            if ($column['is_as'] == 1) {
                $query['fields'][] = "{$column['column_key']} as {$column['as_key']}";
            }else{
                $query['fields'][] = "{$column['column_key']}";
            }
        }

        foreach($query['fields'] as &$v){ $v = DB::raw($v); }

        $payments->select($query['fields']);
        $payments_sql = clone $payments;

        $temp_export_storage = self::TEMP_EXPORT_STORAGE_FOLDER;

        $folder = 'temp_export_storage/'.$temp_export_storage;
        $filename = $temp_export_storage.'_'.uniqid();

        $files_delete = \App\Models\File::query()->where('folder', $folder)->whereDate('created_at', '<', date('Y-m-d'));

        if ($files_delete->get()->count() > 0){

            foreach ($files_delete->get() as $file_delete)
            {
                \Storage::delete($file_delete->folder . '/' . $file_delete->name.'.'.$file_delete->ext);
            }

            $files_delete->delete();
        }


        Excel::create($filename, function($excel) use ($payments, $payments_sql, $user_columns) {

            $excel->sheet('Лист', function($sheet) use ($payments, $payments_sql, $user_columns) {

                $sheet->loadView('analitics.sales.table', [
                    'payments' => $payments->get(),
                    'payments_sql' => $payments_sql,
                    'user_columns' => $user_columns,
                    'is_xls' => 1
                ])->with('margin_sum');

            });

        })->store('xlsx', storage_path('app/'.request()->getHost().'/'.$folder));

        $file = new \App\Models\File();
        $file->original_name = $filename.'.xlsx';
        $file->ext = 'xlsx';
        $file->folder = $folder;
        $file->name = $filename;
        $file->user_id = auth()->check() ? auth()->id() : null;
        $file->host = request()->getHost();
        $file->save();

        return response()->json(['state'=>1, 'url'=>url("/files/$filename")]);

    }





}