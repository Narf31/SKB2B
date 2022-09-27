<?php

namespace App\Http\Controllers\General\Subjects;

use App\Http\Controllers\Controller;

use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Log\LogEvents;
use App\Models\User;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Services\Integration\VernaControllers\VernaSubjects;
use App\Services\Scorings\ContourPrism;
use Illuminate\Http\Request;

class GeneralSubjectsController extends Controller
{



    public function search(Request $request)
    {

        if(!auth()->user()->hasPermission('subject', 'create'))
        {
            abort(303);
        }

        $type = (int)$request->type;

        $view = 'fl';
        if($type == 0){
            $view = 'fl';
        }

        if($type == 1){
            $view = 'ul';
        }


        return view("general.subjects.search.{$view}", [
            'contract_id' => (int)$request->contract_id,
            'subjects' => $request->subjects,
            'type' => $type
        ]);

    }


    public function searchFind(Request $request)
    {
        $type = (int)$request->type;
        $contract_id = (int)$request->contract_id;
        $subjects = $request->subjects;

        $hash = GeneralSubjectsInfo::getHash($type, $request);

        $general = GeneralSubjectsSearch::search_hash($type, $hash);
        if(!$general && $type == 0){
            return $this->createForm($request);
        }else{
            return $this->create($request);
        }

        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == true){
            if($contract_id > 0){
                return parentReload();
            }else{
                return parentRedirect("/general/subjects/edit/{$general->id}");
            }
        }

