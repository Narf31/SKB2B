<?php

namespace App\Processes\Operations\Contracts\Contract;


use App\Domain\Entities\Contracts\EContract;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\User;
use App\Processes\Operations\Contracts\BSO\BSOAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\Contracts\Products\ContractProduct;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use Illuminate\Support\Str;

class ContractCreate{


    public static function create($product, $program, $agent_id){

        $agent = User::find($agent_id);
        $bso_supplier = $agent->getUserBsoSupplier();

        $sign_date = getDateTime();
        $begin_date = date('Y-m-d 00:00:00', strtotime("+1 day"));
        $end_date = date('Y-m-d 00:00:00', strtotime("+1 year $begin_date"));
        $end_date = date('Y-m-d 23:59:59', strtotime("-1 day $end_date"));


        $contract = new Contracts();
        $contract->product_id = $product->id;
        if(isset($program) && isset($program->id) && (int)$program->id>0){
            $contract->program_id = $program->id;
        }


        $contract->bso_supplier_id = $bso_supplier->id;

        $contract->agent_id = $agent_id;
        $contract->agent_organization_id = $agent->organization_id;
        $contract->agent_parent_id = $agent->parent_id;
        $contract->agent_curator_id = $agent->curator_id;
        $contract->user_id = $agent->id;
        $contract->statys_id = 0;
        $contract->is_online = 1;
        $contract->sales_condition = (int)$agent->sales_condition;

        $contract->sign_date = $sign_date;
        $contract->begin_date = $begin_date;
        //$contract->end_date = $end_date;
        $contract->md5_token = getContractMd5Token($contract);
        $contract->save();

        ContractProduct::createProductData($contract);
        Subjects::getSubjectContract($contract, 'insurer');


        if(auth()->guard('client')->check()){

            $subject = Subjects::getSubjectContract($contract, 'insurer');
            GeneralSubjectsSearch::clone_general(auth()->guard('client')->id(), $subject, 0);

            ContractsLogs::setContractLogs($contract->id, 0, 0, 'Создан клиентом');

        }else{

            ContractsLogs::setContractLogs($contract->id, $agent_id, 0, 'Создан');

        }


        return $contract;
    }



}