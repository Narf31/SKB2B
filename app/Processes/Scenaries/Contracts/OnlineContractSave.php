<?php

namespace App\Processes\Scenaries\Contracts;

use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsLogs;
use App\Models\User;
use App\Processes\Operations\Contracts\ContractOnlineProduct;
use App\Processes\Operations\Contracts\Validation\ViewForm\ValidationFormController;

class OnlineContractSave {



    public static function handle($contract, $data){

        $res = (object)['state'=> false, 'msg' => 'Не удалось сохранить договор.'];

        //если нашли класс работы с продуктом
        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){

            if(auth()->user()->hasPermission('role', 'is_curator') && isset($data->set_agent_id) && (int)$data->set_agent_id > 0){
                if((int)$data->set_agent_id != (int)$contract->agent_id){
                    $agent = User::find((int)$data->set_agent_id);
                    $contract->agent_id = $agent->id;
                    $contract->agent_organization_id = $agent->organization_id;
                    $contract->agent_parent_id = $agent->parent_id;
                    $contract->agent_curator_id = $agent->curator_id;
                    $contract->user_id = $agent->id;
                }
            }


            // если всё нормельно сохранилось
            if($online_product_class::save($contract, (object)$data)){
                $res->state = true;
                $res->msg = 'Данные успешно сохранены!';

                ContractsLogs::setContractLogs(
                    $contract->id,
                    auth()->id(),
                    $contract->statys_id,
                    Contracts::STATYS[$contract->statys_id],
                    'Изменения данных',
                    \GuzzleHttp\json_encode($data));



            }

        }else{
            $res->msg = 'Класс продукта не найден!';

        }


        return $res;

    }

    public static function createSupplementary($contract){

        $errors = [];
        $result = new \stdClass();
        $result->state = false;
        $result->id = 0;
        $result->errors = ['errors' => ['Не удалось создать доп. соглашение']];

        //если нашли класс работы с продуктом
        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){

            // если всё нормельно сохранилось
            if($supplementary = $online_product_class::createSupplementary($contract)){
                $result->state = true;
                $result->errors = ['errors' => []];
                $result->id = $supplementary->number_id;

                ContractsLogs::setContractLogs(
                    $contract->id,
                    auth()->id(),
                    $contract->statys_id,
                    Contracts::STATYS[$contract->statys_id],
                    'Создано доп. соглашение '.$supplementary->title);

            }

        }else{
            $result->errors = ['errors' => ['Класс продукта не найден!']];
        }

        return $result;
    }

    public static function saveSupplementary($contract, $supplementary, $data){

        $res = (object)['state'=> false, 'msg' => 'Не удалось сохранить договор.'];

        //если нашли класс работы с продуктом
        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){

            // если всё нормельно сохранилось
            if($online_product_class::saveSupplementary($contract, $supplementary, (object)$data)){
                $res->state = true;
                $res->msg = 'Данные успешно сохранены!';

                ContractsLogs::setContractLogs(
                    $contract->id,
                    auth()->id(),
                    $contract->statys_id,
                    Contracts::STATYS[$contract->statys_id],
                    'Изменения данных доп. соглашение'.$supplementary->title,
                    \GuzzleHttp\json_encode($data));

            }

        }else{
            $res->msg = 'Класс продукта не найден!';

        }


        return (array)$res;

    }




    public static function calc($contract){

        $res = (object)['state'=> false, 'msg' => 'Не удалось рассчитать договор.', 'payment_total'=> 0];

        //если нашли класс работы с продуктом
        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){

            // если всё нормально сохранилось
            if($online_product_class::calc($contract)){
                $res->state = true;
                $res->msg = '';
                $res->payment_total = ($contract->calculation && isset($contract->calculation->sum))?getFloatFormat($contract->calculation->sum):0;


                ContractsLogs::setContractLogs(
                    $contract->id,
                    auth()->id(),
                    $contract->statys_id,
                    Contracts::STATYS[$contract->statys_id],
                    'Расчет договора');

            }


        }else{
            $res->msg = 'Класс продукта не найден!';

        }


        return (array)$res;

    }

    public static function copy($contract){
        $res = (object)['state'=> false, 'msg' => 'Не удалось скопировать договор.', 'id' => ''];
//если нашли класс работы с продуктом
        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){

            // если всё нормельно сохранилось
            if($new_contract = $online_product_class::copy($contract)){
                $res->state = true;
                $res->msg = '';
                $res->id = $new_contract->id;
            }

        }else{
            $res->msg = 'Класс продукта не найден!';

        }

        return (array)$res;
    }

    public static function prolongation($contract, $is_contract_id = null){
        $res = (object)['state'=> false, 'msg' => 'Не удалось скопировать договор.', 'id' => ''];
//если нашли класс работы с продуктом
        if($online_product_class = ContractOnlineProduct::get_online_product_class($contract)){

            // если всё нормельно сохранилось
            if($new_contract = $online_product_class::copy($contract, $is_contract_id)){

                $new_contract->is_prolongation = 1;
                $new_contract->prolongation_bso_id = $contract->bso_id;
                $new_contract->prolongation_bso_title = $contract->bso->bso_title;

                $new_contract->sign_date = getDateTime();
                $new_contract->begin_date = date('Y-m-d 00:00:00', strtotime("+1 day {$contract->end_date}"));//date('Y-m-d 00:00:00', strtotime("+1 day"));

                $new_contract->save();

                $res->state = true;
                $res->msg = '';
                $res->id = $new_contract->id;
            }

        }else{
            $res->msg = 'Класс продукта не найден!';

        }

        return (array)$res;
    }




}