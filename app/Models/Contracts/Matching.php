<?php

namespace App\Models\Contracts;

use App\Classes\Export\TagModels\Contracts\TagContracts;
use App\Helpers\Visible;
use App\Models\Actions\Accept;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerFlats;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\FinancialPolicy;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\InsuranceCompanies;
use App\Models\File;
use App\Models\Orders\Damages;
use App\Models\Organizations\Organization;
use App\Models\Settings\FinancialGroupPayment;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Directories\Products;


class Matching extends Model {

    const FILES_DOC = 'contracts/docs';


    protected $table = 'matching';
    protected $guarded = ['id'];
    public $timestamps = true;

    const TPYE = [
        0 => 'Андеррайтинг',
        1 => 'Служба безопасности',
    ];

    const CATEGORY = [
        0 => 'Договор',
        1 => 'Доп. соглашение',
    ];

    const STATYS = [
        0 => 'Создан',
        1 => 'В работе',
        2 => 'Возвращена на доработку',
        3 => 'Возвращена с доработки',
        4 => 'Согласовано',
        5 => 'Запрет',
        6 => 'Согласование руководителя',
    ];



    public function check_user() {
        return $this->hasOne(User::class, 'id', 'check_user_id');
    }

    public function initiator_user() {
        return $this->hasOne(User::class, 'id', 'initiator_user_id');
    }

    public function initiator_parent() {
        return $this->hasOne(User::class, 'id', 'initiator_parent_id');
    }

    public function initiator_curator() {
        return $this->hasOne(User::class, 'id', 'initiator_curator_id');
    }

    public function contract() {
        return $this->hasOne(Contracts::class, 'id', 'contract_id');
    }

    public function supplementary() {
        return $this->hasOne(ContractsSupplementary::class, 'id', 'supplementary_id');
    }

    public function underwriting_user_logs(){
        return $this->hasMany(UnderwritingCheckUserLog::class, 'matching_id');
    }



    public static function getQuery() {

        $matchings = Matching::query();

        $rolesVisibility = auth()->user()->role->rolesVisibility(14);

        if ($rolesVisibility) {

            $visibility = $rolesVisibility->visibility;

            if ($visibility == 0) {//Все

            } elseif ($visibility == 1) {//Все в рамках организации

                $matchings->where(function ($query) {
                    $query->where('initiator_user_id', auth()->id())
                        ->orWhere('initiator_parent_id', auth()->id())
                        ->orWhere('initiator_curator_id', auth()->id())
                        ->orWhere('initiator_organization_id', auth()->user()->organization_id);
                });



            } elseif ($visibility == 2) {//Только свои

                $matchings->where('initiator_user_id', auth()->id());

            } elseif ($visibility == 3) {//Только свои и своих подчиненных

                $matchings->where(function ($query) {
                    $query->where('initiator_user_id', auth()->id())
                        ->orWhere('initiator_parent_id', auth()->id())
                        ->orWhere('initiator_curator_id', auth()->id())
                        ->orWhere('initiator_organization_id', auth()->user()->organization_id);
                });
            }
        } else {
            $matchings->where('initiator_user_id', auth()->id());
        }



        return $matchings;
    }

    public static function getContracts($statys) {
        $contracts = Contracts::getContractsQuery();

        $contracts->whereIn('statys_id', $statys);
        return $contracts;
    }

    public static function getContractId($id) {

        $contract = Contracts::getContractsQuery()->where('id', $id)->get()->first();
        if(!$contract){
            abort(403);
        }

        return $contract;
    }

    public static function getContractToken($md5_token) {
        $contract = Contracts::where('md5_token', $md5_token)->get()->first();
        if(!$contract){
            abort(403);
        }

        return $contract;
    }

    public function getProductOrProgram()
    {
        return ($this->program)?$this->program:$this->product;
    }

    public function getAlgorithms()
    {

        $group_id = $this->agent->financial_group_id;

        $algorithms = InstallmentAlgorithms::query()
            ->where('bso_supplier_id', $this->bso_supplier_id)
            ->where('product_id', $this->product_id)
            ->where('group_id', $group_id)
            ->leftJoin('installment_algorithms_payment', 'installment_algorithms_payment.id', '=', 'installment_algorithms.algorithm_id')
            ->select('installment_algorithms.*', \DB::raw("installment_algorithms_payment.title as title"));

        return $algorithms->get();
    }


    public function getFinancialGroupPayment()
    {
        return FinancialGroupPayment::getPaymentsProduct($this->id);
    }

    public function getViewStateOnline(){

        return $this->statys_id == 0 ? 'edit' : 'view';
    }

    public function getActualStatus()
    {
        $status = 'Не актуальный';
        if($this->begin_date <= getDateTime() && $this->end_date >= getDateTime()){
            $status = 'Действует';
        }

        return $status;
    }



    public function isUnderNotWork()
    {
        $is_not_work = 0;
        if(in_array($this->status_id, [1,2,3])){
            $is_not_work = 1;
        }

        return $is_not_work;
    }


    public function isUnderLog(){

        if($this->underwriting_user_logs()->where('user_id', auth()->id())->get()->first()){
            return true;
        }

        return false;
    }

}
