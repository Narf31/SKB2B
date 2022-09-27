<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsDocuments;
use App\Models\File;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActionsScansController extends Controller {

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository) {

        $this->filesRepository = $filesRepository;
    }

    public function store($contract_id, Request $request) {

        Contracts::findOrFail($contract_id)->scans()->save($this->filesRepository->makeFile($request->file, ContractsDocuments::getPath($contract_id)));

        return response('', 200);
    }

    public function document($contract_id, Request $request) {

        $contract = Contracts::findOrFail($contract_id);
        $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id);

        if (isset($hold_kv_product) && sizeof($hold_kv_product->documents)) {
            $files = $request->file("file");

            foreach ($files as $key => $file) {

                ContractsDocuments::where("contract_id", $contract_id)->where("document_id", $key)->delete();

                $contract_doc = ContractsDocuments::create([
                            'contract_id' => $contract_id,
                            'document_id' => $key,
                            'file_id' => $this->filesRepository->makeFile($file, ContractsDocuments::getPath($contract_id))->id,
                ]);
            }

            //dd($files);
        }

        $contract->save();

        return redirect("/contracts/temp_contracts/contract/$contract_id/edit");
    }

    public function addDocument($contract_id, $document_id, Request $request) {
        $contract = Contracts::findOrFail($contract_id);
        $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id);

        if (isset($hold_kv_product) && sizeof($hold_kv_product->documents)) {
            $file = $request->file("file");

            ContractsDocuments::where("contract_id", $contract_id)->where("document_id", $document_id)->delete();

            $contract_doc = ContractsDocuments::create([
                        'contract_id' => $contract_id,
                        'document_id' => $document_id,
                        'file_id' => $this->filesRepository->makeFile($file, ContractsDocuments::getPath($contract_id))->id,
            ]);
        }
        ActionsScansController::checkExistenceOfRequiredDocuments($contract);
    }

    public function deleteDocument($contract_id, $document_id) {
        $contract = Contracts::findOrFail($contract_id);
        $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id);
        if (isset($hold_kv_product) && sizeof($hold_kv_product->documents)) {
            $doc = ContractsDocuments::where("contract_id", $contract_id)->where("document_id", $document_id)->first();
            $file = File::query()->where('id', $doc->file_id)->first();
            $doc->delete();
            app()->make('\App\Http\Controllers\FilesController')
                    ->callAction('destroy', [$file->name]);
        }
        ActionsScansController::checkExistenceOfRequiredDocuments($contract);
    }

    public static function checkExistenceOfRequiredDocuments($contract) {
        $hold_kv_product = $contract->bso_supplier->hold_kv_product($contract->product_id);
        $is_all_docs_exist = 1;
        if (isset($hold_kv_product) && sizeof($hold_kv_product->documents)) {
            $required_docs = $hold_kv_product->documents->where('is_required',1)->whereIn('program_id',[$contract->program_id ?:0,0]);
            $docs_ids = ContractsDocuments::where("contract_id", $contract->id)->get()->pluck("document_id")->toArray();

            foreach($required_docs as $required_doc){
                if(!in_array($required_doc->id,$docs_ids)) $is_all_docs_exist = 0;
            }
        }
        $contract->is_all_docs_exist = $is_all_docs_exist;
        $contract->save();
        return $contract;
    }

    public function download_all_scans($id){

        $contract = Contracts::findOrFail($id);
        $files = [];

        foreach($contract->scans as $scan){
            $files[] = url('files/'. $scan->name);
        }

        return $files;
    }


    public function download_zip($contract_id)
    {

        //dd(Storage::exists('contracts/docs/212/16-02-20-14-27-58.zip'));

        $res = new \stdClass();
        $res->state = false;
        $res->msg = 'Ошибка!';

        $urls = '';
        $contract = Contracts::getContractId($contract_id);
        if($contract)
        {

            $files = [];
            foreach($contract->scans as $scan){
                $path = storage_path() . '/app/' . ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], $scan->path_with_host), '/');
                $files[] = $path;
            }

            if(count($files) > 0){

                $pathLocal = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], ContractsDocuments::getPath($contract_id)), '/');
                $path = storage_path() . '/app/' . $pathLocal;

                if (!file_exists($path)) {
                    mkdir($path, 0777);
                }

                $name = date('d-m-y-H-i-s')."-".auth()->id().'.zip';

                $zip_name_local = $pathLocal.'/'.$name;
                $zip_name = $path.'/'.$name;

                $zip = Zip::create($zip_name);
                $zip->add($files, true);
                $zip->close();

                $res->state = true;
                $res->msg = $zip_name_local;

                return $this->openZip($zip_name_local, $name);

            }
        }

        return response()->json($res);
    }


    public function openZip($zip_name_local, $name)
    {

        if (Storage::exists($zip_name_local)){
            return response(Storage::get($zip_name_local), 200, [
                "Content-Type" => Storage::mimeType($zip_name_local),
                'Content-Disposition' => "inline; filename={$name}"
            ]);
        }
    }


}
