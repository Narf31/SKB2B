<?php

namespace App\Http\Controllers\Integration\Verna;

use App\Http\Controllers\Controller;

use App\Models\Log\LogEvents;
use App\Services\Integration\VernaAPI;
use App\Services\Integration\VtigerCRM;
use Illuminate\Http\Request;

class VernaController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:integration,verna');
    }


    public function index(Request $request)
    {
        return view('integration.verna.index', []);
    }


    public function updata(Request $request)
    {
        return response()->json(VernaAPI::updateDirectories($request->type));
    }



}
