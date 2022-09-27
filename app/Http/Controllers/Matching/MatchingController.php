<?php

namespace App\Http\Controllers\Matching;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\ContractsScorings;
use App\Models\Contracts\Matching;
use App\Models\Contracts\UnderwritingCheckUserLog;
use App\Models\Settings\Notification;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use Illuminate\Http\Request;

class MatchingController extends Controller
{

    public function __construct() {

    }



    public function scoring($id, Request $request)
    {

        $scoring = ContractsScorings::find((int)$id);


        if($scoring){
            $json = null;
            if (strlen($scoring->json_response) > 0) {
                $json = \GuzzleHttp\json_decode($scoring->json_response);
            }
            if($json){
                dd($json);
            }

            /*
            return view('matching.scoring.view', [
                'scoring' => $scoring,
                'json' => $json,
            ]);
            */

        }

        return abort(303);
    }





}
