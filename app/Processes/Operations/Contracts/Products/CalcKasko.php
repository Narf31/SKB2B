<?php

namespace App\Processes\Operations\Contracts\Products;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Directories\Products\Data\Kasko\BaseRateKasko;
use App\Models\Directories\Products\Data\Kasko\KaskoCoefficient;
use App\Models\Directories\Products\Data\Kasko\KaskoDopwhere;
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
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Scenaries\Contracts\Products\Kasko;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use App\Services\Integration\VernaControllers\Auxiliary\Car;
use App\Services\Integration\VernaControllers\Auxiliary\Subject;
use App\Services\Integration\VernaControllers\VernaPayment;
use App\Services\Integration\VernaControllers\VernaSend;
use Illuminate\Support\Str;

class CalcKasko{



    public static function calc($contract){


        if($contract->program->slug == 'calculator'){
            return self::calcCalculator($contract);
        }

        $calculation = $contract->calculation;
        if(!$calculation){
            $calculation = $contract->calculation()->create(['contract_id'=>$contract->id, 'state_calc'=>0, 'program_id'=>$contract->program->id]);
        }

        if($contract->program->slug == 'standard'){
            return self::calcStandard($contract, $calculation);
        }

        if($contract->program->slug == 'drive'){
            return self::calcDrive($contract, $calculation);
        }


        return false;
    }

    public static function calcCalculator($contract)
    {
        ContractsCalculation::where('contract_id', $contract->id)->delete();
        foreach ($contract->product->programs()->where('is_actual', 1)->where('slug', '!=', 'calculator')->get() as $program){
            $calculation = ContractsCalculation::create(['contract_id'=>$contract->id, 'is_actual'=>0, 'state_calc'=>0, 'program_id'=>$program->id]);

            if($program->slug == 'standard'){
                $calculation = self::calcStandard($contract, $calculation, 1);
            }

            if($program->slug == 'drive'){
                $calculation = self::calcDrive($contract, $calculation, 1);
            }


        }

        return true;
    }

