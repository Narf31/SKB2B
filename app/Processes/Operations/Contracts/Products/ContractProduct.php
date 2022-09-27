<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use Illuminate\Support\Str;

class ContractProduct{


    public static function createProductData($contract){
        if($contract->getClassPoductData()){
            $data = $contract->data()->create(['contract_id'=>$contract->id]);

            try {
                $data->setDefault();
            } catch (\Exception $e) { }
        }

        return $contract;
    }



}