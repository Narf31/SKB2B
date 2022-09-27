<?php

namespace App\Http\Controllers\BSO\Transfer;

use App\Classes\Export\ExportManager;
use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoCarts;
use App\Models\BSO\BsoCartType;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoState;
use App\Models\Contracts\Debts;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\Products;
use App\Models\Directories\TypeBso;
use App\Models\Settings\TemplateCategory;
use App\Models\User;
use Illuminate\Http\Request;

class TransferBsoController extends Controller
{

    public function index(Request $request)
    {

        $bso_acts = null;
        $bso_cart_id = (isset($request->bso_cart_id))?(int)$request->bso_cart_id:0;
        if($bso_cart_id > 0){
            $bso_cart = BsoCarts::find($bso_cart_id);

            if($bso_cart->cart_state_id == 1){
                $bso_acts = BsoActs::where('bso_cart_id', $bso_cart_id)->get();
            }

            return view('bso.transfer.edit', [
                'bso_cart' => $bso_cart,
                'bso_acts' => $bso_acts,
            ]);


        }

        $bso_cart = new BsoCarts();

        $bso_cart_type = BsoCartType::where('published', 1)->orderBy('ordering', "asc")->get()->pluck('title', 'id');
        $bso_state = BsoState::where('is_actual', 1)->orderBy('id', "asc")->get()->pluck('title', 'id');

        $agents = User::getALLAgents(24)->pluck('name', 'id');
        $bso_manager = User::getALLAgents(26)->pluck('name', 'id');

        return view('bso.transfer.index', [
            'bso_cart_id' => $bso_cart_id,
            'bso_cart' => $bso_cart,
            'bso_cart_type' => $bso_cart_type,
            'bso_state' => $bso_state,
            'agents' => $agents,
            'bso_manager' => $bso_manager,
            'bso_acts' => $bso_acts,
        ]);
    }


    public function get_user_ban_reason(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user->ban_level == 0)
        {
            $user->ban_reason = '';
        }

        if ($user->ban_level == 1)
        {
            $user->ban_reason = 'Выдача БСО ограничена. '.$user->ban_reason.'.<br/><br/> Передать БСО <input type="checkbox" class="unban_user" />';
        }

        if ($user->ban_level == 2)
        {
            $user->ban_reason = 'Выдача БСО заблокирована. Причина: '.$user->ban_reason.'.';
        }

        $user->details_url = "/users/users/{$user->id}/edit";

        if(isset($user->organization) && $user->organization->org_type->is_provider == 0){
            $user->details_url = "/directories/organizations/organizations/{$user->organization_id}/edit";
        }


