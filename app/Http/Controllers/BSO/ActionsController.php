<?php

namespace App\Http\Controllers\BSO;

use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoItem;
use App\Models\Contracts\ObjectInsurer;
use App\Models\Directories\BsoDopSerie;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicy;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\Products;
use App\Models\Directories\TypeBso;
use App\Models\User;
use App\Services\Foreign\GeoLocation;
use App\Services\Foreign\Mobile\MobileOrders;
use App\Services\Foreign\SendSecurity;
use App\Services\Front\Integration;
use App\Services\Front\IntegrationFront;
use Illuminate\Http\Request;

class ActionsController extends Controller
{



    public function get_bso_type(Request $request)
    {

        $bso_supplier = BsoSuppliers::findOrFail($request->bso_supplier_id);


        $bso_type = TypeBso::where('type_bso.is_actual', 1)
            ->whereIn('type_bso.id', function($query) use ($bso_supplier)
            {

                $query->select(\DB::raw('bso_serie.type_bso_id'))
                    ->from('bso_serie')
                    ->where('bso_serie.bso_class_id', 1)
                    ->where('bso_serie.insurance_companies_id', $bso_supplier->insurance_companies_id);

            })
            ->orderBy('title', 'asc')
            ->get();



        return response()->json($bso_type);

    }

    public function get_series(Request $request)
    {
        $bso_supplier = BsoSuppliers::findOrFail($request->bso_supplier_id);
        $bso_serie = BsoSerie::where('insurance_companies_id', $bso_supplier->insurance_companies_id)
            ->where('type_bso_id', $request->bso_type_id);

        if(isset($request->is_electronic)){
            $bso_serie->where('bso_class_id', 1);
        }

        return response()->json($bso_serie->get(['bso_serie', 'id'])->toArray());

    }

    public function get_dop_series(Request $request)
    {
        $bso_dop_serie = BsoDopSerie::where('bso_serie_id', $request->series_id)->get(['bso_dop_serie', 'id'])->toArray();
        return response()->json($bso_dop_serie);

    }

    public function bso_number_to(Request $request)
    {
        $number_to = '';
        $number_from = isset( $request->bso_num ) ? $request->bso_num : '';
        $bso_qty     = isset( $request->bso_qty ) ? (int) str_replace( ' ', '', $request->bso_qty ) : 0;
        if ( $bso_qty == 0 || $number_from == '' ) {

        }else{
            if ( strlen( $number_from ) > 15 ) {// длинные номера бсо больше max int, поэтому обрезаем

                $number_tmp = substr( $number_from, - ( (int) ( strlen( $number_from ) / 2 ) ) );

                $number_tmp += $bso_qty - 1;

                $number_to = substr( $number_from, 0, ( (int) ( strlen( $number_from ) / 2 ) ) +1) . $number_tmp;


            } else {
                $number_to = (int) $number_from + $bso_qty - 1;
                $number_to = str_pad( $number_to, strlen( $number_from ), '0', STR_PAD_LEFT );

            }
        }

        return response($number_to);

    }


    public function create_transfer_act(Request $request)
    {

        $act_number = $request->act_number;
        $bso_supplier_id = $request->bso_supplier_id;
        $point_sale_id = $request->point_sale_id;
        $bso_manager_id = auth()->id();

        $time_create = date('Y-m-d H:i:s');
        $user_id_from = $bso_manager_id;
        $user_id_to = 0;
        $location_from = $point_sale_id; // Склад БСО
        $location_to = 1; // Агент
        $bso_state_id = 0;


        $bso_acts = BsoActs::select((\DB::raw('max(act_number_int) as max_act_number')))->get()->first();
        if($bso_acts->max_act_number){
            $act_number_int = (int)$bso_acts->max_act_number + 1;
        }else{
            $act_number_int = 1;
        }

        if(strlen($act_number) == 0){
            $act_number = str_pad($act_number_int, 6, '0', STR_PAD_LEFT);
        }

        $acts = BsoActs::create([
            'time_create' => $time_create,
            'type_id' => '0',
            'user_id_from' => $user_id_from,
            'user_id_to' => $user_id_to,
            'bso_manager_id' => $bso_manager_id,
            'location_from' => $location_from,
            'location_to' => $location_to,
            'bso_state_id' => $bso_state_id,
            'act_number' => $act_number,
            'act_number_int' => $act_number_int,
        ]);



        return response($acts->id);

    }


