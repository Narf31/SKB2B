<?php

namespace App\Http\Controllers\Client\Damages;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Contracts;
use App\Models\Directories\Products;
use App\Models\Settings\City;
use App\Models\Settings\SettingsSystem;
use App\Processes\Scenaries\Contracts\Damages\Damage;
use Illuminate\Http\Request;

class DamagesClientController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {

        $products = Products::where('is_online', 1)->where('is_actual', 1)->get();

        return view('client.damages.index', [
            'products' => $products
        ]);

    }

    public function product($id)
    {
        $product = Products::where('id', $id)->where('is_actual', 1)->get()->first();
        if(!$product){
            abort(503);
        }

        $product_info = $product->get_products_info(2);

        return view('client.damages.product', [
            'product' => $product,
            'product_info' => $product_info,
        ]);
    }


    public function create(Request $request)
    {

        $client = auth()->guard('client')->user();

        return view('client.damages.orders.create', [
            'client' => $client,
            'city' => City::all(),
            'contracts' => $client->getActualContracts()
        ]);

    }

    public function save(Request $request)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = '';
        $result->damage_id = '';

        $data = (object)$request->all();


        if(strlen($data->comments) < 3){
            $result->msg = 'Укажите причину!';
        }

        if($data->position_type_id != 1){

            if(strlen($data->address) < 3 || strlen($data->latitude) < 3  || strlen($data->longitude) < 3){
                $result->msg = 'Укажите адрес!';
            }
        }

        if(strlen($data->phone) < 10){
            $result->msg = 'Укажите номер телефона!';
        }

        if(strlen($result->msg) == 0){
            $agent_id = SettingsSystem::getDataParam('client', 'user_id');
            $contract = Contracts::find($data->contract_id);

            if($contract){

                if($data->position_type_id == 2){
                    $data->date = date('Y-m-d');
                    $data->time = date('H:i');
                }

                $data->insurer_type_id = 0;
                $data->email = auth()->guard('client')->user()->email;


                $damage = Damage::create($contract->bso, $agent_id, 'Личный кабинет клиента', $data->comments);
                $result = Damage::save($damage->id, $agent_id, 1, $data);
                $result->damage_id = $damage->id;

            }else{
                $result->msg = 'Укажите договор!';
            }
        }



        return response()->json($result);
    }





}