    public static function calcStandard($contract, $calculation, $is_calc = 0){

        $state_calc = true;

        $error_tariff_name = 'Базовая ставка';

        $result_calc_data = new \stdClass();
        $result_calc_data->base = new \stdClass();
        $result_calc_data->base->payment_damage = 0;
        $result_calc_data->base->payment_total = 0;
        $result_calc_data->base->payment_theft = 0;
        $result_calc_data->base->title = '';



        $result_calc_data->official_discount = new \stdClass();
        $result_calc_data->official_discount->tariff = 0;
        $result_calc_data->official_discount->summ = 0;

        $result_calc_data->coefficient = new \stdClass();
        $result_calc_data->equipment = new \stdClass();
        $result_calc_data->service = new \stdClass();
        $result_calc_data->product = new \stdClass();
        $result_calc_data->product->summ = 0;

        $result_calc_data->total = new \stdClass();
        $result_calc_data->total->tariff = 0;
        $result_calc_data->total->summ = 0;
        $result_calc_data->total->title = '';

        $result_calc_data->info = [];


        $auto = $contract->object_insurer->data();

        $insurance_amount = $contract->insurance_amount;

        $base = self::getBaseRate($contract, $auto, $insurance_amount, $calculation);



        if($base){
            $result_calc_data->base = $base;
        }else{
            $state_calc = false;
        }

        if($state_calc == true){

            $info = $contract->data;

            if($state_calc == true){
                $equipment = self::getEquipment($contract, $calculation);
                if($equipment){
                    $result_calc_data->equipment = $equipment;
                }else{
                    $state_calc = false;
                    $error_tariff_name = 'Дополнительное оборудование';
                }
            }

            if($state_calc == true) {
                $service = self::getService($contract, $insurance_amount, $calculation);
                if ($service) {
                    $result_calc_data->service = $service;
                } else {
                    $state_calc = false;
                    $error_tariff_name = 'Дополнительные услуги';
                }

            }



            if($state_calc == true) {
                $coefficient = self::getCoefficient($contract, $calculation);
                if ($coefficient) {
                    $result_calc_data->coefficient = $coefficient;
                } else {
                    $state_calc = false;
                    $error_tariff_name = 'Коэффициент';
                }
            }




            //GO

            if($info->is_gap == 1){


                $prod = KaskoProduct::getProductSum($contract->product_id, $calculation->program_id, 1, $insurance_amount);
                if($prod){

                    $prod_sum = getTotalSumToPrice(getFloatFormat($insurance_amount), (getFloatFormat($prod->payment_tarife)));


                    $result_calc_data->info['gap'] = [
                        'title' => KaskoProduct::PRODUCT[$prod->kasko_product_id],
                        'tariff' => getFloatFormat($prod->payment_tarife),
                        'insurance_amount' => $insurance_amount,
                        'payment_total' => $prod_sum,
                    ];
                    $result_calc_data->total->summ += $prod_sum;
                    $result_calc_data->total->title .= "G:".titleFloatFormat($prod->payment_tarife).', ';
                    $result_calc_data->product->summ += $prod_sum;
                }else{
                    $state_calc = false;
                    $error_tariff_name = 'GAP';
                }
            }

            if((int)$info->civil_responsibility_sum > 0){
                $prod = KaskoProduct::getProductSum($contract->product_id, $calculation->program_id, 4, (int)$info->civil_responsibility_sum);
                if($prod){

                    $prod_sum = $prod->payment_tarife;//getTotalSumToPrice(getFloatFormat($insurance_amount), (getFloatFormat($prod->payment_tarife)));


                    $result_calc_data->info['go'] = [
                        'title' => KaskoProduct::PRODUCT[$prod->kasko_product_id],
                        'tariff' => getFloatFormat(titleFloatFormat(getPriceToTotalSum((int)$info->civil_responsibility_sum, $prod_sum))),//getFloatFormat($prod->payment_tarife),
                        'insurance_amount' => (int)$info->civil_responsibility_sum,
                        'payment_total' => $prod_sum,
                    ];
                    $result_calc_data->total->summ += $prod_sum;
                    $result_calc_data->total->title .= "GO:".titleFloatFormat($prod->payment_tarife).', ';
                    $result_calc_data->product->summ += $prod_sum;
                }else{
                    $state_calc = false;
                    $error_tariff_name = 'GO';
                }
            }


            if((int)$info->ns_type > 0 && (int)$info->ns_sum > 0){
                $_kP = 2;
                if((int)$info->ns_type == 1) $_kP = 2;
                if((int)$info->ns_type == 2) $_kP = 3;

                $prod = KaskoProduct::getProductSum($contract->product_id, $calculation->program_id, $_kP, getFloatFormat($info->ns_sum));
                if($prod){

                    $prod_sum = getTotalSumToPrice(getFloatFormat($insurance_amount), (getFloatFormat($prod->payment_tarife)));

                    $result_calc_data->info['ns'] = [
                        'title' => KaskoProduct::PRODUCT[$prod->kasko_product_id],
                        'tariff' => getFloatFormat($prod->payment_tarife),
                        'insurance_amount' => $insurance_amount,
                        'payment_total' => $prod_sum,
                    ];
                    $result_calc_data->total->summ += $prod_sum;
                    $result_calc_data->total->title .= "NS:".titleFloatFormat($prod->payment_tarife).', ';
                    $result_calc_data->product->summ += $prod_sum;
                }else{
                    $state_calc = false;
                    $error_tariff_name = 'NS';
                }
            }





            $NS_KOR = 600;
            $NS_IM = 900;

            $_minTariff = 0.8;
            if($insurance_amount >= 2500000){
                $_minTariff = 2.5;
            }


            //Минимальный тариф в рублях : БТС ущерб * 0.8 + НС коробка + НС имущество + СП Gap + СП ГО (60141 * 0.8 + 600 + 900 + 0 + 0)
            $_minTariffSum = ($result_calc_data->base->payment_damage * $_minTariff) + getFloatFormat($NS_KOR) + getFloatFormat($NS_IM) + getFloatFormat($result_calc_data->total->summ);


            //СП ущерб
            $_damageTariffSum = ($result_calc_data->base->payment_damage * $result_calc_data->coefficient->tariff);
            //СП тоталь
            $_totalTariffSum = ($result_calc_data->base->payment_total * $result_calc_data->coefficient->tariff);
            //СП хищение
            $_theftTariffSum = ($result_calc_data->base->payment_theft * $result_calc_data->coefficient->tariff);



            if($info->coatings_risks_id == 1) {
                $_totalTariffSum = $_damageTariffSum + $_theftTariffSum + $_totalTariffSum;
                if($_minTariffSum > $_totalTariffSum){
                    $_totalTariffSum = $_minTariffSum;
                }
            }

            if($info->coatings_risks_id == 2) {
                $_totalTariffSum = $_theftTariffSum + $_totalTariffSum;
            }

            if($info->coatings_risks_id == 3) {
                $_totalTariffSum = $_damageTariffSum;
                if($_minTariffSum > $_totalTariffSum){
                    $_totalTariffSum = $_minTariffSum;
                }
            }


            $tarrif_sum = $_totalTariffSum;


            /*

                Расчет тарифов КАСКО

                Страховая сумма = 1 000 000
                БТС ущерб тс = 60141
                БТС тоталь тс  = 0.0054
                БТС хищение тс = 0.0003
                Итого 64 723,00

                СП ущерб тс =БТС ущерб тс *К1 *К2 *К4 *К6*К8 *К9 *К10 *К11 *К12 *К13 *К15*К18 +Д5;
                СП тоталь тс = Страховая сумма * БТС тоталь тс *К1*К6*К8 *К9 *К10*К12 *К13 *К15*К16+ Д5;
                СП хищение тс = Страховая сумма * БТС хищение тс * К3 *К10 *К11*К15*К16 ;

                СП ущерб тс  56691.3957
                СП ущерб тс = БТС ущерб тс *К1 *К2 *К4 *К6*К7*К8 *К9 *К10 *К11 *К12 *К13 *К15*К18+ Д5
                СП ущерб тс = 60141 * 0.92 * 1 * 1 * 1 * 1 * 1.05 * 1 * 1 * 0.95 * 1 * 1 * 1 * 1 + 1500

                СП тоталь тс 4968
                 СП тоталь тс = СС тоталь тс * БТС тоталь тс *К1*К6*К9*К10*К13*К15
                СП тоталь тс = 1000000 * 0.0054 * 0.92 * 1 * 1 * 1 * 1 * 1

                СП хищение тс 285
                СП хищение тс = СС хищение тс * БТС хищение тс * К3 * K10 * К11 *К15*К16
                СП хищение тс = 1000000 * 0.0003 * 1 * 1 * 0.95 * 1 * 1

                NS НС Коробка 600
                GN Имущество Коробка 900

                Минимальный тариф в рублях 49612.8
                БТС ущерб * 0.8 + НС коробка + НС имущество + СП Gap + СП ГО 60141 * 0.8 + 600 + 900 + 0 + 0

                Предварительный результат 63208.56704081633
                Расчетный тариф = (СП ущерб тс +СП тоталь тс+ СП хищение тс + СП ущерб до + СП хищение до + СП го + СП нс + СП gap +СП км ) / (1,0 – Д сст )

                Коэффициент - Это условия в процентном соотношении.
                Дополнительные фиксированные услуги.
                Дополнительное оборудование

                Итоговый тариф % = Расчетный тариф / (Страховая сумма * 100) = Округляем до 2 знаков после запятой в большую сторону
                Страховая премия = (Страховая сумма / 100) * Итоговый тариф
             */













            $total_tarif = getPriceToTotalSum($insurance_amount, $tarrif_sum);
            $total_tarif = round(ceil($total_tarif*100)/100,2);
            $tarrif_sum = getTotalSumToPrice($insurance_amount, $total_tarif);

            $payment_total = $tarrif_sum + ($result_calc_data->product->summ + $result_calc_data->service->summ + $result_calc_data->equipment->summ);


            //$payment_total = getTotalSumToPrice($insurance_amount, $total_tarif);

            $result_calc_data->total->tariff = $total_tarif;
            $result_calc_data->total->summ = $tarrif_sum;
            $result_calc_data->total->title = $result_calc_data->base->title.', ';

            if($result_calc_data->coefficient->tariff > 0){
                $result_calc_data->total->title .= $result_calc_data->coefficient->title.', ';
            }

            if($result_calc_data->equipment->summ > 0){
                $result_calc_data->total->title .= $result_calc_data->equipment->title.', ';
            }

            if($result_calc_data->service->summ > 0){
                $result_calc_data->total->title .= $result_calc_data->service->title.', ';
            }



            $result_calc_data->info['kasko'] = [
                'title' => $calculation->program->title,
                'tariff' => $total_tarif,
                'insurance_amount' => $insurance_amount,
                'payment_total' => $result_calc_data->total->summ,
            ];



            if($state_calc == true){
                if($contract->data->official_discount > 0){
                    $result_calc_data->official_discount->tariff = getFloatFormat($contract->data->official_discount);
                    $result_calc_data->official_discount->summ = getTotalSumToPrice($payment_total, $contract->data->official_discount);
                }
            }


            $result_calc_data->total->title = substr($result_calc_data->total->title, 0, -2);

        }



        if($state_calc == false){
            $contract->payment_total = 0;
            $calculation->insurance_amount = 0;
            $calculation->state_calc = 0;
            $calculation->sum = 0;
            $calculation->json = null;
            $calculation->messages = 'Ошибка тарификатора '.$error_tariff_name;
            $calculation->save();
        }else{


            $dopWhere = KaskoDopwhere::where('product_id', $contract->product_id)
                ->where('program_id', $calculation->program_id)
                ->where('category', 'terms')
                ->where('group', 'insurance_term')
                ->where('value', $contract->data->insurance_term)
                ->get()->first();

            if($dopWhere){
                $_d_tarife = getFloatFormat($dopWhere->tarife);
                if($_d_tarife > 0){
                    $payment_total = getTotalSumToPrice($payment_total, $_d_tarife);
                }

            }

            $contract->payment_total = $payment_total;
            $calculation->insurance_amount = $insurance_amount;
            $calculation->sum = $payment_total;
            $calculation->state_calc = 1;
            $calculation->json = \GuzzleHttp\json_encode($result_calc_data);
            $calculation->messages = '';
            $calculation->save();

        }

        $contract->save();

        if($is_calc == 1){
            return $calculation;
        }

        if($contract->payment_total > 0){
            $calculation->createPaymentCalc();
            return self::setGeneralSubjects($contract);
        }

        return $state_calc;

    }

