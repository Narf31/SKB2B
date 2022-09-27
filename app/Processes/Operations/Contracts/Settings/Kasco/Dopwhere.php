<?php

namespace App\Processes\Operations\Contracts\Settings\Kasco;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\Directories\Products\Data\VZR;
use App\Models\Directories\Products\ProductsAgentsSpecialSsettings;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Settings\Country;
use App\Models\Settings\CurrencyValue;
use App\Models\Settings\InstallmentAlgorithmsPayment;
use App\Models\Vehicle\VehicleAntiTheftSystem;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehiclePurpose;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use Illuminate\Support\Str;

class Dopwhere{

    const CATEGORY = [
        'terms' => 'Условия страхования',
    ];


    public static function getDopwhereAllToCategoryDefault($category){

        $result = [];


        //Условия страхования
        if($category == 'terms'){
            $result = [
                'insurance_term' => [
                    'title' => 'Срок страхования',
                    'tarrif_name' => 'сд',
                    'field' => 'insurance_term',
                    'control' => [
                        'type' => 'select',
                        'value' => Standard::INS_YEAR,
                    ],
                ],
            ];
        }


        return $result;

    }

    public static function getDopwheresGroupDefault($category, $group){
        return self::getDopwhereAllToCategoryDefault($category)[$group];
    }


}