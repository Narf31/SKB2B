<?php

namespace App\Http\Controllers\Contracts\Online;

use App\Domain\Process;
use App\Domain\Processes\Operations\Contracts\Online\ContractCalculationOperations;
use App\Domain\Samplers\Segments\SegmentsOnRequest;
use App\Interfaces\Services\SK\ProductServiceInterface;
use App\Models\Api\SK\ApiSetting;
use App\Models\Clients\GeneralSubjects;
use App\Models\Contracts\Driver;
use App\Models\Contracts\KbmCalculation;
use App\Models\Contracts\Subjects;
use App\Models\Directories\ProductsPrograms;
use App\Processes\Operations\Contracts\Contract\ContractDocuments;
use App\Processes\Operations\Contracts\Contract\ContractMasks;
use App\Processes\Operations\Contracts\Matchings\MatchingArbitration;
use App\Processes\Operations\Contracts\Matchings\MatchingKasko;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Services\Kbm\Alfa\Calc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\TypeBso;
use App\Models\Directories\ProductsCategory;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\Contracts;
use Illuminate\Support\Str;
use Mockery\Exception;

class ActionController extends Controller {

    public function __construct() {


    }


    public function subject($id, Request $request) {

        $subject = new Subjects();
        $subject_name = $request->name;

        $contract = Contracts::getContractId($id);
        if($subject_name == 'insurer'){
            if($contract->insurer) $subject = $contract->insurer;
        }

        if($subject_name == 'owner'){
            if($contract->owner) $subject = $contract->owner;
        }

        if($subject_name == 'beneficiar'){
            if($contract->beneficiar) $subject = $contract->beneficiar;
        }

        $type = (int)$request->type;

        if($type == 3) $type = 1;

        if($subject->type != $type ){
            $subject = new Subjects();
            $subject->type = $type;
        }


        $view_type = $contract->getViewStateOnline();

        return view("contracts.default.subject.partials.{$view_type}.{$request->type}", [
            'subject' => $subject,
            'subject_name' => $subject_name,
            'general_document' => $request->general_document,
            'is_lat' => $request->is_lat,
            'contract' => $contract,
        ]);

    }


    public function searchUL($id, Request $request)
    {

        $subject = new Subjects();
        $subject_name = $request->name;


        $contract = Contracts::getContractId($id);
        if($subject_name == 'insurer'){
            if($contract->insurer) $subject = $contract->insurer;
        }

        if($subject_name == 'owner'){
            if($contract->owner) {
                $subject = $contract->owner;
            }

            if(!$contract->owner || $contract->owner_id == $contract->insurer_id){
                $subject = Subjects::create(['type' => 1, 'phone' => '', 'citizenship_id' => '51', 'is_resident' => '1', 'user_id' => auth()->id()]);
                $contract->owner_id = $subject->id;
                $contract->save();
            }
        }

        if($subject_name == 'beneficiar'){
            /*
            if($contract->beneficiar) {
                $subject = $contract->beneficiar;
            }
            */

            $is_create = 1;
            if($contract->beneficiar_id == $contract->insurer_id){
                $is_create = 1;
            }

            if($contract->beneficiar_id == $contract->owner_id){
                $is_create = 1;
            }

            if($is_create == 1){
                $subject = Subjects::create(['type' => 1, 'phone' => '', 'citizenship_id' => '51', 'is_resident' => '1', 'user_id' => auth()->id()]);
                $contract->beneficiar_id = $subject->id;
                $contract->save();
            }
        }


        $subject->saveDataSubject($request);

        $subject->type = 1;
        $subject->title = $request->title;
        $subject->inn = $request->inn;
        $subject->ogrn = $request->ogrn;
        $data = $subject->data();

        $data->subject_id = $subject->id;
        $data->title = $request->title;
        $data->inn = $request->inn;
        $data->ogrn = $request->ogrn;




        if(strlen($request->title) > 3 && strlen($request->inn) > 3 && strlen($request->ogrn) > 3){

            /*
            $hash = GeneralSubjectsInfo::getHash(1, $request);
            $general = GeneralSubjectsSearch::search_hash(1, $hash);
            if(!$general){
                $general = GeneralSubjectsInfo::createGeneralSubjectHash(1, $hash, auth()->user(), $request);
                $general->user_id = auth()->id();
                $general->save();
            }

            if(GeneralSubjectsInfo::getGeneralSubjectInfoOnline($general) == true){
                if($general){
                    $general = GeneralSubjects::find($general->id);
                    if((int)$general->export_id > 0){
                        $subject->general_subject_id = $general->id;
                        $data->title = $general->title;
                        $data->inn = $general->data->inn;
                        $data->kpp = $general->data->kpp;
                        $data->ogrn = $general->data->ogrn;
                        $data->title_lat = $general->data->full_title_en;
                    }
                }

            }

            */




        }

        $data->save();
        $subject->save();


        return response('', 200);
    }