    public static function calcDrive($contract, $calculation, $is_calc = 0){


        $state_calc = true;

        $error_tariff_name = 'Базовая ставка';

        $result_calc_data = new \stdClass();
        $result_calc_data->base = new \stdClass();
        $result_calc_data->base->tariff = 0;
        $result_calc_data->base->summ = 0;
        $result_calc_data->base->title = '';

        $result_calc_data->official_discount = new \stdClass();
        $result_calc_data->official_discount->tariff = 0;
        $result_calc_data->official_discount->summ = 0;


        $result_calc_data->total = new \stdClass();
        $result_calc_data->total->tariff = 0;
        $result_calc_data->total->summ = 0;
        $result_calc_data->total->title = '';

        $result_calc_data->info = [];

        $payment_total = 0;


        $auto = $contract->object_insurer->data();

        $insurance_amount = $contract->insurance_amount;//getFloatFormat($auto->car_price);


        $spec = ProductsSpecialSsettings::where('product_id', $contract->product->id)->where('program_id', $calculation->program_id)->get()->first();
        $spec_info = null;
        if($spec && $spec->json && strlen($spec->json) > 0){
            $spec_info = json_decode($spec->json);


            if(isset($spec_info->terms)){
                if($insurance_amount >= getFloatFormat($spec_info->terms->insurance_amount)){
                    $insurance_amount = getFloatFormat($spec_info->terms->insurance_amount);
                    if($contract->program->slug != 'calculator'){
                        $contract->insurance_amount = $insurance_amount;
                        $contract->save();
                    }
                }
            }else{
                $state_calc = false;
            }

        }

        $base = self::getBaseRate($contract, $auto, $insurance_amount, $calculation);

        if($base){
            $result_calc_data->base = $base;
        }else{
            $state_calc = false;
        }


        if($state_calc == true){


            if($state_calc == true) {
                $coefficient = self::getCoefficient($contract, $calculation);
                if ($coefficient) {
                    $result_calc_data->coefficient = $coefficient;
                } else {
                    $state_calc = false;
                    $error_tariff_name = 'Коэффициент';
                }
            }

            $total_tarif = null;

            if($state_calc == true) {



                $tarrif_sum = getFloatFormat($result_calc_data->base->summ)*getFloatFormat($result_calc_data->coefficient->tariff);
                $total_tarif = getPriceToTotalSum($insurance_amount, $tarrif_sum);
                $total_tarif = round(ceil($total_tarif*100)/100,2);

                $payment_total = getTotalSumToPrice($insurance_amount, $total_tarif);

                $result_calc_data->total->tariff = $total_tarif;
                $result_calc_data->total->summ = $payment_total;

                $result_calc_data->total->title = $result_calc_data->base->title;

                if($result_calc_data->coefficient->tariff > 0){
                    $result_calc_data->total->title .= $result_calc_data->coefficient->title.', ';
                }


                /*
                    Расчетный тариф = (Базовая ставка * коэффициент)

                    Коэффициент - Это условия в процентном соотношении.

                    Итоговый тариф % = Расчетный тариф / (Страховая сумма * 100) = Округляем до 2 знаков после запятой в большую сторону
                    Страховая премия = (Страховая сумма / 100) * Итоговый тариф
                 */



            }






            $result_calc_data->total->title = substr($result_calc_data->total->title, 0, -2);

        }else{

            if($spec_info){

                $damage_tariff = null;
                if(getFloatFormat($contract->insurance_amount) <= getFloatFormat($spec_info->damage->insurance_amount)){
                    $damage_tariff = getFloatFormat($spec_info->damage->tariff);
                }

                $hijackinge_tariff = null;
                if(getFloatFormat($contract->insurance_amount) <= getFloatFormat($spec_info->hijackinge->insurance_amount)){
                    $hijackinge_tariff = getFloatFormat($spec_info->hijackinge->tariff);
                }

                $tariff = null;

                if((int)$spec_info->terms->coatings_risks_id == 1){
                    $tariff = $damage_tariff*$hijackinge_tariff;
                }

                if((int)$spec_info->terms->coatings_risks_id == 2){
                    $tariff = $hijackinge_tariff;
                }

                if((int)$spec_info->terms->coatings_risks_id == 3){
                    $tariff = $damage_tariff;
                }

                /*
                 Тущерб:37 341,00, Тугон:2 200,00, Ттотал:7 300,00
                 */

                if($tariff){
                    $total_tarif = $tariff;
                    $payment_total = getTotalSumToPrice($insurance_amount, $tariff);
                    $result_calc_data->total->tariff = $tariff;
                    $result_calc_data->total->summ = $payment_total;
                    $result_calc_data->total->title = "";
                    $state_calc = true;
                }

            }


        }


        $result_calc_data->info['kasko'] = [
            'title' => $calculation->program->title,
            'tariff' => $total_tarif,
            'insurance_amount' => $insurance_amount,
            'payment_total' => $payment_total,
        ];


        if($state_calc == true){
            if($contract->data->official_discount > 0){
                $result_calc_data->official_discount->tariff = getFloatFormat($contract->data->official_discount);
                $result_calc_data->official_discount->summ = getTotalSumToPrice($result_calc_data->total->summ, $contract->data->official_discount);
            }
        }


        if($state_calc == false){
            $contract->payment_total = 0;
            $calculation->insurance_amount = 0;
            $calculation->state_calc = 0;
            $calculation->sum = 0;
            $calculation->json = null;
            $calculation->messages = 'Ошибка тарификатора '.$error_tariff_name;
            $calculation->save();
        }else{
            $contract->payment_total = $result_calc_data->total->summ;

            $calculation->insurance_amount = $insurance_amount;
            $calculation->sum = $result_calc_data->total->summ;
            $calculation->state_calc = 1;
            $calculation->json = \GuzzleHttp\json_encode($result_calc_data);
            $calculation->messages = '';
            $calculation->save();

        }

        if($is_calc == 1){
            return $calculation;
        }

        $contract->save();
        if($contract->payment_total > 0){
            if(self::setGeneralSubjects($contract) == true){
                return (boolean)$calculation->createPaymentCalc();
            }
        }

        return true;

    }



