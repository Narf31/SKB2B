<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\ContractsCalculation;
use App\Models\Contracts\Matching;
use App\Models\Contracts\Payments;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Settings\Notification;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use Illuminate\Support\Str;

class ContractMatching{


    public static function sendMatching($contract, $user){




        if(!$contract->calculation){
            $calculation = new ContractsCalculation();
            $calculation->contract_id = $contract->id;
            $calculation->state_calc = 1;
            $calculation->save();
        }else{
            $calculation = $contract->calculation;
        }

        if(!$calculation->matching){

            $insurer_title = '';
            if($contract->insurer){
                $insurer_title = $contract->insurer->title;
            }

            if($contract->product->slug == 'liabilityArbitrationManager'){
                $insurer_title = $contract->data->general_insurer->title;
            }


            $matching = Matching::create([
                'type_id' => 0,
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
            ]);
            $calculation->matching_id = $matching->id;
            $calculation->save();
        }else{
            $matching = $contract->calculation->matching;
            $matching->status_id = 3;
            $matching->save();

            if($matching->check_user_id > 0){
                Notification::setNotificationContractUnder($matching->check_user_id, $matching, $matching->insurer_title.' Cогласования - '.Matching::STATYS[$matching->status_id].' '.auth()->user()->name);
            }

        }

        /*
        ЕСЛИ ОШИБКА ПРИ СОЗДАНИИ СОГЛАСОВАНИЯ СОГЛАСОВВАНИЯ

        $calculation->state_calc = 0;
        $calculation->messages = "Ошибка согласования!";
        $calculation->save();
        return false;
        */




        $contract->statys_id = 2;
        $contract->save();
        return $contract;
    }


    public static function sendSupplementaryMatching($contract, $supplementary, $user)
    {
        $errors = ['Не удалось отправить доп. соглашение на согласование'];
        $result = new \stdClass();
        $result->state = false;
        $result->id = 0;
        $result->errors = ['errors' => $errors];

        if(!$supplementary->matching){

            $insurer_title = '';
            if($contract->insurer){
                $insurer_title = $contract->insurer->title;
            }


            $matching = Matching::create([
                'type_id' => 0,
                'status_id' => 0,
                'category_id' => 1,
                'category_title' => "{$contract->product->title}",
                'contract_id' => $contract->id,
                'supplementary_id' => $supplementary->id,
                'product_id' => $contract->product_id,
                'insurer_title' => $insurer_title,
                'initiator_user_id' => $user->id,
                'initiator_organization_id' => $user->organization_id,
                'initiator_parent_id' => $user->parent_id,
                'initiator_curator_id' => $user->curator_id,
            ]);

        }else{
            $matching = $supplementary->matching;
            $matching->status_id = 3;
            $matching->save();

            if($matching->check_user_id > 0){
                Notification::setNotificationContractUnder($matching->check_user_id, $matching, $matching->insurer_title.' Cогласования - '.Matching::STATYS[$matching->status_id].' '.auth()->user()->name);
            }

        }

        if($matching){
            $supplementary->matching_id = $matching->id;
            $supplementary->status_id = 2;
            $supplementary->save();
            $result->state = true;
        }

        return $result;
    }

}