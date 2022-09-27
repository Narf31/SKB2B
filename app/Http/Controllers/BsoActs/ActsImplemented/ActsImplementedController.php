<?php

namespace App\Http\Controllers\BsoActs\ActsImplemented;

use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Contracts\Payments;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class ActsImplementedController extends Controller
{

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->middleware('permissions:bso_acts,acts_implemented');
        $this->filesRepository = $filesRepository;
    }


    public function index()
    {
        return view('bso_acts.acts_implemented.index', [

        ]);
    }

    public function get_view(Request $request)
    {

        return view('bso_acts.acts_implemented.info.'.$request->load, [

        ]);
    }

    public function get_realized_acts(Request $request)
    {

        $type_id = (int)$request->type_id;
        $user_id = (int)$request->user_id;

        return response()->json(BsoActs::getRealizedActs($user_id, $type_id)->get());

    }

    public function acts_list(Request $request)
    {
        $agent_id = $request->data['agent_id'];

        session(['acts_implemented.agent_id' => $agent_id]);
        $bso_act = BsoActs::getRealizedActs($agent_id);

        if($request->data['number']){
            $bso_act->where('act_number', 'LIKE', '%'.$request->data['number'].'%');
        }

        return view('bso_acts.acts_implemented.info.acts.list', [
            'bso_act' => $bso_act->get()
        ]);
    }

    public function contract_list(Request $request)
    {

        $agent_id = $request->data['agent_id'];
        session(['acts_implemented.agent_id' => $agent_id]);
        $payments = Payments::where('payments.is_deleted', 0)->where('payments.type_id', 0)->where('payments.realized_act_id', 0);

        $payments->leftJoin('contracts', 'contracts.id', '=', 'payments.contract_id');

        if((int)$request->data['kind_acceptance'] != -1){
            $payments->where('contracts.kind_acceptance', (int)$request->data['kind_acceptance']);
        }



        $payments->where(function ($query_bso) use ($agent_id) {


            $query_bso->where(function ($query_b)  use ($agent_id) {
                $query_b->whereIn('payments.bso_id', function($query) use ($agent_id)
                {
                    $query->select(\DB::raw('bso_items.id'))
                        ->from('bso_items')
                        ->where('bso_items.state_id', '=', '2')
                        ->where('bso_items.user_id', '=', $agent_id)
                        ->whereIn('bso_items.location_id', [1,4]);

                });
            })->orWhere(function ($query_b)  use ($agent_id) {
                $query_b->whereIn('payments.bso_receipt_id', function($query) use ($agent_id)
                {
                    $query->select(\DB::raw('bso_receipt.id'))
                        ->from('bso_items as bso_receipt')
                        ->where('bso_receipt.state_id', '=', '2')
                        ->where('bso_receipt.user_id', '=', $agent_id)
                        ->whereIn('bso_receipt.location_id', [1,4]);

                });
            })

            ;
        });

        $payments->select(\DB::raw('*, payments.id as pay_id'));


        return view('bso_acts.acts_implemented.info.contract.list', [
            'payments' => $payments->get()
        ]);
    }

    public function spoiled_list(Request $request)
    {

        $agent_id = $request->data['agent_id'];

        session(['acts_implemented.agent_id' => $agent_id]);

        $bso_items = BsoItem::where("user_id", $agent_id)
            ->where("state_id", 3)
            ->where("location_id", 1)
            ->where("realized_act_id", 0);

        return view('bso_acts.acts_implemented.info.spoiled.list', [
            'bso_items' => $bso_items->get()
        ]);

    }

    public function edit_spoiled(Request $request)
    {

        $agent_id = $request->agent_id;
        $bso_id = (int)$request->bso_id;

        $bso = null;
        if($bso_id > 0){
            $bso = BsoItem::getBsoId($bso_id);
        }


        return view('bso_acts.acts_implemented.info.spoiled.edit', [
            'agent_id' => $agent_id,
            'bso' => $bso,
        ]);

    }

    public function save_spoiled(Request $request)
    {
        $bso = BsoItem::getBsoId($request->bso_id);

        if($bso->state_id != 3)
        {
            $bso->state_id = 3;
            $bso->save();
            BsoLogs::setLogs($bso->id, $bso->state_id, $bso->location_id, 0,  auth()->id());
        }


        if($request->file){
            $file = $this->filesRepository->makeFile($request->file, BsoItem::FILES_DOC.$bso->id.'/');
            $bso->file_id = $file->id;
            $bso->save();
        }

        return parentReloadTab();

    }

    public function create_get_realized_acts(Request $request)
    {
        $user_id = $request->data['user_id'];
        $type_id = $request->data['type_id'];
        $bso_act_id = $request->data['order_id'];

        $payments_array = \GuzzleHttp\json_decode( $request->data['payments_array']);
        $payments = implode( ',', $payments_array );

        if($bso_act_id > 0){
            $bso_act = BsoActs::getActId($bso_act_id);
        }else{
            $bso_act = BsoActs::createRealizedActs($user_id, $type_id);
        }

        $payments_sql = \DB::select("select id, bso_receipt_id from payments where id in ($payments)");//Payments::where('id', 'in', "()");



        $bso = [];
        foreach ($payments_sql as $payment){
            $bso[] = $payment->id;
            if((int)$payment->bso_receipt_id > 0) $bso[] = $payment->bso_receipt_id;
        }


        if($bso) $bso_act->setBsoRealizedActs($bso, $user_id);


        \DB::update("update payments set realized_act_id={$bso_act->id} where id in ($payments) and realized_act_id=0");

        return response(200);

    }

    public function create_spoiled_realized_acts(Request $request)
    {
        $user_id = $request->data['user_id'];
        $type_id = $request->data['type_id'];
        $bso_act_id = $request->data['order_id'];

        $bso_array = \GuzzleHttp\json_decode( $request->data['bso_array']);



        if($bso_act_id > 0){
            $bso_act = BsoActs::getActId($bso_act_id);
        }else{
            $bso_act = BsoActs::createRealizedActs($user_id, $type_id);
        }

        $bso_act->setBsoRealizedActs($bso_array, $user_id);

        return response(200);
    }




    public function сlean_list(Request $request)
    {

        $agent_id = $request->data['agent_id'];

        session(['acts_implemented.agent_id' => $agent_id]);

        $bso_items = BsoItem::where("user_id", $agent_id)
            ->where("state_id", 0)
            ->where("location_id", 1)
            ->where("realized_act_id", 0);

        return view('bso_acts.acts_implemented.info.сlean.list', [
            'bso_items' => $bso_items->get()
        ]);

    }

    public function create_сlean_realized_acts(Request $request)
    {
        $user_id = $request->data['user_id'];
        $type_id = $request->data['type_id'];
        $bso_act_id = $request->data['order_id'];

        $bso_array = \GuzzleHttp\json_decode( $request->data['bso_array']);



        if($bso_act_id > 0){
            $bso_act = BsoActs::getActId($bso_act_id);
        }else{
            $bso_act = BsoActs::createRealizedActs($user_id, $type_id);
        }

        $bso_act->setBsoRealizedActs($bso_array, $user_id);

        return response(200);
    }



}
