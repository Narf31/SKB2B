<?php

namespace App\Models\Contracts;

use App\Classes\Export\TagModels\Contracts\TagContracts;
use App\Models\BSO\BsoItem;
use App\Models\Contracts\ObjectInsurer\ObjectEquipmentAuto;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerFlats;
use App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\InstallmentAlgorithms;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Directories\ProductsPrograms;
use App\Models\File;
use App\Models\Orders\Damages;
use App\Models\Orders\Pso;
use App\Models\Settings\FinancialGroupPayment;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Directories\Products;


class Contracts extends Model {

    use ActiveConstTrait;

    const FILES_DOC = 'contracts/docs';

    const TAG_MODEL = TagContracts::class;

    protected $table = 'contracts';
    protected $guarded = ['id'];
    public $timestamps = true;

    const SALES_CONDITION = [
        0 => 'Агентская',
        1 => 'Продажа организации',
    ];

    const STATYS = [
        -1 => 'Удален',
        0 => 'Создан',
        1 => 'В проверке',
        2 => 'Согласование',
        3 => 'На оплате',
        4 => 'Выпущен',
    ];

    const KIND_ACCEPTANCE = [
        0 => 'Условный',
        1 => 'Безусловный',
    ];

    const TYPE = [
        0 => 'Первичный',
        1 => 'Пролонгация',
    ];

    const SCORING_STATE = [
        0 => 'Нет запретов',
        1 => 'Согласование',
        2 => 'Запрет',
    ];


    public function bso_supplier() {
        return $this->hasOne(BsoSuppliers::class, 'id', 'bso_supplier_id');
    }

    public function logs() {
        return $this->hasMany(ContractsLogs::class, 'contract_id');
    }

    public function bso() {
        return $this->hasOne(BsoItem::class, 'id', 'bso_id');
    }

    public function insurance_companies() {
        return $this->hasOne(InsuranceCompanies::class, 'id', 'insurance_companies_id');
    }

    public function agent() {
        return $this->hasOne(User::class, 'id', 'agent_id');
    }