    public function get_bso_sold(Request $request)
    {
        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;
        $type_bso = (int)$json->type_bso;// 1 БСО 2 квитанция
        $bso_supplier_id = (int)$json->bso_supplier_id;
        $bso_agent_id = (int)$json->bso_agent_id;
        $bso_used = $json->bso_used;

        $res = [];
        $res["suggestions"] = [];

        $bso_items = BsoItem::getBso();

        $bso_items->whereNotIn('bso_title', $bso_used);
        //$bso_items->where('location_id', '=',1);
        $bso_items->where('state_id', '=',2);

        if($type_bso == 1) $bso_items->where('bso_class_id', '!=', 100);
        if($type_bso == 2){
            $bso_items->where('bso_class_id', '=',100);
            if($bso_supplier_id > 0) $bso_items->where('bso_supplier_id', $bso_supplier_id);
            if($bso_agent_id > 0) $bso_items->where('agent_id', $bso_agent_id);
        }

        if(isset($request['product_id'])){
            $bso_items->where('product_id', $request['product_id']);
        }


        $bso_items->where(function($query) use ($like_query)
        {
            $query->where('bso_number', 'like', "%{$like_query}%")
                ->orWhere('bso_title', 'like', "%{$like_query}%");
        });

        $bso_items->orderBy('state_id');
        $bso_items->limit($count);

        foreach ($bso_items->get() as $bso)
        {
            $data = [];
            $data["value"] = $bso->bso_title;
            $data["unrestricted_value"] = $bso->bso_title;
            $data["data"] = [];
            $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
            $data["data"]['bso_title'] = $bso->bso_title;
            $data["data"]['bso_sk'] = $bso->supplier->title;
            $data["data"]['agent_name'] = ($bso->user)?$bso->user->name:'';

            $data["data"]['bso_id'] = $bso->id;
            $data["data"]['bso_supplier_id'] = $bso->bso_supplier_id;
            $data["data"]['insurance_companies_id'] = $bso->insurance_companies_id;
            $data["data"]['product_id'] = $bso->product_id;
            $data["data"]['agent_id'] = $bso->user_id;


            $res["suggestions"][] = $data;
        }

        return response()->json($res);
    }

    public function get_clear_bso(Request $request)
    {
        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;

        $type_selector = isset($json->type_selector) ? $json->type_selector : 0;// Тип БСО
        $series_selector = isset($json->series_selector) ? $json->series_selector : 0; //СЕРИИЯ
        $point_sale = isset($json->point_sale) ? $json->point_sale : 0; //Точка
        $bso_supplier_id = (int)$json->bso_supplier_id;

        $bso_items = BsoItem::getBso();

        $bso_items->where('state_id', '=',0);
        $bso_items->where('location_id', '=',0);
        $bso_items->where('bso_supplier_id', $bso_supplier_id);

        if($type_selector > 0){
            $bso_items->where('type_bso_id', $type_selector);
        }

        if($series_selector > 0){
            $bso_items->where('bso_serie_id', $series_selector);
        }

        if($point_sale > 0){
            $bso_items->where('point_sale_id', $point_sale);
        }



        $bso_items->where('bso_number', 'like', "%{$like_query}%");



        $bso_items->limit($count);

        $res = [];
        $res["suggestions"] = [];

        foreach ($bso_items->get() as $bso)
        {
            $data = [];

            $bso_title = $bso->bso_title;
            $data["value"] = $bso->bso_title;
            $data["unrestricted_value"] = $bso->bso_title;
            $data["data"] = [];
            $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
            $data["data"]['bso_title'] = $bso->bso_title;
            $data["data"]['bso_sk'] = $bso->supplier->title;
            $data["data"]['agent_name'] = ($bso->user)?$bso->user->name:'';

            $data["data"]['bso_id'] = $bso->id;
            $data["data"]['bso_supplier_id'] = $bso->bso_supplier_id;
            $data["data"]['insurance_companies_id'] = $bso->insurance_companies_id;
            $data["data"]['product_id'] = $bso->product_id;
            $data["data"]['agent_id'] = $bso->user_id;

            $data["data"]['type_bso_id'] = $bso->type_bso_id;
            $data["data"]['bso_serie_id'] = $bso->bso_serie_id;
            $data["data"]['bso_number'] = $bso->bso_number;


            $bso_serie = BsoSerie::where('insurance_companies_id', $bso->insurance_companies_id)
                ->where('type_bso_id', $bso->type_bso_id);

            $data["data"]['select_bso_serie'] = $bso_serie->get(['bso_serie', 'id'])->toArray();

            $res["suggestions"][] = $data;
        }

        return response()->json($res);
    }


