<?php

namespace App\Http\Controllers\Integration\VtigerCRM;

use App\Http\Controllers\Controller;

use App\Models\Log\LogEvents;
use App\Services\Integration\VtigerCRM;
use Illuminate\Http\Request;

class VtigerCRMController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:integration,vtigercrm');
    }


    public function index(Request $request)
    {



        return view('integration.vtigercrm.index', [

        ]);

    }

    public function startInfo(Request $request)
    {
        return response()->json(VtigerCRM::getDataResult($request->type, $request->start, $request->counts, $request->count_all, $request, 'info'));
    }


    public function updataInfo(Request $request)
    {
        return response()->json(VtigerCRM::getDataResult($request->type, $request->start, $request->counts, $request->count_all, $request, 'updata'));
    }

    public function connectionInfo(Request $request)
    {

        return response()->json(VtigerCRM::getDataResult($request->type, $request->start, $request->counts, $request->count_all, $request, 'connection'));
    }




    public function clearSystem(Request $request)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Error";

        $clean = 1;

        if($clean == 1){

            //VtigerCRM::clean();
            $res->state = 0;
            $res->msg = "Система очищина";

        }

        return response()->json($res);

    }


}