    public function clone_general($id, Request $request)
    {

        $subject_name = $request->name;
        $contract = Contracts::getContractId($id);
        $subject = Subjects::getSubjectContract($contract, $subject_name);

        return response()->json(GeneralSubjectsSearch::clone_general($request->general_id, $subject, $request->document));
    }

    public function clear_general($id, Request $request)
    {
        $subject_name = $request->name;
        $contract = Contracts::getContractId($id);
        $subject = Subjects::getSubjectContract($contract, $subject_name);

        return response()->json(GeneralSubjectsSearch::clear_general($subject, $request->title));
    }



    public function get_control_view($id, Request $request)
    {
        $result = [];
        $contract = Contracts::getContractId($id);
        if($class = $contract->product->get_control_view($contract, $request)){
            $result = $class->getArrayConfig($contract, $request);
        }

        return response()->json($result);
    }

    public function get_document_general($id, Request $request)
    {
        $result = new \stdClass();
        $result->state = false;

        $general = GeneralSubjects::getGeneralSubjectsId((int)$request->general_id);

        if($general){
            $doc = $general->getDocument((int)$request->document);
            if($doc && $doc->id > 0){
                $result->state = true;
                $result->data = $doc->toArray();
                $result->data['date_issue'] = getDateFormatRu($result->data['date_issue']);
                if((int)$request->document == 1145){
                    $result->data['driver_exp_date'] = getDateFormatRu($result->data['driver_exp_date']);
                }
            }
        }

        return response()->json($result);
    }


    public function getFormHtml($id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        $state = $contract->getViewStateOnline();




        return view("{$request->view}.{$state}", [
            'contract' => $contract,
        ]);

    }


    public function getHistory($id, Request $request)
    {
        $contract = Contracts::getContractId($id);
        return view("contracts.default.history.frame", [
            'contract' => $contract,
        ]);
    }

    public function getPrintList($id, Request $request)
    {
        $contract = Contracts::getContractId($id);

        if(ContractMasks::contract($contract, 0) != true){
            abort(303);
        }


        return view("contracts.default.print.list", [
            'contract' => $contract,
        ]);
    }


    public function getSendMatching($id, Request $request)
    {
        $contract = Contracts::getContractId($id);

        if(ContractMasks::contract($contract, 0) != true){
            abort(303);
        }

        if(ContractDocuments::checkDocuments($contract) == false){
            return abort(303, 'Заполните обязательные документы!');
        }

        return view("contracts.default.matching.send", [
            'contract' => $contract,
        ]);
    }

    public function setSendMatching($id, Request $request)
    {
        $contract = Contracts::getContractId($id);

        if($contract->product->slug == 'kasko'){
            MatchingKasko::createOrUdateMatchingOrder($contract, 'underwriter', $request->comments);
        }

        if($contract->product->slug == 'gap'){
            MatchingKasko::createOrUdateMatchingOrder($contract, 'underwriter', $request->comments);
        }

        if($contract->product->slug == 'arbitration'){
            MatchingArbitration::createOrUdateMatchingOrder($contract, 'underwriter', $request->comments);
        }

        if($contract->product->slug == 'mortgage'){
            MatchingKasko::createOrUdateMatchingOrder($contract, 'underwriter', $request->comments);
        }

        if($contract->product->slug == 'nsPrisoners'){
            MatchingKasko::createOrUdateMatchingOrder($contract, 'underwriter', $request->comments);
        }


        return parentReload();
    }








}
