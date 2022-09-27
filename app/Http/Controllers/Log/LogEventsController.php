<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;

use App\Models\Log\LogEvents;
use Illuminate\Http\Request;

class LogEventsController extends Controller
{



    public function index(Request $request)
    {

        $object_id = (int)$request->object_id;
        $type_id = (int)$request->type_id;
        $view_all = (int)$request->view_all;
        //view_all 0 по объекту, 1 Видеть по родителю, 2 Видеть все

        $logs = LogEvents::query();

        switch ($view_all) {
            case 0:
                $logs->where('type_id', $type_id);
                $logs->where('object_id', $object_id);
                break;
            case 1:
                //$logs->where('type_id', $type_id);
                $logs->where('perent_object_id', $object_id);
                break;
            case 2:
                $logs->where('root_object_id', $object_id);
                break;
        }

        $logs->orderBy('created_at', 'desc');

        return view('log.index', [
            'logs' => $logs->get()
        ]);

    }




}
