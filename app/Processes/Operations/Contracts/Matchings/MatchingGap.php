<?php

namespace App\Processes\Operations\Contracts\Matchings;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\ContractsLogsPayments;
use App\Models\Contracts\Matching;
use App\Models\Directories\HoldKvMatching;
use App\Models\Directories\Products\Data\Kasko\BaseRateKasko;
use App\Models\Directories\Products\Data\Kasko\KaskoCoefficient;
use App\Models\Directories\Products\Data\Kasko\KaskoEquipment;
use App\Models\Directories\Products\Data\Kasko\KaskoProduct;
use App\Models\Directories\Products\Data\Kasko\KaskoService;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\Directories\Products\Data\VZR;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\CurrencyValue;
use App\Models\Settings\Notification;
use App\Models\User;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\Contracts\PSO\PSOCreate;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\Mails\NotificationMails;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use App\Services\Integration\VernaControllers\Auxiliary\Car;
use App\Services\Integration\VernaControllers\Auxiliary\Subject;
use App\Services\Integration\VernaControllers\VernaSend;
use Illuminate\Support\Str;

class MatchingGap{


    public static function check($contract){

        if($contract->matching_underwriter){
            if($contract->matching_underwriter->status_id == 4){
                $contract->statys_id = 3;
                $contract->save();
            }
        }

        return true;
    }

    public static function setTariff($contract, $tariff, $is_contract = 0){

        $calculation = $contract->calculation;
        $info = \GuzzleHttp\json_decode($calculation->json);



        $info->max_amount = getFloatFormat($tariff['max_amount']);
        $info->payment_tariff = getFloatFormat($tariff['payment_tariff']);
        $payment_total = getTotalSumToPrice($contract->insurance_amount, $info->payment_tariff);
        $info->payment_total = getFloatFormat($payment_total);

        $calculation->json = \GuzzleHttp\json_encode($info);
        $calculation->save();
        $contract->payment_total = getFloatFormat($payment_total);
        $contract->save();

        return (boolean)$calculation->createPaymentCalc($is_contract);
    }



}