    public function get_bso_contracts(Request $request)
    {
        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;

        $type = isset($json->type_contract) ? $json->type_contract : 0; //type = 0 все договора 1 действующий

        $bso_items = BsoItem::getBso();
        $bso_items->where('bso_items.state_id', '=',2);
        $bso_items->leftJoin('contracts', 'contracts.bso_id', '=', 'bso_items.id');
        $bso_items->where('contracts.statys_id', '=',4);


        if($type == 1){
            $bso_items->where('contracts.end_date', '>=',getDateTime());
            $bso_items->where('contracts.kind_acceptance', '=',1);
        }

        $bso_items->where('bso_items.bso_number', 'like', "%{$like_query}%");
        $bso_items->select(['bso_items.*']);
        $bso_items->limit($count);

        $res = [];
        $res["suggestions"] = [];

        foreach ($bso_items->get() as $bso)
        {
            $data = [];

            $bso_title = $bso->bso_title;
            $data["value"] = $bso->bso_title;
            $data["unrestricted_value"] = $bso->bso_title;
            $data["data"] = [];
            $data["data"]['bso_id'] = $bso->id;
            $data["data"]['product'] = $bso->product->title;
            $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
            $data["data"]['bso_title'] = $bso->bso_title;
            $data["data"]['bso_sk'] = $bso->supplier->title;

            $data["data"]['agent_name'] = ($bso->agent)?$bso->agent->name:'';
            $data["data"]['agent_organization'] = ($bso->agent)?$bso->agent->organization->title:'';

            $data["data"]['contract_data'] = setDateTimeFormatRu($bso->contract->begin_date,1)." - ".setDateTimeFormatRu($bso->contract->end_date,1);

            $data["data"]['insurer'] = $bso->contract->insurer->title;

            $data["data"]['product_id'] = $bso->product_id;
            $data["data"]['agent_id'] = $bso->user_id;

            $data["data"]['type_bso_id'] = $bso->type_bso_id;
            $data["data"]['bso_serie_id'] = $bso->bso_serie_id;
            $data["data"]['bso_number'] = $bso->bso_number;


            $res["suggestions"][] = $data;
        }

        return response()->json($res);
    }





