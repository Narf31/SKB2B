<?php

namespace App\Http\Controllers\Client\DaData;

use App\Http\Controllers\Controller;
use App\Services\DaData\DaDataGetInfo;
use Illuminate\Http\Request;

class DaDataController extends Controller
{

    protected $dadata = null;

    public function __construct()
    {
        $this->dadata = new DaDataGetInfo();
    }

    public function status_address(Request $request)
    {
        return response()->json('{"search":true,"enrich":true,"state":"ENABLED","resources":[{"version":"31.08.2019 01:12:08","count":30669192,"name":"address"}],"name":"address"}');
    }

    public function status_fio(Request $request)
    {
        return response()->json('{"search":true,"enrich":false,"state":"ENABLED","resources":[{"version":"02.09.2019 00:02:28","count":972177,"name":"fio"}],"name":"fio"}');
    }

    public function status_party(Request $request)
    {
        return response()->json('{"search":true,"enrich":false,"state":"ENABLED","resources":[{"version":"02.09.2019 00:00:00","count":25922292,"name":"egr"}],"name":"party"}');
    }

    public function detectAddressByIp(Request $request)
    {
        return response(200);
    }



    public function address(Request $request)
    {
        $data = \GuzzleHttp\json_decode($request->getContent());
        $response = $this->dadata->getAddress((string)$data->query, (int)$data->count);
        return response()->json($response);
    }

    public function fio(Request $request)
    {
        $data = \GuzzleHttp\json_decode($request->getContent());
        $response = $this->dadata->getFIO((string)$data->query, (int)$data->count);
        return response()->json($response);
    }

    public function party(Request $request)
    {
        $data = \GuzzleHttp\json_decode($request->getContent());
        $response = $this->dadata->getParty((string)$data->query, (int)$data->count);
        return response()->json($response);
    }




}
