<?php

namespace App\Http\Controllers\General\Subjects;

use App\Http\Controllers\Controller;

use App\Models\Clients\GeneralFounders;
use App\Models\Clients\GeneralInteractionsConnections;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures;
use App\Models\Log\LogEvents;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use Illuminate\Http\Request;

class ActionGeneralSubjectsController extends Controller
{




    public function setInteractionsConnections($id, $ic_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        $job_position = '';

        if($ic_id == 0){
            $ic = new GeneralInteractionsConnections();
            $ic->general_subject_id = $id;
            $ic->type_id = (int)$request->type;

            if($general->type_id == 0){
                $job_position = GeneralSubjectsFl::PROFESSIO[$general->data->profession_id];
            }


        }else{
            $ic = GeneralInteractionsConnections::find($ic_id);
            $job_position = $ic->job_position;
        }


        return view("general.subjects.info.interactions_connections.frame", [
            'general' => $general,
            'ic' => $ic,
            'ic_id' => (int)$ic_id,
            'job_position' => $job_position,
            'type' => (int)$request->type,
        ]);

    }

    public function saveInteractionsConnections($id, $ic_id, Request $request)
    {

        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }


        if($ic_id == 0){
            $ic = new GeneralInteractionsConnections();
            $ic->general_subject_id = $id;
            $ic->type_id = (int)$request->type;
            $ic->save();
        }else{
            $ic = GeneralInteractionsConnections::find($ic_id);
        }

        $ic->saveData($request);

        return parentReloadTab();

    }


    public function setFounders($id, $founder_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        if($founder_id == 0){
            $founder = new GeneralFounders();
            $founder->general_subject_id = $id;
            $founder->type_id = (int)$request->type;


        }else{
            $founder = GeneralFounders::find($founder_id);
        }

        return view("general.subjects.info.founder.frame", [
            'general' => $general,
            'founder' => $founder,
            'founder_id' => (int)$founder_id,
            'type' => (int)$request->type,
        ]);

    }


    public function saveFounders($id, $founder_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        if($founder_id == 0){
            $founder = new GeneralFounders();
            $founder->general_subject_id = $id;
            $founder->type_id = (int)$request->type;
            $founder->save();

        }else{
            $founder = GeneralFounders::find($founder_id);
        }

        $founder->saveData($request);

        GeneralSubjectsLogs::setLogs($general->id, "Добавление ".GeneralFounders::TYPE[$founder->type_id]." {$founder->general_founders->title}");


        return parentReloadTab();
    }

    public function deleteFounders($id, $founder_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        $founder = GeneralFounders::find($founder_id);

        GeneralSubjectsLogs::setLogs($general->id, "Удаление ".GeneralFounders::TYPE[$founder->type_id]." {$founder->general_founders->title}");
        $founder->delete();
        return parentReloadTab();

    }







}
