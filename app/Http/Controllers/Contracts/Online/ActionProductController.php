<?php

namespace App\Http\Controllers\Contracts\Online;

use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LADocuments;
use App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager\LAProcedures;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehicleModelsClassificationKasko;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contracts\Contracts;
use App\Repositories\FilesRepository;

class ActionProductController extends Controller {

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository) {

        $this->filesRepository = $filesRepository;
    }


    public function getProcedures($id, $general_subject_id, Request $request) {

        $procedure = LAProcedures::where('general_subject_id', $general_subject_id);

        return response()->json($procedure->get());

    }



    public function procedures($id, $procedure_id, Request $request) {

        $contract = Contracts::getContractId($id);
        if($procedure_id > 0){
            $procedure = LAProcedures::find($procedure_id);
        }else{
            $procedure = new LAProcedures();
        }


        return view("contracts.default.insurance_object.liabilityArbitrationManager.procedures.edit", [
            'procedure' => $procedure,
            'procedure_id' => (int)$procedure_id,
            'contract' => $contract,
        ]);

    }


    public function saveProcedures($id, $procedure_id, Request $request) {

        $contract = Contracts::getContractId($id);
        if($procedure_id > 0){
            $procedure = LAProcedures::find($procedure_id);
            ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Изменена процедура', "{$procedure->title} - {$procedure->organization_title}");
        }else{
            $procedure = new LAProcedures();
            $procedure->contract_id = $contract->id;
            $procedure->general_subject_id = $contract->data->general_insurer_id;
            ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Создана процедура', "{$request->title} - {$request->organization_title}");
        }

        $procedure->title = $request->title;
        $procedure->organization_title = $request->organization_title;
        $procedure->inn = $request->inn;
        $procedure->ogrn = $request->ogrn;
        $procedure->address = $request->address;
        $procedure->latitude = $request->latitude;
        $procedure->longitude = $request->longitude;
        $procedure->content_html = $request->content_html;
        $procedure->save();

        return parentReloadTab();

    }

    public function deleteProcedures($id, $procedure_id, Request $request) {

        $contract = Contracts::getContractId($id);
        $procedure = LAProcedures::find($procedure_id);

        ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Удалена процедура', "{$procedure->title} - {$procedure->organization_title}");

        $procedure->delete();
        return response('',200);
    }


    public function saveDocuments($id, $key, Request $request)
    {

        $file = $request->file("file");

        $contract_doc = LADocuments::create([
            'contract_id' => $id,
            'document_id' => $key,
            'status_id' => 0,
            'file_id' => $this->filesRepository->makeFile($file, LADocuments::getPath($id))->id,
        ]);


        return response('', 200);
    }


    public function saveStatusDocuments($id, $doc_id, $status_id)
    {
        LADocuments::find($doc_id)->update(['status_id'=>(int)$status_id]);
        return response('',200);
    }





    public function getAutoMark($id, $category_id)
    {
        $category = VehicleCategories::findOrFail($category_id);

        $result = VehicleMarks::query()->where('category_id', $category->isn)//->whereNotNull('ref_key_1c')
            ->orderBy('title', 'asc')
            ->get(['id', 'title'])
            ->toArray();

        return response()->json($result);
    }

    public function getAutoModels($id, $category_id, $mark_id)
    {
        $category = VehicleCategories::findOrFail($category_id);

        $result = [];
        if ((int)$mark_id>0) {
            $result = VehicleModels::where('vehicle_models.category_id', $category->isn)//->whereNotNull('vehicle_models.ref_key_1c')
                ->where('vehicle_models.mark_id2', $mark_id)
                ->orderBy('vehicle_models.title', 'asc')
                ->get(['vehicle_models.id', 'vehicle_models.title'])
                ->toArray();
        }

        return response()->json($result);

    }

    public function getAutoModelsClassification($id, $category_id, $model)
    {


        $result = [];
        if ((int)$model > 0) {

        }

        return response()->json($result);

    }





}