    public function get_bso_order(Request $request)
    {
        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;

        $series_selector = isset($json->series_selector) ? $json->series_selector : '';

        $type_bso = (int)$json->type_bso;// 1 БСО 2 квитанция
        $bso_supplier_id = (int)$json->bso_supplier_id;
        $bso_agent_id = (int)$json->bso_agent_id;
        $order = Orders::find((int)$json->order_id);



        $res = [];
        $res["suggestions"] = [];

        $bso_items = BsoItem::getBso();


        $bso_items->where('state_id', '=',0);

        if($type_bso == 1){
            $bso_items->where('bso_class_id', '!=', 100);
        }
        if($type_bso == 2){
            $bso_items->where('bso_class_id', '=',100);

            if($bso_supplier_id > 0 && !empty($bso_supplier_id)) $bso_items->where('bso_supplier_id', $bso_supplier_id);




        }

        /*
        if(isset($request['product_id']) && (int)$request['product_id'] > 0){
            $bso_items->where('product_id', $request['product_id']);
        }
        */

        if($order->bso_cart_id > 0){

            $bso_cart_id = $order->bso_cart_id;

            $bso_items->where(function ($query) use($bso_agent_id, $bso_cart_id) {
                $query->where('agent_id', '=', $bso_agent_id)
                    ->orWhere('bso_cart_id', '=', $bso_cart_id);
            });

        }else{
            if($bso_agent_id > 0 && !empty($bso_agent_id)) $bso_items->where('agent_id', $bso_agent_id);
        }






        $bso_items->where(function($query) use ($like_query)
        {
            $query->where('bso_number', 'like', "%{$like_query}%")
                ->orWhere('bso_title', 'like', "%{$like_query}%");
        });

        //dd(getLaravelSql($bso_items));


        $bso_items->orderBy('state_id');
        $bso_items->limit($count);



        if (!empty($series_selector)){
            foreach ($bso_items->get() as $bso)
            {
                $data = [];
                $temp___ = stristr($bso->bso_title, ' ');
                $temp___ = str_replace(' ', '', $temp___);

                $bso_title = $temp___;
                $data["value"] = $temp___;
                $data["unrestricted_value"] = $temp___;
                $data["data"] = [];
                $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
                $data["data"]['bso_title'] = $bso->bso_title;
                $data["data"]['bso_sk'] = $bso->supplier->title;
                $data["data"]['agent_name'] = ($bso->user)?$bso->user->name:'';

                $data["data"]['bso_id'] = $bso->id;
                $data["data"]['bso_supplier_id'] = $bso->bso_supplier_id;
                $data["data"]['insurance_companies_id'] = $bso->insurance_companies_id;
                $data["data"]['product_id'] = $bso->product_id;
                $data["data"]['agent_id'] = $bso->user_id;

                $res["suggestions"][] = $data;
            }
        }else{
            foreach ($bso_items->get() as $bso)
            {
                $data = [];

                $bso_title = $bso->bso_title;
                $data["value"] = $bso->bso_title;
                $data["unrestricted_value"] = $bso->bso_title;
                $data["data"] = [];
                $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
                $data["data"]['bso_title'] = $bso->bso_title;
                $data["data"]['bso_sk'] = $bso->supplier->title;
                $data["data"]['agent_name'] = ($bso->user)?$bso->user->name:'';

                $data["data"]['bso_id'] = $bso->id;
                $data["data"]['bso_supplier_id'] = $bso->bso_supplier_id;
                $data["data"]['insurance_companies_id'] = $bso->insurance_companies_id;
                $data["data"]['product_id'] = $bso->product_id;
                $data["data"]['agent_id'] = $bso->user_id;
                $data["data"]['isset_hold_kv'] = $bso->product ? $bso->supplier->hold_kv_product($bso->product->id) ? 1 : 0 : 1;

                $res["suggestions"][] = $data;
            }
        }




        return response()->json($res);

    }

    public function get_bso(Request $request)
    {
        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;

        $series_selector = isset($json->series_selector) ? $json->series_selector : '';

        $type_bso = (int)$json->type_bso;// 1 БСО 2 квитанция
        $bso_supplier_id = (isset($json->bso_supplier_id))?(int)$json->bso_supplier_id:0;
        $bso_agent_id = (isset($json->bso_agent_id))?(int)$json->bso_agent_id:0;
        $bso_used = [];
        $temp_bso_used = [];

        if(isset($json->bso_used)){
            foreach ($json->bso_used as $k => $b){
                if (!empty($b)){
                    if (!empty($series_selector)){
                        $temp_bso_used[] = $series_selector." ".$b;
                    }else{
                        $temp_bso_used[] = $b;
                    }

                }
            }
        }

        $bso_used = $temp_bso_used;

        $res = [];
        $res["suggestions"] = [];

        $bso_items = BsoItem::getBso();

        $bso_items->whereNotIn('bso_title', $bso_used);

        $bso_items->where('state_id', '=',0);

        if($type_bso == 1){
            $bso_items->where('bso_class_id', '!=', 100);
            if (empty($series_selector)){
                $bso_items->where('location_id', '=',1);
            }else{
                $bso_items->where('location_id', '=',0);
            }
        }
        if($type_bso == 2){
            $bso_items->where('bso_class_id', '=',100);

            if($bso_supplier_id > 0) $bso_items->where('bso_supplier_id', $bso_supplier_id);

        }

        $bso_agent = User::find($bso_agent_id);
        if($bso_agent->organization && $bso_agent->organization->parent_user && $bso_agent->organization->org_type->is_provider == 0){
            $bso_agent = $bso_agent->organization->parent_user;
        }

        if($bso_agent_id > 0) $bso_items->where('user_id', $bso_agent->id);

        if(isset($request['product_id']) && (int)$request['product_id'] > 0){
            $bso_items->where('product_id', $request['product_id']);
        }


        $bso_items->where(function($query) use ($like_query)
        {
            $query->where('bso_number', 'like', "%{$like_query}%")
                ->orWhere('bso_title', 'like', "%{$like_query}%");
        });

        /*
        if (!empty($series_selector))
        {
            $bso_items->where('bso_title', 'like', "%{$series_selector}%");
        }
        */


        //dd(getLaravelSql($bso_items));

        $bso_items->orderBy('state_id');
        $bso_items->limit($count);



        if (!empty($series_selector)){
            foreach ($bso_items->get() as $bso)
            {
                $data = [];
                $temp___ = stristr($bso->bso_title, ' ');
                $temp___ = str_replace(' ', '', $temp___);

                $bso_title = $temp___;
                $data["value"] = $temp___;
                $data["unrestricted_value"] = $temp___;
                $data["data"] = [];
                $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
                $data["data"]['bso_title'] = $bso->bso_title;
                $data["data"]['bso_sk'] = $bso->supplier->title;
                $data["data"]['agent_name'] = ($bso->user)?$bso->user->name:'';

                $data["data"]['bso_id'] = $bso->id;
                $data["data"]['bso_supplier_id'] = $bso->bso_supplier_id;
                $data["data"]['insurance_companies_id'] = $bso->insurance_companies_id;
                $data["data"]['product_id'] = $bso->product_id;
                $data["data"]['agent_id'] = $bso->user_id;

                $res["suggestions"][] = $data;
            }
        }else{
            foreach ($bso_items->get() as $bso)
            {
                $data = [];

                $isset_hold_kv = 1;
                if($bso->bso_class_id != 100){
                    $isset_hold_kv = $bso->product ? $bso->supplier->hold_kv_product($bso->product->id) ? 1 : 0 : 1;
                }

                $bso_title = $bso->bso_title;
                $data["value"] = $bso->bso_title;
                $data["unrestricted_value"] = $bso->bso_title;
                $data["data"] = [];
                $data["data"]['bso_type'] = $bso->type->title.' ('.$bso->bso_states->title.')';
                $data["data"]['bso_title'] = $bso->bso_title;
                $data["data"]['bso_sk'] = $bso->supplier->title;
                $data["data"]['agent_name'] = ($bso->user)?$bso->user->name:'';

                $data["data"]['bso_id'] = $bso->id;
                $data["data"]['bso_supplier_id'] = $bso->bso_supplier_id;
                $data["data"]['insurance_companies_id'] = $bso->insurance_companies_id;
                $data["data"]['product_id'] = $bso->product_id;
                $data["data"]['agent_id'] = $bso->user_id;
                $data["data"]['isset_hold_kv'] = $isset_hold_kv;

                $res["suggestions"][] = $data;
            }
        }




        return response()->json($res);

    }


