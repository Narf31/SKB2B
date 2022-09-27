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

class MatchingKasko{



    public static function check($contract){


        if($contract->scoring_state == 1){
            if(self::createOrUdateMatchingOrder($contract, 'underwriter') == true){
                return false;
            }
        }


        $spec = ProductsSpecialSsettings::where('product_id', $contract->product_id)->where('program_id', $contract->program_id)->get()->first();
        if($spec && $spec->json && strlen($spec->json) > 0) {
            $def_data = \GuzzleHttp\json_decode($spec->json);

            $matching_setting = $def_data->matching;


            $matching_num = (int)$contract->matching_num;
            if($matching_num == 0) $matching_num = 1;

            $data = self::getDataContract($contract);

            $state = true;

            if($matching_num == (int)$matching_setting->underwriter_num && (int)$matching_setting->underwriter == 1){
                //Если нужна проверяем условия и создаем
                if(self::checkAndCreateOrder($contract, $data, 'underwriter') == true){
                    $state = false;
                }
            }

            if($matching_num == (int)$matching_setting->sb_num && (int)$matching_setting->sb == 1){
                //Если нужна проверяем условия и создаем
                if(self::checkAndCreateOrder($contract, $data, 'sb') == true){
                    $state = false;
                }
            }

            if($matching_num == (int)$matching_setting->inspection_num && (int)$matching_setting->inspection == 1){
                //Если нужна проверяем условия и создаем
                if(self::checkAndCreateOrder($contract, $data, 'inspection') == true){
                    $state = false;
                }
            }

            if($state == true && $matching_num < 5){
                $contract->matching_num = $matching_num+1;
                $contract->save();
                return self::check($contract);
            }

            if($state == true && $matching_num == 5){
                $contract->statys_id = 3;
                $contract->save();
                Notification::setNotificationContract($contract->agent_id, $contract, "Договор согласован!");
            }

            return $state;

        }


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

        if(isset($tariff['is_set_pso']) && (int)$tariff['is_set_pso'] == 1){
            $json['is_set_pso'] = true;
        }else{
            $json['is_set_pso'] = false;
        }

        if(isset($tariff['text_anti_theft_system'])) $json['text_anti_theft_system'] = $tariff['text_anti_theft_system'];


        $calculation->json = \GuzzleHttp\json_encode($json);
        $calculation->save();
        $contract->payment_total = $payment_total;
        $contract->save();

        return (boolean)$calculation->createPaymentCalc($is_contract);
    }



    public static function checkAndCreateOrder($contract, $data, $type){
        $matching_terms = MatchingsContract::getMatchingsTerms($contract, $type);

        if(self::comparisonsTerms($matching_terms, $data) == true){
            //Создаем заявку

            //Осмотр
            if($type == 'inspection'){

                return self::createOrUdatePSOOrder($contract);
            }else{
                return self::createOrUdateMatchingOrder($contract, $type);
            }
        }

        return false;

    }

    public static function checkDataOrder($contract, $type){
        $data = self::getDataContract($contract);
        $matching_terms = MatchingsContract::getMatchingsTerms($contract, $type);
        return self::comparisonsTerms($matching_terms, $data);
    }

    public static function createOrUdatePSOOrder($contract){

        $user = auth()->user();

        $inspection = $contract->matching_inspection;
        if(!$inspection){

            $inspection = PSOCreate::create($contract, $user);
            $contract->matching_inspection_id = $inspection->id;

            if($contract->statys_id != 4){
                $contract->statys_id = 2;
            }

            $contract->save();
            ContractsLogs::setContractLogs($contract->id, $user->id, $contract->statys_id, "Отправлен на предстраховой осмотр");
            return true;

        }else{



            return true;
        }




        return false;
    }

