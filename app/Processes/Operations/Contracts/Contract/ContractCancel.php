<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Domain\Entities\Contracts\EContract;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Services\PaymentAgent\IntegrationPaymentAgent;
use Illuminate\Support\Str;

class ContractCancel{


    public static function check($contract){

        $result = new \stdClass();
        $result->state = false;
        $result->msg = 'Ошибка прав!';

        if($contract->begin_date > date("Y-m-d H:i:s") || auth()->user()->hasPermission('contracts', 'cancel_contract'))
        {
            if(sizeof($contract->damages) == 0){

                $result->state = true;
                foreach ($contract->payments as $payment){
                    if($payment->statys_id == 1){
                        if($payment->reports_order_id > 0 || $payment->reports_dvou_id > 0){
                            $result->state = false;
                            $result->msg = 'Договор находится в отчете! Удалите из отчета!';
                        }

                        if($payment->payment_method->key_type == 4 && !auth()->user()->hasPermission('contracts', 'cancel_contract')){
                            $result->state = false;
                            $result->msg = 'У Вас нет прав на удаление договора с методом оплаты "Платежная страница"!';
                        }
                    }
                }

            }else{
                $result->msg = 'По договору есть убытки, анулировать нельзя!';
            }

        }else{
            $result->msg = 'Вы не можете аннулировать договор!';
        }

        return $result;
    }

    public static function delete($contract, $bso_states){

        $result = ContractCancel::check($contract);

        if($result->state == true){

            foreach ($bso_states as $id => $state){
                $bso = BsoItem::find($id);
                $bso->state_id = $state;
                $bso->contract_id = 0;
                $bso->save();

                BsoLogs::setLogs(
                    $bso->id,
                    $bso->state_id,
                    $bso->location_id,
                    0,
                    0,
                    auth()->id(),
                    0,
                    0,
                    0
                );
            }

            $contract->statys_id = -1;
            $contract->save();

            foreach ($contract->payments as $payment){
                $payment->is_deleted = 1;
                $payment->save();

                if($payment->invoice){
                    //Платежная страница
                    if($payment->payment_method->key_type == 4){
                        IntegrationPaymentAgent::deleteBillingPage($payment->invoice);
                    }

                    //ОФД
                    if($payment->payment_method->key_type == 1){
                        IntegrationPaymentAgent::deleteOfdReceipt($payment->invoice);
                    }
                }


            }
        }

        return $result;
    }


    public static function deleteDraft($contract_id){

        $contract = Contracts::getContractId($contract_id, true);
        if($contract){
            if($contract->statys_id == 0){

                //self::deleteDraftSubject($contract->insurer);
                //self::deleteDraftSubject($contract->owner);
                //self::deleteDraftSubject($contract->beneficiar);
                $contract->contracts_insurers()->delete();
                $contract->data()->delete();
                $contract->calculation()->delete();
                $contract->payments()->delete();
                $contract->delete();
                return true;
            }
        }



        return false;
    }

    private static function deleteDraftSubject($subject){

        if($subject){
            $_info = $subject->get_info();
            if($_info) $_info->delete();
            $subject->delete();
        }

        return true;
    }

}