    public function get_installment_algorithms(Request $request)
    {

        $insurance_companies_id = $request->insurance_companies_id;

        $result = [];

        $algorithms = InstallmentAlgorithms::where('insurance_companies_id', $insurance_companies_id)->get();
        foreach ($algorithms as $algo){
            $result[] = collect(['id'=>$algo->id, 'title'=>InstallmentAlgorithms::ALG_TYPE[$algo->algorithm_id]]);
        }

        return response()->json($result);

    }

    public function get_financial_policy(Request $request)
    {

        $insurance_companies_id = $request->insurance_companies_id;
        $bso_supplier_id = $request->bso_supplier_id;
        $product_id = $request->product_id;

        $result = [];

        $fp = FinancialPolicy::where('insurance_companies_id', $insurance_companies_id)
            ->where('bso_supplier_id', $bso_supplier_id)
            ->where('product_id', $product_id)
            ->where('is_actual', 1)
            ->orderBy('title')->get(['id', 'title']);


        $result = $fp->toArray();

        return response()->json($result);

    }

    public function get_html_mini_contract_object_insurer(Request $request)
    {
        $product_id = $request->product_id;

        $product = Products::find($product_id);

        $object = new ObjectInsurer();

        if($product->category->template == 'auto')
        {
            $object->type = 1;
        }

        return view('contracts.contract_object_insurer.mini.'.$product->category->template, [
            'key' => $request->key,
            'object' => $object,
        ]);

    }


    public function get_orders_front(Request $request)
    {
        $json = \GuzzleHttp\json_decode($request->getContent());
        $count = $json->count;
        $like_query = $json->query;

        $front = new IntegrationFront();

        $res = [];
        $res["suggestions"] = $front->search_orders($like_query, $count);

        return response()->json($res);
    }

    public function get_order_id_front(Request $request)
    {

        $order_id = $request->order_id;
        $front = new IntegrationFront();
        $res = $front->get_order_data($order_id);


        return response()->json($res);


    }


    public function status_party(Request $request)
    {
        return response('',200);
    }

}
