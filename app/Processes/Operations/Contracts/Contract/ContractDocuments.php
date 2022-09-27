<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\ContractsDocuments;
use App\Models\Contracts\Matching;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Settings\Notification;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use Illuminate\Support\Str;

class ContractDocuments{


    public static function checkDocuments($contract){

        if($contract->is_prolongation == 0){

            if(self::checkExistenceOfRequiredDocuments($contract) == 0){
                return false;
            }

        }else{
            $contract->is_all_docs_exist = 1;
            $contract->save();
        }

        return true;
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
        return $is_all_docs_exist;
    }



}