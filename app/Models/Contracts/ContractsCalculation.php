<?php

namespace App\Models\Contracts;

use App\Models\Directories\ProductsPrograms;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class ContractsCalculation extends Model {

    use ActiveConstTrait;

    protected $casts = [
        'json' => 'json',
    ];
    protected $table = 'contracts_calculations';
    protected $guarded = ['id'];
    public $timestamps = true;


    public function contract(){
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function matching(){
        return $this->hasOne(Matching::class, 'id', 'matching_id');
    }

    public function program() {
        return $this->hasOne(ProductsPrograms::class, 'id', 'program_id');
    }

    public function save(array $options = array()) {
        $save = parent::save($options);


    }

    public function createPaymentCalc($is_contract = 0){

        $contract = $this->contract;
        //Определяем
        if($contract->financial_policy_manually_set == 1) {

        }else{
            $financial_policy = PaymentsFinancialPolicy::getFinancialPolicy($contract,$is_contract);

            $contract->financial_policy_id = $financial_policy->financial_policy_id;
            $contract->financial_policy_manually_set = $financial_policy->financial_policy_manually_set;
            $contract->financial_policy_kv_bordereau = $financial_policy->financial_policy_kv_bordereau;
            $contract->financial_policy_kv_dvoy = $financial_policy->financial_policy_kv_dvoy;
            $contract->financial_policy_kv_parent = $financial_policy->financial_policy_kv_parent;
        }

        $contract->save();

        //Создаем платежи
        if(PaymentsCreate::save($contract)){
            $this->state_calc = 1;
        }else{
            $this->state_calc = 0;
        }
        $this->save();

        return $this->state_calc;

    }


}