    public static function getEquipment($contract, $calculation){

        $equipment = new \stdClass();
        $equipment->tariff = 0;
        $equipment->summ = 0;
        $equipment->title = '';

        if($contract->object_equipment){
            $equipment_sum = $contract->object_equipment()->sum('payment_total');
            if($equipment_sum > 0){
                $equipment_procent = KaskoEquipment::getEquipmentSum($contract->product_id, $calculation->program_id, $equipment_sum);//Дополнительное оборудование
                if($equipment_procent){
                    $equipment->summ = (getTotalSumToPrice($equipment_sum, $equipment_procent->payment_tarife));
                    $equipment->tariff = getPriceToTotalSum($equipment_sum, $equipment->summ);
                    $equipment->title = 'ДО:'.titleFloatFormat($equipment->tariff);
                }else{
                    return null;
                }
            }
        }
        return $equipment;
    }

    public static function getService($contract, $insurance_amount, $calculation){
        $service = new \stdClass();
        $service->tariff = 0;
        $service->summ = 0;
        $service->title = '';

        $serv = [
            ['name'=> 'is_emergency_commissioner', 'title' => 'Уак'],//'Аварийный Комиссар',
            ['name'=> 'is_collection_certificates', 'title' => 'Уcc'],//'Сбор справок в случае необходимости',
            ['name'=> 'is_evacuation', 'title' => 'Уаэ'],//'Эвакуация ТС при ДТП',
        ];



        $info = $contract->data->toArray();

        foreach ($serv as $_serv){
            if(isset($info[$_serv['name']]) && (int)$info[$_serv['name']] == 1){
                $service_sum = KaskoService::getServiceSum($contract->product_id, $calculation->program_id, $_serv['name']);
                if($service_sum){
                    $service->summ += getFloatFormat($service_sum->payment_total);
                    $service->tariff += getPriceToTotalSum($insurance_amount, $service_sum->payment_total);
                    $service->title .= $_serv['title'].':'.titleFloatFormat(getPriceToTotalSum($insurance_amount, $service_sum->payment_total)).',';

                }

            }

        }


        if($service->summ > 0){
            $service->title = substr($service->title, 0, -1);
        }

        return $service;
    }


