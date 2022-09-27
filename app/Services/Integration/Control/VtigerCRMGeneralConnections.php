<?php

namespace App\Services\Integration\Control;

use App\Models\Clients\GeneralPodftFl;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use function GuzzleHttp\Psr7\str;
use Mockery\Exception;

class VtigerCRMGeneralConnections
{



    public function getDataInfo($start, $request)
    {
        if(isset($request) && isset($request->delete) && $request->delete == 1){

        }


        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "";
        $res->start = 0;
        $res->result = null;

        $res->progressbar = 0;
        $res->count_all = GeneralSubjects::where('export_is_connection', 1)->count();

        return $res;
    }

    public function updateDataInfo($start, $counts, $request, $count_all)
    {
        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "VtigerCRM не настроен!";

        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "";
        $res->start = 0;
        $res->result = null;

        $res->progressbar = 0;
        $res->count_all = $count_all;

        $controller = getSqlPage($start, $counts, $count_all);

        $res->state = $controller->state;
        $res->start = $controller->next;
        $res->progressbar = $controller->progressbar;
        $res->count_all = $count_all;


        $generals = GeneralSubjects::where('export_is_connection', 1)->limit($counts)->get();
        foreach ($generals as $general){
            $general->export_is_connection = 0;
            $gOrg = GeneralSubjects::where('export_id', $general->export_org_id)->get()->first();
            if($gOrg){
                $podft = $general->podft;
                $podft->general_organization_id = $gOrg->id;
                $podft->save();
            }
            $general->save();
        }


        return $res;
    }

    public function connectionDataInfo()
    {
        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "Физ.лица связаны";



        return $res;
    }






}