        $errors = 'Данный контрагент уже есть в системе!';
        return back()->withInput()->withErrors($errors);

    }

    public function createForm(Request $request)
    {
        $type = (int)$request->type;

        $view = 'fl';
        if($type == 0){
            $view = 'fl';
        }

        if($type == 1){
            $view = 'ul';
        }


        return view("general.subjects.search.create.{$view}", [
            'contract_id' => (int)$request->contract_id,
            'data' => $request,
            'type' => $type
        ]);
    }

    public function create(Request $request)
    {
        $type = (int)$request->type;
        $contract_id = (int)$request->contract_id;
        $subjects = $request->subjects;

        $hash = GeneralSubjectsInfo::getHash($type, $request);

        $general = GeneralSubjectsSearch::search_hash($type, $hash);
        if(!$general){
            $general = GeneralSubjectsInfo::createGeneralSubjectHash($type, $hash, auth()->user(), $request);
            $general->user_id = auth()->id();
            if($general->type_id == 0){
                return $this->saveData($general->id, $request, $contract_id, 1);
            }
        }

        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == true){
            if($contract_id > 0){
                return parentReload();
            }else{
                return parentRedirect("/general/subjects/edit/{$general->id}");
            }

        }

        $errors = 'Данный контрагент уже есть в системе!';
        return back()->withInput()->withErrors($errors);

    }

    public function edit($id)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        $this->breadcrumbs[] = [
            'label' => 'Контрагенты',
            'url' => 'subject/'.($general->type_id==0?"fl":"ul")
        ];

        $this->breadcrumbs[] = [
            'label' => $general->title,
        ];

        return view("general.subjects.info", [
            'general' => $general
        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function get_html_block($id, Request $request){
        $general = GeneralSubjects::find($id);
        $state = $general->getView(auth()->user());

        return view("{$request->view}", [
            'general' => $general,
            'state' => $state,
        ]);
    }


    public function frame($id, Request $request)
    {

        $contract_id = isset($request->contract_id)?(int)$request->contract_id:0;
        $subjects = isset($request->subjects)?$request->subjects:'';

        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        return view("general.subjects.form.frame", [
            'contract_id' => (int)$request->contract_id,
            'subjects' => $request->subjects,
            'general' => $general
        ]);

    }


    public function frameSaveData($id, Request $request)
    {

        $contract_id = isset($request->contract_id)?(int)$request->contract_id:0;
        $subjects = isset($request->subjects)?$request->subjects:'';

        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        GeneralSubjectsLogs::setLogs($general->id, 'Изменения данных');

        $general->is_resident = (isset($request->is_resident))?1:0;
        $general->citizenship_id = $request->citizenship_id;
        $general->data->saveFrame($request);


        foreach ($request->address as $address){
            $general_address = $general->getAddressType($address['type_id']);
            if((int)$general_address->id <= 0){
                $general_address->save();
            }
            $general_address->update($address);
        }


        if($general->type_id == 1165){
            if(isset($request->doc)){
                $doc = $request->doc[0];
                $general_documents = $general->getDocumentsType(1165);
                if((int)$general_documents->id <= 0){
                    $general_documents->save();
                }
                $doc['date_issue'] = getDateFormatEn($doc['date_issue']);
                $general_documents->update($doc);
            }
        }

        return redirect("/general/subjects/frame/{$general->id}?contract_id={$contract_id}&subjects={$subjects}");
    }





    public function saveData($id, Request $request, $contract_id = null, $is_parent_redirect = 0)
    {

        $general = GeneralSubjects::find($id);
        if (GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false) {
            abort(303);
        }

        $hash = GeneralSubjectsInfo::getHash($general->type_id, $request);

        if ($general->hash != $hash) {
            $general->hash = $hash;
        }
        if (isset($request->title)) {
            $general->title = $request->title;
            $general->label = $request->title;
        }

        if (isset($request->inn)) {
            $general->inn = $request->inn;
            if($general->type_id == 1){
                $general->label = "{$general->title} - {$request->inn}";
            }

        }

        if (isset($request->birthdate)) {
            $general->label = "{$general->title} - ".getDateFormatRu($request->birthdate);
        }




        $general->is_resident = (isset($request->is_resident)) ? 1 : 0;
        $general->citizenship_id = $request->citizenship_id;
        $general->phone = $request->phone;
        $general->email = $request->email;
        $general->comments = $request->comments;
        $general->person_category_id = (int)$request->person_category_id;
        $general->status_work_id = (int)$request->status_work_id;


        GeneralSubjectsLogs::setLogs($general->id, 'Изменения данных');

        if (isset($request->user_id)) {

            if ((int)$request->user_id > 0) {
                $user = User::getUserId($request->user_id);

                if ($general->user_id != $user->id) {
                    GeneralSubjectsLogs::setLogs($general->id, "Изменения ответственного сотрудника на {$user->name}");
                }

                $general->user_id = $user->id;
                $general->user_organization_id = $user->organization_id;
                $general->user_parent_id = $user->parent_id;
                $general->user_curator_id = $user->curator_id;


            } else {

                if ($general->user_id != 0) {
                    GeneralSubjectsLogs::setLogs($general->id, "Изменения ответственного сотрудника - Общий доступ");
                }

                $general->user_id = 0;
                $general->user_organization_id = null;
                $general->user_parent_id = null;
                $general->user_curator_id = null;


            }
        }


        $general->save();
        $general->data->saveFrame($request);

        if (isset($request->address)){
            foreach ($request->address as $address) {
                $general_address = $general->getAddressType($address['type_id']);
                if ((int)$general_address->id <= 0) {
                    $general_address->save();
                }
                $general_address->update($address);
            }
        }

        if(isset($request->doc)){
            $doc = $request->doc[0];
            $general_documents = $general->checkOrCreateDocumentsType($doc['type_id'],$doc['serie'],$doc['number']);
            if((int)$general_documents->id <= 0){
                $general_documents->save();
            }
            $doc['is_actual'] = isset($doc['is_actual'])?1:0;
            $doc['date_issue'] = getDateFormatEn($doc['date_issue']);
            $general_documents->update($doc);

            $this->switchMainDocument($general_documents,$general->documents());

            if((int)$general_documents->type_id == 1165){
                $prism = new ContourPrism();
                $passport = $general_documents;
                $general->status_work_id = 1;
                if($passport && strlen($passport->number) == 6){
                    $valid = $prism->getIndividualPassport("{$passport->serie} {$passport->number}");
                    if($valid && isset($valid->isInvalid) && $valid->isInvalid == false){
                        if($general->status_work_id != 2){
                            $general->status_work_id = 0;
                        }
                        $passport->is_check = 1;
                    }else{
                        $passport->is_check = 2;
                    }

                    $passport->save();
                }

                $general->save();
            }

        }


        if($contract_id){
            return parentReload();
        }

        if($is_parent_redirect == 1){
            return parentRedirect("/general/subjects/edit/{$general->id}");
        }
        return redirect("/general/subjects/edit/{$general->id}");
    }


    public function savePodft($id, Request $request)
    {
        $res = (object)['state'=> false, 'msg' => 'Не удалось сохранить данные.'];

        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        if($general->podft->saveData($request) == true){
            $res = (object)['state'=> true, 'msg' => ''];
            GeneralSubjectsLogs::setLogs($general->id, "Изменения данных - ПОД/ФТ");
        }

        return response()->json($res);

    }

    public function checkPodft($id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        return response()->json(GeneralSubjectsInfo::getCheckContourPrism($general));
    }

    public function updateInfoPodft($id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        GeneralSubjectsInfo::getGeneralSubjectInfoOnline($general);

        return response()->json((object)['state'=> true, 'msg' => '']);
    }




    public function saveSpecial($id, Request $request)
    {
        $res = (object)['state'=> true, 'msg' => 'Не удалось сохранить данные.'];

        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        $general->risk_level_id = $request->risk_level_id;
        $general->risk_user_id = $request->risk_user_id;
        $general->risk_date = setDateTimeFormat($request->risk_date);
        $general->risk_history = $request->risk_history;
        $general->risk_base = $request->risk_base;
        $general->risk_comments = $request->risk_comments;
        $general->save();


        return response()->json($res);

    }



    public function getDocument($id, $doc_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        if($doc_id == 0){
            $document = new GeneralSubjectsDocuments();
            $document->general_subject_id = $general->id;
        }else{
            $document = $general->documents()->where('id', $doc_id)->get()->first();
        }

        if($general->type_id == 0){
            $DOC_TYPE = collect(\App\Models\Contracts\SubjectsFlDocType::getDocType()->pluck('title', 'isn'));
        }else{
            $DOC_TYPE = \App\Models\Contracts\Subjects::DOC_TYPE_UL;
        }


        return view("general.subjects.info.documents.frame", [
            'general' => $general,
            'document' => $document,
            'doc_id' => (int)$doc_id,
            'DOC_TYPE' => $DOC_TYPE,
        ]);

    }

    public function saveDocument($id, $doc_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        if($doc_id == 0){
            $document = new GeneralSubjectsDocuments();
            $document->general_subject_id = $general->id;
            $document->save();
        }else{
            $document = $general->documents()->where('id', $doc_id)->get()->first();
        }


        if(isset($request->doc)){
            $doc = $request->doc[0];
            if($doc['type_id'] == 1165 && $general->checkDublicateDocument($document,$doc['type_id'],$doc['serie'],$doc['number'])){
                return redirect('/general/subjects/edit/'.$id.'/document/'.$doc_id)->withInput()->with('error','Документ с указанными серией и номером уже существует!');
            }
            $doc['is_actual'] = isset($doc['is_actual'])?1:0;
            $doc['date_issue'] = getDateFormatEn($doc['date_issue']);
            $document->update($doc);
        }

        return parentReloadTab();
    }

    public function switchMainDocument($document,$all_documents) {
        $all_documents->where('type_id', $document->type_id)
            ->where('id','!=',$document->id)->get();
        $all_documents->update(['is_main' => 0]);
        $all_documents->update(['is_actual' => 0]);

        return true;
    }

    public function deleteDocument($id, $doc_id, Request $request)
    {
        $general = GeneralSubjects::find($id);
        if(GeneralSubjectsInfo::checkAccessGeneralSubject($general, auth()->user()) == false){
            abort(303);
        }

        $document = $general->documents()->where('id', $doc_id)->get()->first();
        if($document){
           // if($document->is_main != 1){
                $document->delete();
           // }
        }

        return response('', 200);

    }






}
