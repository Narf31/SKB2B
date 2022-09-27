<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Models\Contracts\Payments;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Services\Integration\VernaControllers\VernaPayment;
use Illuminate\Support\Str;

class ContractAccept{


    public static function accept($contract, $data){

        $result = new \stdClass();
        $result->state = false;
        $result->msg = 'Ошибка акцепта!';





        $bso_id = (int)$data->bso_id;
        if(isset($data->bso_is_epolicy) && $data->bso_is_epolicy == 1 && $contract->bso_id > 0){
            $bso_id = $contract->bso_id;
        }

        $bso = BSOAccept::getBso($contract, $bso_id);

        if($bso){

            Payments::where('contract_id', $contract->id)->update(['bso_id' => $bso->id]);

            $contract->bso_id = $bso->id;
            $contract->bso_title = $bso->bso_title;
            $contract->statys_id = 3;
            $contract->save();

            $payment = $contract->getPaymentsFirstNotInvoice();
            if($payment){


                $result = PaymentsAccept::accept($contract, $payment, $data);



                if($result->state == false){
                    $contract->statys_id = 0;
                }else{

                }

                $contract->save();

            }else{
                $result->msg = "Ошибка платежа!";
            }

        }else{
            $result->msg = "Ошибка БСО!";
        }

        return $result;
    }




}