    public static function getCoefficient($contract, $calculation)
    {
        $coefficient = new \stdClass();
        $coefficient->tariff = 1;
        $coefficient->title = '';

        $coefficients_group = KaskoCoefficient::where('product_id', $contract->product_id)
            ->where('program_id', $calculation->program_id)
            ->orderBy('category', 'asc')
            ->orderBy('group', 'asc')
            ->orderBy('tarife', 'asc')
            ->get();

        $coefficients = Coefficients::getCoefficientsValue(Coefficients::getDataContract($contract), $coefficients_group);


        foreach ($coefficients as $coeff){


            $title = "{$coeff['title']}:".titleFloatFormat($coeff['tariff']).", ";
            $_temp = getFloatFormat($coeff['tariff']);

            $coefficient->tariff = $coefficient->tariff * $_temp;
            $coefficient->title .= $title;

        }

        if($coefficient->tariff == 0){

        }else{
            $coefficient->title = substr($coefficient->title, 0, -2);
        }


        return $coefficient;
    }



    public static function setGeneralSubjects($contract)
    {
        //Привязываем контрагентов
        if($contract->insurer_id > 0) {
            GeneralSubjectsInfo::getGeneralSubject($contract->insurer_id);
        }
        if((int)$contract->owner_id > 0) {
            if($contract->owner->general_subject_id == 0){
                GeneralSubjectsInfo::getGeneralSubject($contract->owner_id);
            }

        }
        if((int)$contract->beneficiar_id > 0 && $contract->beneficiar_id!=$contract->insurer_id && $contract->beneficiar_id!=$contract->owner_id){
            if($contract->beneficiar_id!=$contract->owner_id){

                if($contract->beneficiar->general_subject_id == 0){
                    GeneralSubjectsInfo::getGeneralSubject($contract->beneficiar_id);
                }

            }
        }



        foreach ($contract->contracts_insurers as $insurer){
            GeneralSubjectsInfo::updateGeneralToInsurer($insurer, 1145);
        }


        return MainScorings::scoring($contract);
    }