        return response()->json($user);

    }

    public function get_agent_info(Request $request)
    {
        $agent = User::find($request->user_id);

        $debts = Debts::getDebtsAgent($agent);
        $agent_summaries = [];

        foreach($debts as $debt){

            $type = $debt->type();
            $ptotal = $debt->payment_total;
            $agent_summaries[$type] = isset($agent_summaries[$type]) ? ($agent_summaries[$type] + $ptotal) : $ptotal;
            $agent_summaries['all'] = isset($agent_summaries['all']) ? ($agent_summaries['all'] + $ptotal) : $ptotal;
        }



        return view('bso.transfer.agent_info', [
            'agent' => $agent,
            'agent_summary' => $agent_summaries,
        ]);

    }

    public function create_bso_cart(Request $request)
    {

        $user_id_to = isset($request->user_id_to) ? (int)$request->user_id_to : 0;
        $bso_cart_type = isset($request->bso_cart_type) ? (int)$request->bso_cart_type : 0;
        $bso_state_id = isset($request->bso_state_id) ? (int)$request->bso_state_id : 0;
        $tp_id = isset($request->tp_id) ? (int)$request->tp_id : 0;
        $tp_new_id = 0;
        $tp_bso_manager_id = 0;



        if ($bso_cart_type == 2){
            $tp_new_id = isset($request->tp_new_id) ? (int)$request->tp_new_id : 0;
            $tp_bso_manager_id = isset($request->tp_bso_manager_id) ? (int)$request->tp_bso_manager_id : 0;
        }

        $bso_manager_id = (int)auth()->id();
        $time_create = date('Y-m-d H:i:s');


        $bso_cart = BsoCarts::create([
            'user_id_to' => $user_id_to,
            'bso_cart_type' => $bso_cart_type,
            'bso_state_id' => $bso_state_id,
            'bso_manager_id' => $bso_manager_id,
            'tp_id' => $tp_id,
            'tp_new_id' => $tp_new_id,
            'tp_bso_manager_id' => $tp_bso_manager_id,
            'time_create' => $time_create,
        ]);

        return response($bso_cart->id);

    }

    public function get_bsos(Request $request)
    {
        $bso_supplier_id = isset($request->bso_supplier_id)?(int)$request->bso_supplier_id:0;
        $bso_type_id = isset($request->bso_type_id)?(int)$request->bso_type_id:0;
        $bso_state_id = isset($request->bso_state_id)?(int)$request->bso_state_id:0;
        $tp_id = isset($request->tp_id)?(int)$request->tp_id:0;

        $bso_items = BsoItem::query();
        $bso_items->where('point_sale_id', $tp_id);
        $bso_items->where('location_id',0);

        $bso_items->where('bso_supplier_id', $bso_supplier_id);
        $bso_items->where('type_bso_id', $bso_type_id);
        $bso_items->where('state_id', $bso_state_id);
        $bso_items->where('is_reserved',0);
        $bso_items->orderBy('bso_title', 'asc');
        $bso_items->limit(100);

        return view('bso.transfer.get_bsos', [
            'bso_items' => $bso_items->get(),
        ]);

    }

    public function bso_selector(Request $request)
    {
        $bso_supplier_id = isset($request->bso_supplier_id)?(int)$request->bso_supplier_id:0;
        $bso_supplier = BsoSuppliers::findOrFail($bso_supplier_id);

        $bso_type = TypeBso::where('type_bso.is_actual', 1)
            ->where('type_bso.insurance_companies_id', $bso_supplier->insurance_companies_id)
            ->orderBy('title', 'asc');

        return view('bso.transfer.bso_selector', [
            'bso_supplier_name' => $bso_supplier->title,
            'bso_supplier_id' => $bso_supplier->id,
            'bso_type' => $bso_type->pluck('title', 'id'),
        ]);

    }

    public function selector_bso_transfer(Request $request)
    {
        $return = new \stdClass();
        $return->error_state = 0;

        $obj = \GuzzleHttp\json_decode( $request->obj );


        $bso_type_id      = (int) $obj->type_id;
        $bso_serie_id     = (int) $obj->serie_id;
        $bso_qty          = (int) $obj->bso_qty;
        $bso_cart_id      = (int) $obj->bso_cart_id;
        $tp_id            = (int) $obj->tp_id;

        $bso_number_from = $obj->number;
        $number_to       = (int) $bso_number_from + $bso_qty - 1;
        $bso_number_to   = str_pad( $number_to, strlen( $bso_number_from ), '0', STR_PAD_LEFT );

        $bso_number_from_int = (int) $bso_number_from;


        if ( $bso_type_id > 0 ) {

            if ( $bso_number_from_int == 0 ) {
                $return->error_state = 1;
                $return->error_attr  = 4;
                $return->error_title = 'Введите значение';
                return response()->json($return);
            }
            if ( $bso_qty == 0 ) {
                $return->error_state = 1;
                $return->error_attr  = 3;
                $return->error_title = 'Введите значение';
                return response()->json($return);
            }
        } elseif ( $bso_number_from_int > 0 ) {
            $return->error_state = 1;
            $return->error_attr  = 1;
            $return->error_title = 'Введите значение';
            return response()->json($return);
        } else {
            $return->error_state = 2; // пустая строка
            return response()->json($return);
        }

        $object = BsoCarts::find($bso_cart_id);

        $user_id_from  = (int) $object->user_id_from;
        $bso_cart_type = (int) $object->bso_cart_type;
        $bso_state_id  = (int) $object->bso_state_id;



        $stock_qty = 0;
        $i         = 0;
        while ( $i < $bso_qty ) {
            $sql  = "select count(*) as my_count from bso_items where is_reserved=0 and type_bso_id=$bso_type_id and location_id=0 and state_id=0 and point_sale_id=$tp_id and bso_serie_id=$bso_serie_id and bso_number in ( ";
            $stop = min( $i + 2000, $bso_qty );
            while ( $i < $stop ) {
                $bso_number_int = $bso_number_from_int + $i;
                $bso_number     = str_pad( $bso_number_int, strlen( $bso_number_from ), '0', STR_PAD_LEFT );

                $sql .= "'$bso_number',";
                $i ++;
            }
            $sql = substr( $sql, 0, - 1 );
            $sql .= ')';
            $res = \DB::select($sql);
            $stock_qty += (int)$res[0]->my_count;

        }

        if ( $stock_qty == 0 ) {
            $return->error_state = 1;
            $return->error_attr  = 4;
            $return->error_title = 'Не найдено ни одного БСО';
            return response()->json($return);
        }


        if ( $stock_qty != $bso_qty ) {
            $return->error_state = 1;
            $return->error_attr  = 4;
            $return->error_title = 'Из указанного диапазона есть только ' . $stock_qty . ' БСО';
            return response()->json($return);
        }



        $i = 0;
        while ( $i < $bso_qty ) {
            $sql  = "update bso_items set is_reserved=1, bso_cart_id=$bso_cart_id where type_bso_id=$bso_type_id and location_id=0 and state_id=0 and point_sale_id=$tp_id and bso_serie_id=$bso_serie_id and bso_number in ( ";
            $stop = min( $i + 2000, $bso_qty );
            while ( $i < $stop ) {
                $bso_number_int = $bso_number_from_int + $i;
                $bso_number     = str_pad( $bso_number_int, strlen( $bso_number_from ), '0', STR_PAD_LEFT );

                $sql .= "'$bso_number',";
                $i ++;
            }
            $sql = substr( $sql, 0, - 1 );
            $sql .= ')';

            \DB::update($sql);
        }

        return response()->json($return);
    }

    public function bso_cart_content(Request $request)
    {
        $bso_cart = BsoCarts::find($request->bso_cart_id);

        $sql ="
        select
        bi.type_bso_id,
        count(bi.id) as qty,
        bs.title as sk_title, 
        bt.title as type_title
        from bso_items bi
        left join bso_suppliers bs on bs.id=bi.bso_supplier_id
        left join type_bso bt on bt.id = bi.type_bso_id
        where bi.bso_cart_id={$bso_cart->id}
        group by  bi.type_bso_id,  bs.title, bt.title
        order by sk_title, type_title ";

        $bso_items_group = \DB::select($sql);


        return view('bso.transfer.bso_cart', [
            'bso_cart' => $bso_cart,
            'bso_items_group' => $bso_items_group,
        ]);

    }

    public function move_to_cart(Request $request)
    {

        $bso_arr = explode( ',', $request->bsos );
        $bso_cart_id = isset($request->bso_cart_id)?(int)$request->bso_cart_id:0;

        if($bso_cart_id > 0){

            for($i=0;$i<count($bso_arr);$i++){
                if((int)$bso_arr[$i]>0){

                    $bso_items = BsoItem::where('id', $bso_arr[$i]);
                    $bso_items->update(['is_reserved' => 1, 'bso_cart_id' => $bso_cart_id]);

                    $user_id = auth()->id();
                    $sql = "insert into bso_logs (bso_id, log_time, bso_location_id, user_id) values ('{$bso_arr[$i]}', NOW(), '100', '$user_id')";

                    \DB::insert($sql);
                }
            }

        }

        return response(200);
    }















    public function get_bso_types(Request $request)
    {
        $sk_user_id = isset($request->sk_user_id)?(int)$request->sk_user_id:0;
        $sk_id = isset($request->id_sk)?(int)$request->id_sk:0;

        $bso_supplier = BsoSuppliers::findOrFail($sk_user_id);


        $bso_type_select = TypeBso::where('type_bso.is_actual', 1);
        if($sk_user_id > 0){
            $bso_type_select->where('type_bso.insurance_companies_id', $bso_supplier->insurance_companies_id);
        }elseif ($sk_id > 0){
            $bso_type_select->where('type_bso.insurance_companies_id', $sk_id);
        }

        $bso_type_select->orderBy('title', 'asc');

        $type_selector = \Form::select('type_selector', $bso_type_select->get()->pluck('title', 'id')->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']);

        return response($type_selector);

    }





    public function get_all_bsos(Request $request)
    {

        $user_id_from = isset($request->user_id_from)?(int)$request->user_id_from:0;

        $bso_items = BsoItem::query();
        $bso_items->where('user_id', $user_id_from);
        $bso_items->where('location_id',1);
        $bso_items->orderBy('bso_title', 'asc');

        return view('bso.transfer.get_all_bsos', [
            'bso_items' => $bso_items->get(),
        ]);

    }









    public function remove_cart(Request $request)
    {
        $bso_cart_id = (int)$request->bso_cart_id;
        $bso_cart = BsoCarts::find($bso_cart_id);
        $bso_cart->removeBSOCart(0, 0);
        $bso_cart->delete();
        return response(200);
    }

    public function remove_from_bso_cart(Request $request)
    {
        $bso_cart_id = (int)$request->bso_cart_id;
        $bso_id = (int)$request->bso_id;
        $bso_type_id = (int)$request->bso_type_id;

        $bso_cart = BsoCarts::find($bso_cart_id);
        $bso_cart->removeBSOCart($bso_id, $bso_type_id);

        return response(200);

    }




    public function reserve_export(){
        $this->validate(request(), [
            "bso_cart_id" => "integer",
        ]);

        $builder = BsoCarts::query()->where('id', (int)request('bso_cart_id'));
        $category = TemplateCategory::get('reserve_act');

        $builder->firstOrFail();

        return (new ExportManager($category, $builder))->handle();


    }


}
