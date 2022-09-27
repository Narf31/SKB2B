<?php

namespace App\Services\Front;


use App\Models\Settings\SettingsSystem;
use App\Services\Front\api\KansaltingRIT_V2_1;
use GuzzleHttp\Client;
use Illuminate\Http\Response;

class IntegrationFront
{

    private $protocol = 0;

    private $Front;

    public function __construct()
    {
        $this->protocol = (int)(SettingsSystem::getDataParam('front', 'protocol'));

        if($this->protocol == 1)
        {
            $this->Front = new KansaltingRIT_V2_1();
        }


    }

    public function search_orders($order, $count)
    {
        $res = [];

        if($this->protocol == 0){
            $data = [];
            $data["value"] = 'Интеграция не включена';
            $data["unrestricted_value"] = 'Интеграция не включена';
            $data["data"] = [];
            $data["data"]['id'] = 0;

            $res[] = $data;
        }else{

            $res = $this->Front->searchOrders($order, $count);
        }


        return $res;


    }

    public function search_users($name)
    {
        $res = [];

        if($this->protocol == 0){
            $data = [];
            $data["value"] = 'Интеграция не включена';
            $data["unrestricted_value"] = 'Интеграция не включена';
            $data["data"] = [];
            $data["data"]['id'] = 0;

            $res[] = $data;
        }else{

            $res = $this->Front->searchUsers($name);
        }


        return $res;


    }

    public function get_order_data($order_id)
    {
        return $this->Front->get_order_data($order_id);
    }





}