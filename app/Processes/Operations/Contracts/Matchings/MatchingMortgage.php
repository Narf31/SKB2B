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

class MatchingMortgage{



    public static function check($contract){

        $contract->statys_id = 3;
        $contract->save();
        return true;
    }


    public static function setTariff($contract, $tariff, $is_contract = 0){

        $calculation = $contract->calculation;
        $json = \GuzzleHttp\json_decode($calculation->json, true);

        if(!isset($json['original'])){
            $json['original'] = $json['info'];
        }

        $payment_total = 0;



        foreach ($json['info'] as $key => $info)
        {

            $info['tariff'] = getFloatFormat($tariff[$key]);
            $info['payment_total'] = getTotalSumToPrice($info['insurance_amount'], $info['tariff']);
            $payment_total += getFloatFormat($info['payment_total']);
            $json['info'][$key] = $info;
        }



        $calculation->json = \GuzzleHttp\json_encode($json);
        $calculation->save();
        $contract->payment_total = $payment_total;
        $contract->save();

        return (boolean)$calculation->createPaymentCalc($is_contract);
    }




}