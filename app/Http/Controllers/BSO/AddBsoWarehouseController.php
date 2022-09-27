<?php

namespace App\Http\Controllers\BSO;

use App\Http\Controllers\Controller;

use App\Models\BSO\BsoActs;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\TypeBso;
use App\Models\User;
use Illuminate\Http\Request;

class AddBsoWarehouseController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:bso,add_bso_warehouse');
    }


    public function index(Request $request)
    {
        
        if(isset($request->bso_supplier_id) && $request->bso_supplier_id > 0 ){
            $bso_supplier = BsoSuppliers::findOrFail($request->bso_supplier_id);
        }else{

             $bso_supplier = BsoSuppliers::query()->get()->first();

        }

        $bso_type = TypeBso::where('type_bso.is_actual', 1)
            ->where('type_bso.insurance_companies_id', $bso_supplier->insurance_companies_id)
            ->orderBy('title', 'asc')
            ->get();


        $acts = BsoActs::where('bso_manager_id', auth()->id())
            ->orderBy('time_create', 'desc')
            ->limit(10)
            ->get();

        $agents = User::getALLUser(24)->pluck('name', 'id');


        return view('bso.add_bso_warehouse.index', [
            'bso_supplier_id' => $bso_supplier->id,
            'bso_type' => $bso_type->pluck('title', 'id'),
            'acts' => $acts,
            'agents' => $agents,
        ]);

    }


    public function add_bso(Request $request)
    {

        $return              = new \stdClass();
        $return->error_state = 0;

        $obj = (object)\GuzzleHttp\json_decode($request->obj);
        $ignore_errors    = (int)$obj->ignore_errors;
        $bso_supplier_id  = (int)$obj->bso_supplier_id;
        $tp_id            = (int)$obj->tp_id;
        $user_id          = (int)auth()->id();
        $act_number       = $obj->act_number;
        $bso_act_id       = $obj->bso_act_id;
        $time_create      = date('Y-m-d H:i:s');
        $time_target      = date('Y-m-d H:i:s', strtotime($obj->time_create));
        $log_time         = date('Y-m-d H:i:s');
        $ip_address       = $_SERVER['REMOTE_ADDR'];
        $bso_type_id      = $obj->bso_type_id;

        $product_id = 0;
        $bso_type = null;

        $bso_supplier = ($bso_supplier_id > 0)?BsoSuppliers::findOrFail($bso_supplier_id):null;

        if($bso_type_id > 0){
            $bso_type = TypeBso::findOrFail($bso_type_id);
            $product_id = ($bso_type->product)?$bso_type->product->id:0;
        }


        $bso_type_value = ($bso_type)?$bso_type->title : '';

        $bso_serie_id = (int)$obj->bso_serie_id;
        $bso_serie_value = (isset($obj->bso_serie_value)) ? $obj->bso_serie_value : '';
        $bso_qty = (int)$obj->bso_qty;
        $bso_number_from = $obj->bso_number_from;


        if ((int)$bso_serie_id == 0) {
            $return->error_state = 1;
            $return->error_attr  = 2;
            $return->error_title = 'Укажите серию';
            return response()->json($return);
        }

        $bso_serie = BsoSerie::findOrFail($bso_serie_id);

        if (strlen($bso_number_from) > 15) {


            $number_tmp = (int)substr($bso_number_from, 15, strlen($bso_number_from));

            $bso_number = substr($bso_number_from, 0, 15) . $number_tmp;

        } else {
            $number_to = $bso_number_from + $bso_qty - 1;

            $bso_number_to = str_pad($number_to, strlen($bso_number_from), '0', STR_PAD_LEFT);
        }


        $bso_dop_serie_id    = $obj->bso_dop_serie_id;
        $bso_dop_serie_value = (isset($obj->bso_dop_serie_value) && $obj->bso_dop_serie_id > 0) ? $obj->bso_dop_serie_value : '';

        $bso_blank_serie_id    = $obj->bso_blank_serie_id;
        $bso_blank_serie_value = (isset($obj->bso_blank_serie_value) && $obj->bso_blank_serie_id > 0) ? $obj->bso_blank_serie_value : '';

        $bso_blank_number_from = $obj->bso_blank_number_from;
        $bso_blank_number_to   = $obj->bso_blank_number_to;

        $bso_blank_dop_serie_id    = $obj->bso_blank_dop_serie_id;
        $bso_blank_dop_serie_value = (isset($obj->bso_blank_dop_serie_id) && (int)$obj->bso_blank_dop_serie_id >0) ? $obj->bso_blank_dop_serie_value : '';


        $bso_number_from_int       = $bso_number_from;
        $bso_blank_number_from_int = $bso_blank_number_from;


        if ($bso_type_id > 0) {

            if ($bso_number_from_int == 0) {
                $return->error_state = 1;
                $return->error_attr  = 4;
                $return->error_title = 'Введите значение';
                return response()->json($return);
            }
            if ($bso_qty == 0) {
                $return->error_state = 1;
                $return->error_attr  = 3;
                $return->error_title = 'Введите значение';
                return response()->json($return);
            }
        } else {
            $return->error_state = 2; // пустая строка
            return response()->json($return);
        }

        $sk_id        = $bso_serie->insurance_companies_id;
        $bso_class_id = $bso_serie->bso_class_id;

        // Проверка на то что бланки уже были введены ранее
        $doubles = 0;
        $i       = 0;
        if ($ignore_errors == 0) {
            while ($i < $bso_qty) {
                $sql  = "select count(*) as doubles from bso_items where insurance_companies_id= $sk_id and type_bso_id=$bso_type_id and (location_id !=2 or (location_id =2 and state_id != 0) ) and bso_title in ( ";
                $stop = min($i + 2000, $bso_qty);
                while ($i < $stop) {


                    if (strlen($bso_number_from_int) > 15) {

                        $number_tmp = (int)substr($bso_number_from_int, 15, strlen($bso_number_from_int)) + $i;
                        $bso_number = substr($bso_number_from_int, 0, 15) . $number_tmp;

                    } else {
                        $bso_number_int = (int)$bso_number_from_int + $i;
                        $bso_number     = str_pad($bso_number_int, strlen($bso_number_from), '0', STR_PAD_LEFT);
                    }


                    //$bso_number_int = $bso_number_from_int + $i;
                    //$bso_number = str_pad($bso_number_int, strlen($bso_number_from), '0', STR_PAD_LEFT);
                    $bso_title = $bso_serie_value .' '. $bso_number . $bso_dop_serie_value;

                    if ($bso_blank_number_from != '') {
                        $bso_blank_number_int = $bso_blank_number_from_int + $i;
                        $bso_blank_number     = str_pad($bso_blank_number_int, strlen($bso_blank_number_from), '0', STR_PAD_LEFT);
                        $bso_blank_title      = $bso_blank_serie_value .' '. $bso_blank_number . $bso_blank_dop_serie_value;
                    } else {
                        $bso_blank_number = '';
                        $bso_blank_title  = '';
                    }
                    $sql .= "'$bso_title',";
                    $i++;
                }
                $sql = substr($sql, 0, -1);
                $sql .= ')';


                $doubles = (int)\DB::select($sql)[0]->doubles;


            }
            if ($doubles > 0) {
                $return->error_state = 1;
                $return->error_attr  = 1;
                $return->error_title = 'Ошибка. ' . $doubles . ' БСО уже были добавлены ранее. <input type="button" class="ignore_errors" value="Все равно принять" />';
                return response()->json($return);
            }

        }

        $state_id    = 0;
        $location_id = 0;


        // обновление информации по БСО, которые были повторно возвращены из СК
        $i = 0;

        if ($ignore_errors == 0) {
            while ($i < $bso_qty) {
                $sql  = "update bso_items set bso_supplier_id=$bso_supplier_id, org_id={$bso_supplier->purpose_org_id}, point_sale_id=$tp_id, state_id=$state_id, location_id=$location_id, user_id=0, time_create='$time_create', time_target='$time_target', last_operation_time='$log_time' where insurance_companies_id= $sk_id and type_bso_id=$bso_type_id and location_id=2 and bso_title in ( ";
                $stop = min($i + 2000, $bso_qty);
                while ($i < $stop) {

                    if (strlen($bso_number_from_int) > 15) {

                        $number_tmp = (int)substr($bso_number_from_int, 15, strlen($bso_number_from_int)) + $i;

                        $bso_number = substr($bso_number_from_int, 0, 15) . $number_tmp;

                    } else {
                        $bso_number_int = (int)$bso_number_from_int + $i;
                        $bso_number     = str_pad($bso_number_int, strlen($bso_number_from), '0', STR_PAD_LEFT);
                    }

                    $bso_title = $bso_serie_value .' '. $bso_number . $bso_dop_serie_value;
                    if ($bso_blank_number_from != '') {
                        $bso_blank_number_int = $bso_blank_number_from_int + $i;
                        $bso_blank_number     = str_pad($bso_blank_number_int, strlen($bso_blank_number_from), '0', STR_PAD_LEFT);
                        $bso_blank_title      = $bso_blank_serie_value .' '. $bso_blank_number . $bso_blank_dop_serie_value;
                    } else {
                        $bso_blank_number = '';
                        $bso_blank_title  = '';
                    }
                    $sql .= "'$bso_title',";
                    $i++;
                }
                $sql = substr($sql, 0, -1);
                $sql .= ')';

                \DB::update($sql);

            }

        }

        // Добавление БСО на склад
        $i = 0;
        while ($i < $bso_qty) {
            $sql  = "INSERT INTO bso_items (bso_supplier_id, insurance_companies_id, org_id, point_sale_id, bso_class_id, type_bso_id, state_id, location_id, user_id, time_create, time_target, last_operation_time, bso_serie_id, bso_number, bso_dop_serie_id, bso_title, bso_blank_serie_id, bso_blank_number, bso_blank_dop_serie_id, bso_blank_title, bso_act_id, product_id) VALUES ";
            $stop = min($i + 2000, $bso_qty);
            while ($i < $stop) {
                if (strlen($bso_number_from_int) > 15) {

                    $symbolNumber = 16;

                    if($bso_number_from_int[$symbolNumber] == 0){
                        for($j = 16; $j <= strlen($bso_number_from_int);$j++ ){
                            if($bso_number_from_int[$j] != 0){
                                $symbolNumber = $j;
                                break;
                            }
                        }
                    }

                    $number_tmp = (int)substr($bso_number_from_int, $symbolNumber, strlen($bso_number_from_int)) + $i;


                    $bso_number = substr($bso_number_from_int, 0, $symbolNumber) . $number_tmp;


                } else {
                    $bso_number_int = (int)$bso_number_from_int + $i;
                    $bso_number     = str_pad($bso_number_int, strlen($bso_number_from), '0', STR_PAD_LEFT);
                }

                //	$bso_number = str_pad($bso_number_int, strlen($bso_number_from), '0', STR_PAD_LEFT);

                $bso_title = $bso_serie_value .' '. $bso_number . $bso_dop_serie_value;

                if ($bso_blank_number_from != '') {
                    $bso_blank_number_int = $bso_blank_number_from_int + $i;
                    $bso_blank_number     = str_pad($bso_blank_number_int, strlen($bso_blank_number_from), '0', STR_PAD_LEFT);
                    $bso_blank_title      = $bso_blank_serie_value .' '. $bso_blank_number . $bso_blank_dop_serie_value;
                } else {
                    $bso_blank_number = '';
                    $bso_blank_title  = '';
                }

                $sql .= "('$bso_supplier_id', '$sk_id', '{$bso_supplier->purpose_org_id}', '$tp_id', '$bso_class_id', '$bso_type_id', '$state_id', '$location_id', '0', '$time_create', '$time_target', '$log_time', '$bso_serie_id', '$bso_number', '$bso_dop_serie_id', '$bso_title', '$bso_blank_serie_id', '$bso_blank_number', '$bso_blank_dop_serie_id', '$bso_blank_title', '$bso_act_id', '$product_id'),";
                $i++;
            }
            $sql = substr($sql, 0, -1);
            \DB::insert($sql);
        }


        // логируем добавление чистых БСО на склад
        $sql = "insert into bso_logs (log_time, bso_id, bso_act_id, bso_state_id, bso_location_id, bso_user_from, bso_user_to, user_id, ip_address)
SELECT '$log_time', id, '$bso_act_id', '$state_id', '$location_id', '$bso_supplier_id', '$user_id', '$user_id', '$ip_address' from bso_items where bso_act_id=$bso_act_id ";

        \DB::insert($sql);

        return response()->json($return);

    }



}
