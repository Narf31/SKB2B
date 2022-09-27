<?php

namespace App\Http\Controllers\Contracts\Search;

use App\Helpers\PaginationHelper;
use App\Http\QueryHandlers\Analitics\Common\PaymentsQueryHandler;
use App\Models\Contracts\Payments;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SearchController extends Controller {

    // Для выгрузки Excel через POST
    const TEMP_EXPORT_STORAGE_FOLDER = 'analitics_common';

    public function __construct() {
        $this->middleware('permissions:contracts,search');
        $this->breadcrumbs[] = [
            'label' => 'Договоры',
            'url' => 'contracts/search'
        ];
    }

    public function index(Request $request) {

        $users = User::getALLUser();




        return view('contracts.search.index', [
            'users' => $users
        ])->with('breadcrumbs', $this->breadcrumbs);
    }


    public function get_payments_builder(Request $request)
    {
        $payments = Payments::getPaymentsUser();
        $payments->leftJoin('contracts', 'contracts.id', '=', 'payments.contract_id')
            ->leftJoin('bso_items', 'bso_items.id', '=', 'payments.bso_id')
            ->leftJoin('users as agent', 'agent.id', '=', 'payments.agent_id')
            ->leftJoin('products', 'products.id', '=', 'bso_items.product_id')
            ->leftJoin('subjects', 'subjects.id', '=', 'contracts.insurer_id')

            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('subjects as insurer', 'insurer.id', '=', 'contracts.insurer_id');

        //$payments->where('payments.is_deleted', 0);
        //$payments->where('contracts.statys_id', '>', 0);




        //Ограничения по базе
        $payments = (new PaymentsQueryHandler($payments))->allowEmpty()->apply();
        $payments->select(["payments.*"]);

        return $payments;

    }

    public function get_payments_table(Request $request) {
        $payments = $this->get_payments_builder($request);


        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = 50;



        $result = PaginationHelper::paginate($payments, $page, $page_count);
        $payments = $result['builder']->get();


        return [
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'html' => view('contracts.search.table', [
                'payments' => $payments,
                'is_xls' => 0
            ])->render(),
        ];

    }


    public function get_payments_table_to_excel(Request $request)
    {
        $payments = $this->get_payments_builder($request)->get();

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


        Excel::create($filename, function($excel) use ($payments) {

            $excel->sheet('Лист', function($sheet) use ($payments) {

                $sheet->loadView('contracts.search.table', [
                    'payments' => $payments,
                    'is_xls' => 1,
                ]);

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