    public static function getBaseRate($contract, $auto, $insurance_amount, $calculation){

        if($insurance_amount <= 0){
            return null;
        }

        $base = new \stdClass();
        $base->payment_damage = 0;
        $base->payment_total = 0;
        $base->payment_theft = 0;
        $base->title = '';

        //total and theft / 100
        //$insurance_amount * total
        //$insurance_amount * theft

        //Поиск по марки и модели и год
        $base_teriff = BaseRateKasko::getBaseRate(
            $contract->product_id,
            $calculation->program_id,
            $auto->mark_id,
            $auto->model_id,
            (date('Y')-(int)$auto->car_year));

        //Поиск по марки и модели
        if(!$base_teriff){
            $base_teriff = BaseRateKasko::getBaseRate(
                $contract->product_id,
                $calculation->program_id,
                $auto->mark_id,
                $auto->model_id,
                null);
        }

        //Поиск по марки
        if(!$base_teriff){
            $base_teriff = BaseRateKasko::getBaseRate(
                $contract->product_id,
                $calculation->program_id,
                $auto->mark_id,
                null,
                null);
        }


        if(!$base_teriff){
            return null;
        }


        $base->payment_damage = getFloatFormat($base_teriff->payment_damage);
        $base->payment_total = $insurance_amount * (getFloatFormat($base_teriff->total)/100);
        $base->payment_theft = $insurance_amount * (getFloatFormat($base_teriff->theft)/100);

        if($base->payment_damage > 0 && $base->payment_total > 0 && $base->payment_theft > 0){
            $info = $contract->data;
            if($info->coatings_risks_id == 1) {
                $base->title = "Тущерб:".titleFloatFormat($base->payment_damage).", Тугон:".titleFloatFormat($base->payment_theft).", Ттотал:".titleFloatFormat($base->payment_total);
            }

            if($info->coatings_risks_id == 2) {
                $base->title = "Тугон:".titleFloatFormat($base->payment_theft).", Ттотал:".titleFloatFormat($base->payment_total);
            }

            if($info->coatings_risks_id == 3) {
                $base->title = "Тущерб:".titleFloatFormat($base->payment_damage);
            }
        }else{
            return null;
        }


        return $base;

    }

}