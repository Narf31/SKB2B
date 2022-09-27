<?php

namespace App\Http\Controllers\Integration\TitCRM;

use App\Http\Controllers\Controller;

use App\Models\Log\LogEvents;
use App\Services\Integration\TITOld\TITContracts;
use App\Services\Integration\TITOld\TITMarkModels;
use App\Services\Integration\TITOld\TITUser;
use App\Services\Integration\VtigerCRM;
use Illuminate\Http\Request;

class TitCRMController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:integration,tit_old');
    }


    public function index(Request $request)
    {



        return view('integration.tit.index', [

        ]);

    }

    public function startInfo(Request $request)
    {
        return response()->json($this->getDataResult($request->type, $request->start, $request->counts, $request->count_all, $request, 'info'));
    }


    public function updataInfo(Request $request)
    {
        return response()->json($this->getDataResult($request->type, $request->start, $request->counts, $request->count_all, $request, 'updata'));
    }


    public function updateMarkModel(Request $request)
    {
        $api = new TITMarkModels();

        $result = [
            true => ['msg' => 'Успех', 'state' => 'success'],
            false => ['msg' => 'Ошибка', 'state' => 'danger']
        ];

        $mark = $result[$api->updateMark()];
        $model = $result[$api->updateModel()];

        $result = [
            ['msg' => 'Обновления маркок - '.$mark['msg'], 'state'=>$mark['state']],
            ['msg' => 'Обновления моделей - '.$model['msg'], 'state'=>$model['state']],
        ];

        return response()->json($result);
    }


    public function getDataResult($type, $start, $counts, $count_all, $request, $state = 'info')
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Метод не найден";

        $_class = null;
        switch ($type) {
            case 'users':
                //$_class = new TITUser();
                break;
            case 'contracts':
                //$_class = new TITContracts();
                break;
        }


        if($_class){
            if($state == 'info'){
                $res = $_class->getDataInfo($start, $request);
            }
            if($state == 'updata'){
                $res = $_class->updateDataInfo($start, $counts, $request, $count_all);
            }

        }


        return $res;

    }




    public function clearSystem(Request $request)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Error ";

        /*
        if((int)$request->type == 1){//пользователи
            if(TITUser::clearUsers() == true){
                $res->state = 0;
                $res->msg = "Пользователи удалены";
            }

        }

        if((int)$request->type == 2){//Договора
            if(TITContracts::clearContracts() == true){
                $res->state = 0;
                $res->msg = "Договора удалены";
            }

        }

        */


        return response()->json($res);

    }


}
