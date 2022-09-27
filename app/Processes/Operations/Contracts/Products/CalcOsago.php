<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Directories\Products\Data\VZR;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\CurrencyValue;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use App\Services\Integration\VernaControllers\Auxiliary\Car;
use App\Services\Integration\VernaControllers\Auxiliary\Subject;
use App\Services\Integration\VernaControllers\VernaSend;
use Illuminate\Support\Str;

class CalcOsago{



    public static function calc($contract){

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0]);
        }

        $contract->insurance_amount = 0;
        $contract->payment_total = 0;
        $calculation->state_calc = 0;
        $calculation->sum = '';
        $calculation->json = null;
        $calculation->messages = 'Нет подключения к РСА';

        $calculation->save();


        return true;
    }


    public static function setGeneralSubjects($contract)
    {
        //Привязываем контрагентов
        if($contract->insurer_id > 0) GeneralSubjectsInfo::getGeneralSubject($contract->insurer_id);
        if((int)$contract->owner_id > 0 && $contract->owner_id!=$contract->insurer_id) GeneralSubjectsInfo::getGeneralSubject($contract->owner_id);


        foreach ($contract->contracts_insurers as $insurer){
            GeneralSubjectsInfo::updateGeneralToInsurer($insurer, 1145);
        }

        return MainScorings::scoring($contract);
    }





}