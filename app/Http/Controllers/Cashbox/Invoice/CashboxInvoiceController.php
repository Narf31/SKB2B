<?php

namespace App\Http\Controllers\Cashbox\Invoice;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\Cashbox\Invoice\InvoicesQueryHandler;
use App\Models\Finance\Invoice;
use App\Models\User;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class CashboxInvoiceController extends Controller
{

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->filesRepository = $filesRepository;
        $this->middleware('permissions:cashbox,invoice');
    }



    public function index(Request $request)
    {

        $agent_id = session()->get('invoice.agent_id')?:auth()->id();

        return view('cashbox.invoice.index', [
            'agent' => User::find($agent_id),
        ]);
    }


    public function get_invoices_table(Request $request)
    {

        $this->validate(request(),[
            'agent_id' => 'integer',
            'type' => 'integer',
            'status' => 'integer',
            'date_from' => 'date',
            'date_to' => 'date',
            'page_count' => 'integer',
            'PAGE' => 'integer',
            'invoice_number' => 'integer',
        ]);


        $agent_id = $request->agent_id;
        session(['invoice.agent_id' => $agent_id]);

        $invoices = (new InvoicesQueryHandler(Invoice::query()))->allowEmpty()->apply();

        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int)request()->get('page_count') : 10;
        $result = PaginationHelper::paginate($invoices, $page, $page_count);
        if(request('invoice_number')){
            $invoice_number = (int)request('invoice_number');
            $result['builder']->where('id', 'like', "%{$invoice_number}%");
        }
        $result['builder']->orderBy('created_at', 'desc')->with('payments','agent','org');

        $invoices = $result['builder']->get();
        $html = view('cashbox.invoice.info', [
            'invoices' => $invoices,
        ])->render();

        return [
            'html' => $html,
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
        ];
    }

    public function edit($id)
    {
        $invoice = Invoice::getInvoiceId($id);

        $invoice_info = $invoice->getInfoSum();

        return view('cashbox.invoice.edit', [
            'invoice' => $invoice,
            'invoice_info' => $invoice_info,
        ]);
    }

    public function view($id)
    {
        $invoice = Invoice::getInvoiceId($id);

        $invoice_info = $invoice->getInfoSum();

        return view('cashbox.invoice.view', [
            'invoice' => $invoice,
            'invoice_info' => $invoice_info,
        ]);
    }


    public function data_invoice_payment($id, Request $request)
    {

        $view_payment = 'payment_cash';
        if((int)$request->type_invoice_payment == 1) $view_payment = 'payment_cash';
        elseif((int)$request->type_invoice_payment == 2) $view_payment = 'payment_balance';
        else $view_payment = 'payment_cashless';

        $invoice = Invoice::getInvoiceId($id);
        $invoice_info = $invoice->getInfoSum();

        return view("payments.invoice.partials.$view_payment", [
            'invoice' => $invoice,
            'invoice_info' => $invoice_info,
        ]);

    }

    public function save($id, Request $request)
    {
        $invoice = Invoice::getInvoiceId($id);
        $invoice_info = $invoice->getInfoSum();

        $result = new \stdClass();
        $result->state = 0;
        $result->error = '';



        if($invoice->status_id == 1){
            $type_invoice_payment = (int)$request->type_invoice_payment;
            $invoice->type_invoice_payment_id = $type_invoice_payment;
            $invoice->save();

            session(['invoice.type_invoice_payment' => $type_invoice_payment]);

            $invoice->invoice_payment_com = $request->invoice_payment_comm;
            if($request->file){
                $file = $this->filesRepository->makeFile($request->file, Invoice::FILES_DOC.$invoice->id.'/');
                $invoice->file_id = $file->id;
            }
            $invoice->save();



            $res = InvoiceAutomatic::closeInvoice($invoice, getDateTime(), $invoice_info->total_sum);


            $result->state = 1;

            //События БСО 12 Оплачен BsoLog
            foreach ($invoice->payments as $payment){
                $payment->setBsoLogToPayment(12);
            }


        }else{
            $result->error = 'Ошибка счет оплачен!';
        }


        if($result->state == 0){
            return redirect(url("/cashbox/invoice/{$invoice->id}/edit/"))->with('error', $result->error);
        }


        return redirect(url("/cashbox/invoice/{$invoice->id}/view/"))->with('success', "Счет оплачен!");
    }




}
