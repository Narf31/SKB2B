<?php

namespace App\Http\Controllers\BsoActs\ActsSK\Contracts;

use App\Command\BsoActs\ActsSK\Contracts\AddToActPayments;
use App\Command\BsoActs\ActsSK\Contracts\CreateActPayments;
use App\Command\BsoActs\ActsSK\Contracts\MovePaymentsToRegistryBasket;
use App\Command\BsoActs\ActsSK\Contracts\MovePaymentsToRegistryCurrent;
use App\Command\BsoActs\ActsSK\Contracts\MovePaymentsToRegistryNext;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\BsoActs\ActsSK\Contracts\PaymentsListQueryHandler;
use App\Models\Directories\BsoSuppliers;
use Illuminate\Pipeline\Pipeline;

class ContractActsController extends Controller {

    public function __construct() {
        $this->middleware('permissions:bso_acts,acts_sk');
        $this->breadcrumbs[] = [
            'label' => 'Акты в СК',
            'url' => 'bso_acts/acts_sk'
        ];
    }

    public function index($supplier_id) {
        $supplier = BsoSuppliers::findOrFail($supplier_id);

        $this->breadcrumbs[] = [
            'label' => $supplier->title,
        ];

        return view('bso_acts.acts_sk.contracts.index', [
            'supplier' => $supplier
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function get_list($supplier_id) {

        $this->validate(request(), [
            'state_id' => 'array',
            'product_id' => 'array',
            'acts_sk_id' => 'integer',
            'state_id.*' => 'integer',
            'product_id.*' => 'integer',
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);

        $payments = $supplier->getBasePayments();

        $payments = (new PaymentsListQueryHandler($payments))->allowEmpty()->apply();

        $payments = $payments->get();

        return [
            'payments' => $payments
        ];
    }

    public function get_table($supplier_id) {
        $data = $this->get_list($supplier_id);
        $data['html'] = view('bso_acts.acts_sk.contracts.table', $data)->render();
        return $data;
    }

    public function get_action_table($supplier_id) {
        $this->validate(request(), [
            'acts_sk_id' => 'integer',
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);


        return view('bso_acts.acts_sk.contracts.action_table', [
            'acts_sk_id' => request('acts_sk_id'),
            'acts' => $supplier->reports_acts()->where('type_id', 1)->get()
        ]);
    }

    public function execute_payments($supplier_id) {

        $this->validate(request(), [
            'payment_ids' => 'array',
            'payment_ids.*' => 'integer',
            'event_id' => 'integer',
            'report_year' => 'integer',
            'report_month' => 'integer',
            'to_act_sk_id' => 'integer',
            'report_date_start' => 'date',
            'report_date_end' => 'date',
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);
        $payment_ids = array_map('intval', request('payment_ids'));
        $payments = $supplier->getBasePayments()->whereIn('id', $payment_ids);

        $pipes = [
            MovePaymentsToRegistryCurrent::class,
            MovePaymentsToRegistryBasket::class,
            MovePaymentsToRegistryNext::class,
            CreateActPayments::class,
            AddToActPayments::class,
        ];


        $result = app(Pipeline::class)->send($payments)->through($pipes)->then(function ($payments) {
            
        });

        return response()->json(['status' => 'ok']);
    }

}
