<?php

namespace App\Http\Controllers\BsoActs\ActsSK\Bso;

use App\Command\BsoActs\ActsSK\Bso\AddToActBso;
use App\Command\BsoActs\ActsSK\Bso\CreateActBso;
use App\Command\BsoActs\ActsSK\Bso\MoveBsoToRegistryBasket;
use App\Command\BsoActs\ActsSK\Bso\MoveBsoToRegistryCurrent;
use App\Command\BsoActs\ActsSK\Bso\MoveBsoToRegistryNext;
use App\Http\Controllers\Controller;
use App\Http\QueryHandlers\BsoActs\ActsSK\Bso\BsoListQueryHandler;
use App\Models\Directories\BsoSuppliers;
use Illuminate\Pipeline\Pipeline;

class BsoActsController extends Controller {

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


        return view('bso_acts.acts_sk.bso.index', [
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

        $bso_items = $supplier->bso_items_to_sk_acts()->getQuery();

        $bso_items = (new BsoListQueryHandler($bso_items))->allowEmpty()->apply();

        $bso_items = $bso_items->get();

        return [
            'bso_items' => $bso_items
        ];
    }

    public function get_table($supplier_id) {
        $data = $this->get_list($supplier_id);
        $data['html'] = view('bso_acts.acts_sk.bso.table', $data)->render();
        return $data;
    }

    public function get_action_table($supplier_id) {
        $this->validate(request(), [
            'acts_sk_id' => 'integer',
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);


        return view('bso_acts.acts_sk.bso.action_table', [
            'acts_sk_id' => request('acts_sk_id'),
            'acts' => $supplier->reports_acts()->where('type_id', 0)->get()
        ]);
    }

    public function execute_bso($supplier_id) {

        $this->validate(request(), [
            'bso_ids' => 'array',
            'bso_ids.*' => 'integer',
            'event_id' => 'integer',
            'report_year' => 'integer',
            'report_month' => 'integer',
            'to_act_sk_id' => 'integer',
            'report_date_start' => 'date',
            'report_date_end' => 'date',
        ]);

        $supplier = BsoSuppliers::findOrFail($supplier_id);
        $bso_ids = array_map('intval', request('bso_ids'));
        $bso_items = $supplier->bso_items()->getQuery()->whereIn('id', $bso_ids);

        $pipes = [
            MoveBsoToRegistryCurrent::class,
            MoveBsoToRegistryBasket::class,
            MoveBsoToRegistryNext::class,
            CreateActBso::class,
            AddToActBso::class,
        ];


        $result = app(Pipeline::class)->send($bso_items)->through($pipes)->then(function ($bso_items) {
            
        });

        return response()->json(['status' => 'ok']);
    }

}