    public function manager() {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function curator() {
        return $this->hasOne(User::class, 'id', 'agent_curator_id');
    }

    public function insurer() {
        return $this->hasOne(Subjects::class, 'id', 'insurer_id');
    }

    public function owner() {
        return $this->hasOne(Subjects::class, 'id', 'owner_id');
    }

    public function beneficiar() {
        return $this->hasOne(Subjects::class, 'id', 'beneficiar_id');
    }

    public function check_user() {
        return $this->hasOne(User::class, 'id', 'check_user_id');
    }

    public function calculation() {
        return $this->belongsTo(ContractsCalculation::class, 'id', 'contract_id')->where("is_actual", 1);
    }

    public function product() {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function program() {
        return $this->hasOne(ProductsPrograms::class, 'id', 'program_id');
    }

    public function object_insurer() {
        return $this->hasOne(ObjectInsurer::class, 'id', 'object_insurer_id');
    }

    public function object_insurer_auto() {
        return $this->hasOne(ObjectInsurerAuto::class, 'object_insurer_id', 'object_insurer_id');
    }


    public function object_insurer_flats(){
        return $this->hasOne(ObjectInsurerFlats::class, 'object_insurer_id', 'object_insurer_id');
    }

    public function scans() {
        return $this->belongsToMany(File::class, 'contracts_scans', 'contract_id', 'file_id');
    }

    public function document($document_id) {
        return $this->hasOne(ContractsDocuments::class, 'contract_id', 'id')->where("document_id", $document_id)->get()->first();
    }

    public function masks() {
        return $this->belongsToMany(File::class, 'contracts_masks', 'contract_id', 'file_id');
    }

    public function masks_table() {
        return $this->hasMany(ContractsMasks::class, 'contract_id', 'id');
    }

    public function contracts_insurers() {
        return $this->hasMany(ContractsInsurer::class, 'contract_id', 'id');
    }

    public function scorings() {
        return $this->hasMany(ContractsScorings::class, 'contract_id', 'id')->orderBy('created_at', 'desc');
    }

    public function payments() {
        return $this->hasMany(Payments::class, 'contract_id', 'id');
    }

    public function getPaymentsFirstNotInvoice() {
        $payment = $this->payments()->where('statys_id', 0)->whereNull('invoice_id');
        return $payment->get()->first();
    }

    public function getPaymentsFirstInvoiceLink() {
        $payment = $this->payments()->where('statys_id', 0)->where('invoice_id','>', 0);
        return $payment->get()->first();
    }

    public function installment_algorithms() {
        return $this->hasOne(InstallmentAlgorithms::class, 'id', 'installment_algorithms_id');
    }

    public function pso_order() {
        return $this->hasOne(Pso::class, 'contract_id', 'id')->where("type_id", 0);
    }

    public function damages() {
        return $this->hasMany(Damages::class, 'contract_id', 'id')->where("type_id", 1);
    }

    public function history_logs() {
        return $this->hasMany(ContractsLogs::class, 'contract_id', 'id')->orderBy('created_at', 'desc');
    }

    public function contracts_logs_payments() {
        return $this->hasMany(ContractsLogsPayments::class, 'contract_id', 'id')->orderBy('created_at', 'desc');
    }

    public function chats(){
        return $this->hasMany(ContractsChat::class, 'contract_id');
    }

    public function chatsMsg($type){
        return $this->hasMany(ContractsChat::class, 'contract_id')->where("type_id", $type)->get();
    }

    public function mainContract() {
        return $this->hasOne(Contracts::class, 'id', 'prolongation_bso_id');
    }

    public function supplementary() {
        return $this->hasMany(ContractsSupplementary::class, 'contract_id', 'id');
    }

    public function getSupplementaryNumber($number) {
        return $this->supplementary()->where('number_id', $number)->get()->first();
    }

    public function object_equipment() {
        return $this->hasMany(ObjectEquipmentAuto::class, 'contract_id', 'id');
    }


    public function matching_underwriter() {
        return $this->hasOne(Matching::class, 'id', 'matching_underwriter_id');
    }

    public function matching_sb() {
        return $this->hasOne(Matching::class, 'id', 'matching_sb_id');
    }

    public function matching_inspection() {
        return $this->hasOne(Pso::class, 'id', 'matching_inspection_id');
    }


    public function data(){
        $_class = $this->getClassPoductData();
        if($_class){
            return $this->hasOne($_class, 'contract_id', 'id');
        }
        return null;
    }

    public function getClassPoductData(){
        $_class = null;
        if($this->product->slug == 'vzr'){
            $_class = Products\Data\VZR::class;
        }

        if($this->product->slug == 'prf'){
            $_class = Products\Data\PRF::class;
        }

        if($this->product->slug == 'migrants'){
            $_class = Products\Data\Migrants::class;
        }

        if($this->product->slug == 'liabilityArbitrationManager' || $this->product->slug == 'arbitration'){
            $_class = Products\Data\LiabilityArbitrationManager::class;
        }

        if($this->product->slug == 'nsPrisoners'){
            $_class = Products\Data\NSPrisoners::class;
        }

        if($this->product->slug == 'osago'){
            $_class = Products\Data\Osago::class;
        }


        if($this->product->slug == 'kasko'){
            if($this->program){
                if($this->program->slug == 'standard' || $this->program->slug == 'calculator'){
                    $_class = Products\Data\Kasko\Standard::class;
                }

                if($this->program->slug == 'drive'){
                    $_class = Products\Data\Kasko\Drive::class;
                }
            }

        }

        if($this->product->slug == 'gap'){
            $_class = Products\Data\GAP\Gap::class;
        }

        if($this->product->slug == 'dgo'){
            $_class = Products\Data\DGO\Dgo::class;
        }

        if($this->product->slug == 'mortgage'){
            $_class = Products\Data\Mortgage\Mortgage::class;
        }

        return $_class;
    }


    public static function getContractsQuery() {

        $contracts = Contracts::query();

        $rolesVisibility = auth()->user()->role->rolesVisibility(6);


        if ($rolesVisibility) {

            $visibility = $rolesVisibility->visibility;

            if ($visibility == 0) {//Все

            } elseif ($visibility == 1) {//Все в рамках организации

                $contracts->where(function ($query) {
                    $query->where('contracts.agent_id', auth()->id())
                        ->orWhere('contracts.agent_parent_id', auth()->id())
                        ->orWhere('contracts.agent_curator_id', auth()->id())
                        ->orWhere('contracts.agent_organization_id', auth()->user()->organization_id);
                });



            } elseif ($visibility == 2) {//Только свои

                $contracts->where('contracts.agent_id', auth()->id());

            } elseif ($visibility == 3) {//Только свои и своих подчиненных

                $contracts->where(function ($query) {
                    $query->where('contracts.agent_id', auth()->id())
                        ->orWhere('contracts.agent_parent_id', auth()->id())
                        ->orWhere('contracts.agent_curator_id', auth()->id());
                });
            }
        } else {
            $contracts->where('contracts.agent_id', auth()->id());
        }



        return $contracts;
    }

    public static function getContracts($statys) {
        $contracts = Contracts::getContractsQuery();

        $contracts->whereIn('statys_id', $statys);
        return $contracts;
    }

    public static function getContractId($id, $is_return = false) {

        $contract = Contracts::getContractsQuery()->where('id', $id)->get()->first();
        if(!$contract){
            if($is_return == true) return null;

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

    public function getProductAndProgramTitle()
    {
        return ($this->program)?$this->product->title." - ".$this->program->title:$this->product->title;
    }

    public function getAlgorithms()
    {

        $group_id = $this->agent->financial_group_id;

        $is_underwriting = (int)auth()->user()->is('is_underwriter');


        $algorithms = InstallmentAlgorithms::query()
            ->where('bso_supplier_id', $this->bso_supplier_id)
            ->where('product_id', $this->product_id)
            ->where('group_id', $group_id)
            ->leftJoin('installment_algorithms_payment', 'installment_algorithms_payment.id', '=', 'installment_algorithms.algorithm_id')
            ->select('installment_algorithms.*', \DB::raw("installment_algorithms_payment.title as title"));

        if($is_underwriting == 0){
            $algorithms->where('is_underwriting', 0);
        }

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


    public function getContractsStatusTitle()
    {
        $title = self::STATYS[$this->statys_id];

        $contract = $this;

        if($contract->statys_id == 2){

            $matching_name = '';

            if($contract->calculation->matching){
                $matching_name = 'Андеррайтер: '. Matching::STATYS[$contract->calculation->matching->status_id];
            }




            if($contract->matching_underwriter) {
                $matching_name .= 'Андеррайтер: '. Matching::STATYS[$contract->matching_underwriter->status_id].'; ';
            }
            if($contract->matching_sb){
                $matching_name .= 'Служба безопасности: '. Matching::STATYS[$contract->matching_sb->status_id].'; ';
            }


            if(strlen($matching_name) > 0){
                $title = "{$title} - $matching_name";
            }


        }

        return $title;
    }


}
