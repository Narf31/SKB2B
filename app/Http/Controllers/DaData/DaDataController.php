<?php

namespace App\Http\Controllers\DaData;

use App\Http\Controllers\Controller;
use App\Models\Clients\GeneralSubjects;
use App\Models\Contracts\Contracts;
use App\Models\Organizations\Organization;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
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
        $query = (string)$data->query;
        $fio = explode(' ', $query);
        if(count($fio) >= 4){
            $response = GeneralSubjectsSearch::search($query, (int)$data->count);
        }else{
            $response = $this->dadata->getFIO($query, (int)$data->count);
        }


        return response()->json($response);
    }

    public function party(Request $request)
    {
        $data = \GuzzleHttp\json_decode($request->getContent());
        $response = $this->dadata->getParty((string)$data->query, (int)$data->count);
        return response()->json($response);
    }


    public function generalUL(Request $request)
    {
        $type = 1;
        if(isset($request->type)) $type = $request->type;
        return self::generalSearch($type, $request);
    }

    public function generalFL(Request $request)
    {
        return self::generalSearch(0, $request);
    }



    public function generalSearch($type, Request $request)
    {
        $response = new \stdClass();
        $response->suggestions = [];

        $data = \GuzzleHttp\json_decode($request->getContent());
        $query = (string)$data->query;

        $generals = GeneralSubjects::getAllGeneralSubjects($type, auth()->user());
        $generals->where('general_subjects.label', 'like', "%{$query}%");
        $generals->limit(5);

        foreach ($generals->get() as $general){
            $data = new \stdClass();
            $data->id = $general->id;
            $data->value = $general->label;
            $data->default_text = $general->title;
            $data->data = new \stdClass();

            $response->suggestions[] = $data;

        }


        return response()->json($response);
    }


    public function organization(Request $request)
    {
        $response = new \stdClass();
        $response->suggestions = [];

        $data = \GuzzleHttp\json_decode($request->getContent());
        $query = (string)$data->query;

        $organizations = Organization::getOrgIsContract();
        $organizations->where('organizations.title', 'like', "%{$query}%");
        $organizations->limit(5);

        foreach ($organizations->get() as $organization){
            $data = new \stdClass();
            $data->id = $organization->id;
            $data->value = $organization->title;
            $data->default_text = $organization->title;
            $data->data = new \stdClass();

            $response->suggestions[] = $data;

        }


        return response()->json($response);
    }


    public function prolongation(Request $request)
    {
        $response = new \stdClass();
        $response->suggestions = [];

        $data = \GuzzleHttp\json_decode($request->getContent());
        $query = (string)$data->query;

        $contracts = Contracts::getContracts([4]);
        $contracts->where('contracts.product_id', (int)$request->product);
        $contracts->where('contracts.bso_title', 'like', "%{$query}%");
        $contracts->limit(5);


        foreach ($contracts->get() as $contract){
            $data = new \stdClass();
            $data->id = $contract->id;
            $data->value = $contract->bso_title.' - '.$contract->insurer->title;
            $data->default_text = $contract->bso_title;
            $data->data = new \stdClass();

            $response->suggestions[] = $data;

        }



        return response()->json($response);
    }


}
