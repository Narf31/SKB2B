<?php

namespace App\Http\Controllers\VermaAPI;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\Bank;
use Illuminate\Http\Request;

class VermaAPIController extends Controller
{

    public function __construct()
    {

    }

    public function data(Request $request)
    {
        \Log::info($request->getContent());

        return response('OK', 200);
    }


}
