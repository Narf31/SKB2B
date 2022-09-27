<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Classes\Export\Replacers\ExcelReplacer;
use App\Classes\Export\Replacers\WordReplacer;
use App\Models\Contracts\ContractsDocuments;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\File;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\ContractOnlineProduct;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use Illuminate\Support\Str;

class ContractSubjects{


    //Доступ в ЛК или привязка генерального страхователя
    public static function access($contract)
    {

        if($contract->insurer && $contract->insurer->type == 0) {
            GeneralSubjectsInfo::getGeneralSubject($contract->insurer_id);
        }

        if($contract->owner && $contract->owner->type == 0) {
            GeneralSubjectsInfo::getGeneralSubject($contract->owner_id);
        }

        if($contract->beneficiar && $contract->beneficiar->type == 0) {
            GeneralSubjectsInfo::getGeneralSubject($contract->beneficiar_id);
        }


        return true;
    }

}