    public static function createOrUdateMatchingOrder($contract, $type, $comments = '')
    {
        $title = '';
        $type_id = 0;
        $matching = null;

        //Андеррайтер
        if($type == 'underwriter'){
            $title = 'в андеррайтинг';
            $type_id = 0;
            if($contract->matching_underwriter){
                $matching = $contract->matching_underwriter;
            }
        }

        //СБ
        if($type == 'sb'){
            $title = 'в службу безопасности';
            $type_id = 1;
            if($contract->matching_sb){
                $matching = $contract->matching_sb;
            }
        }

        $user = auth()->user();

        if(!$matching){
            //Создаем заявку

            $insurer_title = '';
            if($contract->insurer){
                $insurer_title = $contract->insurer->title;
            }


            $matching = Matching::create([
                'type_id' => $type_id,
                'status_id' => 0,
                'category_id' => 0,
                'category_title' => "{$contract->product->title}",
                'contract_id' => $contract->id,
                'product_id' => $contract->product_id,
                'insurer_title' => $insurer_title,
                'initiator_user_id' => $user->id,
                'initiator_organization_id' => $user->organization_id,
                'initiator_parent_id' => $user->parent_id,
                'initiator_curator_id' => $user->curator_id,
                'agent_comments' => $comments,
            ]);

            //Андеррайтер
            if($type == 'underwriter'){
                $contract->matching_underwriter_id = $matching->id;
            }

            //СБ
            if($type == 'sb'){
                $contract->matching_sb_id = $matching->id;
            }

            $contract->statys_id = 2;
            $contract->save();

            $users = null;
            $mail_url = '';
            //Андеррайтер
            if($type == 'underwriter'){
                $mail_url = '/matching/underwriting/';
                $users = User::getUserIsRole('is_underwriter')->where('is_notification',1)->get();
            }

            //СБ
            if($type == 'sb'){
                $mail_url = '/matching/security-service/';
                $users = User::getUserIsRole('is_sb')->where('is_notification',1)->get();
            }

            if($users){
                $mail_template = 'emails.notification.notification';
                $mail_title = 'Согласование договора';
                $mail_body = 'Уведомляем Вас о появлении договора, требующего согласования. Детали смотрите по ссылке <a href="'.url($mail_url).'" target="_blank">'.url($mail_url).'</a>';

                foreach($users as $_user){
                    if($_user->is_notification) {
                        NotificationMails::create($_user->email, $mail_template, $mail_title, $mail_body, $mail_url);
                    }
                }
            }


            ContractsLogs::setContractLogs($contract->id, $matching->initiator_user_id, $contract->statys_id, "Отправлен на согласования {$title}");
            ContractsLogsPayments::setContractLogsPayments($contract->id, $matching->initiator_user_id, getFloatFormat($contract->payment_total), "Отправлен на согласования {$title}");


            return true;

        }else{
            $matching->agent_comments = $comments;

            //Проверяем статус
            if($matching->status_id == 2){
                $matching->status_id = 3;
                $matching->save();

                if($matching->check_user_id > 0){
                    Notification::setNotificationContractUnder($matching->check_user_id, $matching, $matching->insurer_title.' Cогласования - '.Matching::STATYS[$matching->status_id].' '.auth()->user()->name);

                    $mail_url = '';
                    //Андеррайтер
                    if($type == 'underwriter'){
                        $mail_url = '/matching/underwriting/'.$matching->id.'/';
                    }

                    //СБ
                    if($type == 'sb'){
                        $mail_url = '/matching/security-service/'.$matching->id.'/';
                    }

                    $_user = User::find($matching->check_user_id);
                    $mail_template = 'emails.notification.notification';
                    $mail_title = 'Согласование договора после доработки';
                    $mail_body = 'Уведомляем Вас о том, что договор был возвращен после доработки. Детали смотрите по ссылке <a href="'.url($mail_url).'" target="_blank">'.url($mail_url).'</a>';


                    if($_user->is_notification) {
                        NotificationMails::create($_user->email, $mail_template, $mail_title, $mail_body, $mail_url);
                    }

                }

                $contract->statys_id = 2;
                $contract->save();

                ContractsLogs::setContractLogs($contract->id, $matching->initiator_user_id, $contract->statys_id, "Отправлен на согласования {$title}");
                ContractsLogsPayments::setContractLogsPayments($contract->id, $matching->initiator_user_id, getFloatFormat($contract->payment_total), "Отправлен на согласования {$title}");

                return true;
            }

        }



        return false;
    }



    private static function comparisonsTerms($terms, $data)
    {

        foreach ($terms as $term){

            unset($term['insurer']['address_register']);
            unset($term['owner']['address_register']);
            $term['contract']['insurance_amount'] = getFloatFormat($term['contract']['insurance_amount']);
            if(isset($term['contract']['is_prolongation'])) {
                $term['contract']['is_prolongation'] = (int)$term['contract']['is_prolongation'];
            }



            if(self::checkTerms($term, $data) == true){
                return true;
            }
        }

        return false;
    }

    private static function checkTerms($term, $data)
    {
        $stat = new \stdClass();
        $stat->c_select = 0;
        $stat->c_comparison = 0;

        foreach ($term as $key => $group){
            foreach ($group as $key_p => $param){
                $stat = self::checkTermsArr($term, $data, $key, $key_p, $stat);
            }
        }


        if($stat->c_select > 0 && $stat->c_select == $stat->c_comparison){
            return true;
        }

        return false;
    }

    private static function checkTermsArr($term, $data, $group, $param, $stat){



        if((int)$term[$group][$param] > 0){
            $stat->c_select++;
            if($param == 'insurance_amount' || $param == 'car_year'){
                if((int)$term[$group][$param] <= (int)$data[$group][$param]){
                    $stat->c_comparison++;
                }
            }else{
                if((int)$term[$group][$param] == (int)$data[$group][$param]){
                    $stat->c_comparison++;
                }
            }

        }elseif($param == 'is_prolongation'){
            $stat->c_select++;
            if((int)$term[$group][$param] == (int)$data[$group][$param]){
                $stat->c_comparison++;
            }
        }
        return $stat;
    }


    public static function getDataContract($contract)
    {

        $auto = $contract->object_insurer->data()->toArray();
        $auto['car_year'] = date('Y')-(int)$auto['car_year'];

        $data = [
            'contract' => ['insurance_amount' => getFloatFormat($contract->insurance_amount), 'is_prolongation' => (int)$contract->is_prolongation],
            'insurer' => self::getDataContractSubjects($contract->insurer),
            'owner' => self::getDataContractSubjects($contract->owner),
            'object' => $auto,

        ];

        return $data;
    }


    public static function getDataContractSubjects($subject){

        $birthyear = null;
        $sex = null;
        $address_register_kladr = null;
        if($subject->type == 0){

            $data = $subject->data();
            $birthyear = date('Y')-date('Y', strtotime($data->birthdate));
            $sex = $data->sex;
            $address_register_kladr = $data->address_register_kladr;
        }


        $subject = [
            'type' => $subject->type,
            'birthyear' => $birthyear,
            'sex' => $sex,
            'resident' => $subject->is_resident,
            'citizenship' => $subject->citizenship_id,
            'address_register_kladr' => $address_register_kladr
        ];

        return $subject;

    }


}