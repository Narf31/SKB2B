<?php

namespace App\Http\Controllers\BsoActs\ActsTransferTP;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Contracts\Payments;
use App\Models\Settings\PointsSale;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class ActsTransferTPController extends Controller
{

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->middleware('permissions:bso_acts,acts_transfer_tp');
        $this->filesRepository = $filesRepository;
    }


    public function index()
    {
        return view('bso_acts.acts_transfer_tp.index', [

        ]);
    }

    public function get_view(Request $request)
    {

        return view('bso_acts.acts_transfer_tp.info.'.$request->load, [

        ]);
    }



    public function contract_list(Request $request)
    {

        $point_sale_id = (int)$request->point_sale_id;
        session(['acts_transfer_tp.point_sale_id' => $point_sale_id]);


        $bsos = BsoItem::where('point_sale_id', $point_sale_id)
            ->where('state_id', 2)
            ->whereIn('location_id', [1, 2, 4])
            ->where("realized_act_id", 0);


        if(isset($request->product_id) && $request->product_id != -1){
            $bsos->where('bso_items.product_id', $request->product_id);
        }

        if(isset($request->contract_insurer) && strlen($request->contract_insurer) > 3){
            $bsos->leftJoin('contracts', 'contracts.bso_id', '=', 'bso_items.id');
            $bsos->leftJoin('subjects as insurer', 'insurer.id', '=', 'contracts.insurer_id');

            $bsos->where('insurer.title', 'like', "%{$request->contract_insurer}%");
        }

        if(isset($request->contract_bso_title) && strlen($request->contract_bso_title) > 3){
            $bsos->where('bso_items.bso_title', 'like', "%{$request->contract_bso_title}%");
        }



        $bsos->select(['bso_items.*']);

        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = 25;

        $result = PaginationHelper::paginate($bsos, $page, $page_count);


        $bsos = $result['builder']->get();


        return [
            'max_row' => $result['max_row'],
            'view_row' => $result['view_row'],
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'html' => view('bso_acts.acts_transfer_tp.info.contract.list', [
                'bsos' => $bsos,
            ])->render(),
        ];


    }


    public function get_realized_acts(Request $request)
    {
        return response()->json(BsoActs::getRealizedActsTP(auth()->id(), (int)$request->type_id)->get());
    }

    public function create_get_realized_acts(Request $request)
    {
        $event_id = $request->event_id;
        $point_sale_id = $request->point_sale_id;
        $new_tp_id = $request->new_tp_id;
        $type_id = $request->type_id;
        $bso_act_id = $request->order_id;

        $user_id = auth()->id();

        $point_sale = PointsSale::findOrFail($point_sale_id);
        $new_point_sale = PointsSale::findOrFail($new_tp_id);
        $act_dop_name = "$point_sale->title на $new_point_sale->title";


        $bso_item_array = \GuzzleHttp\json_decode( $request->bso_item_array);
        $bso_item_sql = BsoItem::whereIn('id', $bso_item_array);

        if($event_id == 2 && $bso_act_id > 0){
            $bso_act = BsoActs::getActId($bso_act_id);
        }else{
            $bso_act = BsoActs::createRealizedActs($user_id, $type_id, $act_dop_name, $point_sale_id, $new_tp_id);
        }

        $bso = [];
        foreach ($bso_item_sql->get() as $bso_item){
            $bso[] = $bso_item->id;
        }

        if($bso) $bso_act->setBsoRealizedActs($bso, $user_id);


        return response(200);

    }


    public function acts_list(Request $request)
    {
        $point_sale_id = $request->data['point_sale_id'];

        session(['acts_transfer_tp.point_sale_id' => $point_sale_id]);
        $bso_act = BsoActs::getRealizedActsTP(null, -1, $point_sale_id);

        if($request->data['number']){
            $bso_act->where('act_number', 'LIKE', '%'.$request->data['number'].'%');
        }

        return view('bso_acts.acts_transfer_tp.info.acts.list', [
            'bso_act' => $bso_act->get()
        ]);
    }




}
