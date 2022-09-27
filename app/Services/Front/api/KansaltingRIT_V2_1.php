<?php

namespace App\Services\Front\api;

use App\Models\Settings\SettingsSystem;
use App\Models\User;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

class KansaltingRIT_V2_1
{

    private $url = '';

    public function __construct()
    {
        $this->url = SettingsSystem::getDataParam('front', 'url');
    }

    public function send($url, $data = null, $method = 'GET')
    {
        $headers = [];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->url}$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return \GuzzleHttp\json_decode($response);
    }


    public function searchOrders($order, $count)
    {
        $order = (int)$order;
        $result = $this->send("get_data_orders_list.aspx?order={$order}");

        $res = null;

        foreach ($result as $res_data)
        {

            $order_title = "Направление ".(string)$res_data->type_order." №".(int)$res_data->seria_order." / ".(int)$res_data->number_order." от ".(string)$res_data->date_order.' '.(string)$res_data->client_name;


            $data = [];
            $data["value"] = $order_title;
            $data["unrestricted_value"] = $order_title;
            $data["data"] = [];
            $data["data"]['id'] = $res_data->id_node;
            $data["data"]['title'] = $order_title;
            $data["data"]['insurer'] = $res_data->client_name;

            $res[] = $data;
        }

        return $res;

    }


    public function get_order_data($order_id)
    {


        $order_id = (int)$order_id;
        $result = $this->send("get_data_orders.aspx?order_id={$order_id}");

        $temp_auto = (array)\GuzzleHttp\json_decode((string)$result->object_insurer_auto);
        $temp_auto_str = $temp_auto["XmlФорма"];
        $temp_auto_str = str_replace('ctl00$ContentPlaceHolderBody$BaseOrder1$ctl01$','', $temp_auto_str);

        $temp_auto_xml = new \SimpleXMLElement($temp_auto_str);
        $temp_auto_arr = [];
        foreach ($temp_auto_xml->KeyValueOfstringstring as $temp_KeyValue){

            $temp_auto_arr[(string)$temp_KeyValue->Key] = (string)$temp_KeyValue->Value;
        }

        //dd($result);


        $manager_id = 0;
        $manager = User::where("front_user_id", (int)$result->manager_id)->get()->first();
        if($manager){
            $manager_id = $manager->id;
        }

        $model = (isset($temp_auto_arr['Модель'])?$temp_auto_arr['Модель']:'');
        $model = explode(' ', $model)[0];

        //ЗАПРОС К КАНСАЛТИНГУ
        $result = [
            'id'=>(int)$result->rit_order_id,
            'title'=>(string)$result->rit_order_title,
            'insurer'=>(string)$result->insurer,
            'insurer_type' => (((string)$result->insurer_type == 'ФизическоеЛицо')?0:1),
            'insurer_phone'=>getPhoneFormat((string)$result->phone),
            'insurer_email'=>'',
            'payment_total'=>getFloatFormat((string)$result->premium),
            'summ_qty'=>getFloatFormat((string)$result->summ_in_receipt),
            'payment_number'=>'1',
            'date_qty'=>setDateTimeFormatRu((string)$result->payment_date, 1),
            'official_discount'=>'',
            'informal_discount'=>getFloatFormat((string)$result->discount),
            'bank_kv' => getFloatFormat((string)$result->kv_bank),
            'sign_date'=>'',
            'begin_date'=>'',
            'end_date'=>'',
            'is_personal_sales'=> (((string)$result->personal_selling == 'False')?0:1),
            'manager_id'=>$manager_id,
            'object_insurer_title'=>(string)$result->ts,
            'object_insurer_auto' => [
                'power'=>'',
                'reg_number'=>'',
                'mark'=>(isset($temp_auto_arr['Марка'])?$temp_auto_arr['Марка']:''),
                'mark_id'=>'0',
                'model'=> $model,
                'model_id'=>'0',
                'vin'=>'',
            ]

        ];

        //dd($result);

        if(isset($result['object_insurer_auto']) && isset($result['object_insurer_auto']['mark'])){
            $mark = VehicleMarks::where('title', 'like', $result['object_insurer_auto']['mark']."%")->get()->first();

            if($mark){
                $result['object_insurer_auto']['mark_id'] = $mark->id;
                $model = VehicleModels::where('mark_id', $mark->id)->where('title', 'like', "%".$result['object_insurer_auto']['model']."%")->get()->first();
                if($model){
                    $result['object_insurer_auto']['model_id'] = $model->id;
                }
            }

        }


        return $result;

    }

    public function searchUsers($name)
    {

        $name = explode(' (', $name)[0];

        $res = null;

        $result = $this->send("users.aspx", "user_name={$name}", "POST");
        foreach ($result as $res_data)
        {
            $data = [];
            $data["value"] = (string)$res_data->user_title." (".(string)$res_data->user_login.")";
            $data["unrestricted_value"] = (string)$res_data->user_title." (".(string)$res_data->user_login.")";
            $data["data"] = [];
            $data["data"]['id'] = (int)$res_data->user_id;
            $data["data"]['title'] = (string)$res_data->user_title;

            $res[] = $data;
        }

        return $res;


    }

    //http://192.168.2.18/k/back_api/users.aspx?user_name=test




}