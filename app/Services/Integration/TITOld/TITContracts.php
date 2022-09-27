<?php

namespace App\Services\Integration\TITOld;

use App\Models\Acts\ReportAct;
use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoActsItems;
use App\Models\BSO\BsoCarts;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Cashbox\Cashbox;
use App\Models\Cashbox\CashboxTransactions;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsDocuments;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\ContractsSupplementary;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\BsoSuppliers;
use App\Models\Directories\Products\Data\Kasko\Standard;
use App\Models\Directories\ProductsPrograms;
use App\Models\Finance\Invoice;
use App\Models\Organizations\Organization;
use App\Models\Reports\ReportOrders;
use App\Models\Settings\PaymentMethods;
use App\Models\Settings\SettingsSystem;
use App\Models\Subject\Physical;
use App\Models\User;
use App\Models\Vehicle\VehicleColor;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Processes\Operations\Contracts\Contract\ContractCreate;
use App\Processes\Operations\Contracts\Invoice\InvoiceAutomatic;
use App\Processes\Operations\Contracts\Payments\PaymentsCreate;
use App\Processes\Operations\Contracts\Products\CalcKasko;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use App\Processes\Scenaries\Contracts\Scorings\Products\Kasko;
use Mockery\Exception;

class TITContracts
{



    public function getDataInfo($start, $request)
    {

        $res = new \stdClass();
        $res->state = 1;
        $res->msg = "Метод не найден";
        $res->count_all = 0 ;

        $select = "SELECT count(*) as count_all FROM prod_policies where status=110 and id_insurance in (17, 52)";
        $results = TITSend::send($select);

        if($results && isset($results[0])){
            $res->state = 0;
            $res->msg = "";
            $res->count_all = $results[0]->count_all;
        }

        return $res;
    }


    public function updateDataInfo($start, $counts, $request, $count_all)
    {

        $res = new \stdClass();
        $res->state = 0;
        $res->msg = "";
        $res->start = 0;
        $res->result = null;

        $res->progressbar = 0;
        $res->count_all = 0;

        $result = [];


        $select = "SELECT * FROM prod_policies where status=110 and id_insurance in (17, 52)  LIMIT {$counts} OFFSET {$start}";
        $results = TITSend::send($select);

        foreach ($results as $_result)
        {
            $this->saveContract($_result);
        }

        $res->start = $start+count($results);


        $progressbar_set = $start+$counts;
        if($result && count($result) > 0 ){
            $progressbar_set = $result[count($result)-1]->id;
        }


        if($progressbar_set > $count_all) $progressbar_set = $count_all;

        $res->start = $progressbar_set;

        $res->progressbar = (int)ceil(($progressbar_set/$count_all)*100);

        $res->result = $result;
        if($res->progressbar < 100){
            $res->state = 0;
        }else{
            $res->state = 1;
        }


        return $res;
    }



    const product_id = 2;
    const program_id = 2;
    const program = null;

    const produtcs = [
        'kasko.telematika' => 2,
        'kasko.eqgap' => 4,
        'simple-kasko-sst' => 2,
        'carsharing' => 2,
        'retro-gap' => 4,
        'kasko.newgap' => 4,
    ];

    const payment_method = [
        'Наличные' => 2,
        'Оплата по счёту' => 5,
    ];

    public function __construct()
    {
        $this->program = ProductsPrograms::find(self::program_id);
    }


    public function saveContract($result)
    {



        $product_id = isset(self::produtcs[$result->code_product])? self::produtcs[$result->code_product] : null;
        if(!$product_id) return false;


        $user = User::where('export_user_id', $result->id_user)->get()->first();
        if(!$user) return false;

        $bso = self::getBSOSerya($result->number, $product_id, $user);
        if(!$bso) return false;

        $json = \GuzzleHttp\json_decode($result->data);

        //Создаем договор
        $contract = $this->createContract($bso, $user, $result, $json);

        //Создаем платеж
        $this->createPayments($contract, $result->id);



        \DB::table('reports_payments')->truncate();

        return true;
    }


    public static function clearContracts(){
        //БСО
        BsoLogs::query()->truncate();
        BsoCarts::query()->truncate();
        BsoActs::query()->truncate();
        BsoActsItems::query()->truncate();
        ReportOrders::query()->truncate();
        ReportAct::query()->truncate();
        BsoItem::query()->truncate();

        \DB::table('report_payment_sum')->truncate();
        \DB::table('reservations')->truncate();
        \DB::table('notifications')->truncate();

        //Договора
        Invoice::query()->truncate();
        Cashbox::query()->truncate();
        CashboxTransactions::query()->truncate();
        Contracts::query()->truncate();
        ContractsDocuments::query()->truncate();
        ContractsLogs::query()->truncate();
        Payments::query()->truncate();

        \DB::table('object_insurer')->truncate();
        \DB::table('object_insurer_auto')->truncate();

        \DB::table('contracts_calculations')->truncate();
        \DB::table('contracts_insurer')->truncate();
        \DB::table('contracts_documents')->truncate();
        \DB::table('contracts_logs')->truncate();
        \DB::table('contracts_masks')->truncate();
        \DB::table('contracts_scans')->truncate();
        \DB::table('contracts_scorings')->truncate();

        \DB::table('contracts_supplementary')->truncate();

        \DB::table('subjects')->truncate();
        \DB::table('subjects_fl')->truncate();
        \DB::table('subjects_ul')->truncate();

        \DB::table('general_subjects')->truncate();
        \DB::table('general_subjects_address')->truncate();
        \DB::table('general_subjects_documents')->truncate();
        \DB::table('general_subjects_fl')->truncate();
        \DB::table('general_subjects_ul')->truncate();
        \DB::table('general_subjects_logs')->truncate();


        \DB::table('general_founders')->truncate();
        \DB::table('general_interactions_connections')->truncate();
        \DB::table('general_podft_fl')->truncate();
        \DB::table('general_podft_ul')->truncate();
        \DB::table('general_ul_of')->truncate();


        \DB::table('orders')->truncate();
        \DB::table('orders_chat')->truncate();
        \DB::table('orders_damages')->truncate();
        \DB::table('orders_damages_payments')->truncate();
        \DB::table('orders_logs')->truncate();
        \DB::table('orders_scans')->truncate();


        \DB::table('matching')->truncate();
        \DB::table('matching_underwriting_user_log')->truncate();

        \DB::table('mails_notification')->truncate();

        \DB::table('object_equipment')->truncate();

        \DB::table('products_kasko_drive')->truncate();
        \DB::table('products_kasko_standard')->truncate();
        \DB::table('products_gap')->truncate();
        \DB::table('products_dgo')->truncate();
        \DB::table('reports_payments')->truncate();

        return true;
    }

    private static function getBSOSerya($bso, $product_id, $user){

        $s_bso = new \stdClass();
        $s_bso->status = false;

        $s_bso->bso_serie = '';
        $s_bso->bso_number = '';

        $_arr = explode(' ', $bso);
        if(isset($_arr[1])){
            $s_bso->bso_serie = $_arr[0];
            $s_bso->bso_number = $_arr[1];
            $s_bso->status = true;
        }else{

            $_arr = explode('-', $bso);
            if(isset($_arr[2])){
                $s_bso->bso_serie = str_replace($_arr[2], '', $bso);
                $s_bso->bso_number = $_arr[2];
                $s_bso->status = true;
            }
        }

        if($s_bso->status == false){
            return null;
        }


        return self::getBSO($bso, $s_bso->bso_serie, $s_bso->bso_number, $product_id, $user);;
    }


    private static function getBSO($bso_title, $bso_serie, $bso_number, $product_id, $user)
    {

        //Базовые данные
        $type_bso_id = $product_id;
        $insurance_companies_id = 1;
        $bso_class_id = 1;
        $bso_count_number = 6;

        $serie = BsoSerie::where('insurance_companies_id', $insurance_companies_id)
            ->where('bso_class_id', $bso_class_id)
            ->where('product_id', $product_id)
            ->where('type_bso_id', $type_bso_id)
            ->where('bso_serie', $bso_serie)->get()->first();

        if(!$serie){
            $serie = BsoSerie::create([
                'type_bso_id' => $type_bso_id,
                'bso_class_id' => $bso_class_id,
                'insurance_companies_id' => $insurance_companies_id,
                'product_id' => $product_id,
                'bso_serie' => $bso_serie,
                'bso_count_number' => $bso_count_number,
            ]);
        }




        if($user){
            $agent = $user;
            $organization = $user->organization;
            if($organization->parent_user) $agent = $organization->parent_user;
        }else{
            $agent = User::find(1);
        }

        $bso_supplier = BsoSuppliers::findOrFail(1);

        $bso = BsoItem::where('bso_supplier_id', $bso_supplier->id)
            ->where('bso_serie_id', $serie->id)
            ->where('bso_number', $bso_number);



        $bso = $bso->get()->first();
        if($bso){
            return $bso;
        }

        $bso_serie = $serie;


        //Создаем БСО и вешаем на Агента
        $bso = BsoItem::create([
            'bso_supplier_id' => $bso_supplier->id,
            'org_id' => $bso_supplier->id,
            'insurance_companies_id' => $bso_supplier->insurance_companies_id,
            'bso_class_id' => $bso_serie->bso_class_id,
            'type_bso_id' => $bso_serie->type_bso_id,
            'product_id' => $bso_serie->product_id,
            'bso_serie_id' => $bso_serie->id,
            'bso_number' => $bso_number,
            'bso_title' => $bso_title,
            'location_id' => 4,
            'state_id' => 0,
            'agent_id' => $agent->id,
            'agent_organization_id' => $agent->organization->id,
            'user_id' => 0,
            'user_org_id' => $agent->organization->id,
            'time_create' => date('Y-m-d H:i:s'),
            'time_target' => date('Y-m-d H:i:s'),
            'last_operation_time' => date('Y-m-d H:i:s'),
            'transfer_to_agent_time' => date('Y-m-d H:i:s'),
            'transfer_to_org_time' => date('Y-m-d H:i:s'),
            'bso_manager_id' => (int)auth()->id(),
            'point_sale_id' => (int)$agent->point_sale_id,
            'bso_dop_serie_id' => 0,
        ]);



        BsoLogs::setLogs(
            $bso->id,
            $bso->state_id,
            $bso->location_id,
            0,
            0,
            0,
            0,
            0,
            0
        );



        return $bso;
    }


    private function createContract($bso, $user, $result, $json)
    {

        //Создаем договор
        $contract = ContractCreate::create($bso->product, $this->program, $user->id);
        $contract->bso_id = $bso->id;
        $contract->bso_title = $bso->bso_title;

        $begin_date = null;
        if(isset($json->startInsurance)) $begin_date = $json->startInsurance;
        if(isset($json->startDate)) $begin_date = $json->startDate;
        $end_date = null;
        if(isset($json->endInsurance)) $end_date = $json->endInsurance;
        if(isset($json->endDate)) $end_date = $json->endDate;

        $contract->statys_id = 0;
        $contract->sign_date = setDateTimeFormat($result->added);
        $contract->begin_date = getDateFormatEn($begin_date);
        $contract->end_date = getDateFormatEn($end_date);
        $contract->insurance_amount = getFloatFormat($json->carCost);
        $contract->payment_total = getFloatFormat($result->sum);

        $contract->financial_policy_id = 0;
        $contract->financial_policy_manually_set = 1;
        $contract->financial_policy_kv_dvoy = 0;
        $contract->financial_policy_kv_bordereau = 0;

        $contract->save();

        return $this->setContractData($contract, $result, $json, $user);

    }


    private function createPayments($contract, $export_id)
    {
        $select = "SELECT * FROM prod_policy_payment_parts where id_policy=$export_id";
        $results = TITSend::send($select);
        if($results){
            foreach ($results as $result){

                $payment = PaymentsCreate::create((int)$result->num, $contract, getFloatFormat($result->sum), $result->date, ["month" => 0, "payment" => "100"], true);
                $payment->bso_id = $contract->bso_id;
                $payment->is_export = 1;

                if(isset(self::payment_method[$result->payment_method])){
                    $method = PaymentMethods::findOrFail(self::payment_method[$result->payment_method]);
                    $payment->payment_method_id = $method->id;
                    $payment->payment_type = $method->payment_type;
                    $payment->payment_flow = $method->payment_flow;
                }

                $payment->save();

                //Создаем счет
                if(isset(self::payment_method[$result->payment_method]) && (int)$result->status == 1){
                    $i_type = Invoice::searchType($payment->payment_type, $payment->payment_flow);

                    $invoice = Invoice::create([
                        'user_id' => $payment->agent_id,
                        'status_id' => 1,
                        'type' => $i_type->TYPES,
                        'create_type' => 1,
                        'org_id' => $payment->bso->supplier->purpose_org_id,
                        'agent_id' => $payment->agent_id,
                        'type_invoice_payment_id' => $i_type->TYPE_INVOICE_PAYMENT,
                        'invoice_payment_total' => $payment->invoice_payment_total,
                        'invoice_payment_date' => getDateTime(),
                        'payment_method_id' => $payment->payment_method_id,
                        'md5_token' => $payment->contract->md5_token,
                        'client_email' => null,
                        'client_info' => null,
                        'client_type' => null,
                    ]);

                    $payment->invoice_id = $invoice->id;
                    $payment->save();

                    InvoiceAutomatic::closeInvoice($invoice, $result->payed, getFloatFormat($result->sum));
                }
            }
        }
        return true;

    }







    private function setContractData($contract, $result, $json, $user)
    {

        if($result->id_scheme == 1) return $this->setContractDataScheme1(Contracts::find($contract->id), $result, $json, $user);
        if($result->id_scheme == 16) return $this->setContractDataScheme16(Contracts::find($contract->id), $result, $json, $user);
        if($result->id_scheme == 14) return $this->setContractDataSchemeGAP(Contracts::find($contract->id), $result, $json, $user);
        if($result->id_scheme == 19) return $this->setContractDataSchemeGAP(Contracts::find($contract->id), $result, $json, $user);
        if($result->id_scheme == 29) return $this->setContractDataSchemeGAP(Contracts::find($contract->id), $result, $json, $user);

        //dump($result);
        dd($json);



        return $contract;
    }

    private function setContractDataScheme1($contract, $result, $json, $user)
    {


        $is_multidriver = 1;
        $is_only_spouses = 0;
        $limit_indemnity_id = ($json->limitIndemnity == 'Неагрегатная страховая сумма') ? 1 : 2;

        $is_auto_credit = 0;
        if(isset($json->creditType) && $json->creditType == 'Кредит') $is_auto_credit = 1;
        if(isset($json->carCreditType) && $json->carCreditType == 'Кредит') $is_auto_credit = 1;
        if(isset($json->carCreditType) && $json->carCreditType == 'Лизинг') $is_auto_credit = 2;


        $is_transition = 0;
        $official_discount = getFloatFormat((int)str_replace('-', '', $json->discount));
        $insurance_term = 0;

        $repair_options_id = 1;
        if(isset($json->repairType) && $json->repairType == 'Ремонт на СТОА по направлению Страховщика') $repair_options_id = 3;

        $franchise_id =  0;
        if(isset($json->franchiseSize) && Standard::FRANCHISE[(int)getFloatFormat($json->franchiseSize)]) $franchise_id =  (int)getFloatFormat($json->franchiseSize);

        $is_collection_certificates = 0;//Сбор справок в случае необходимости
        $is_emergency_commissioner = 0;//Аварийный Комиссар

        if(isset($json->services)){
            foreach ($json->services as $_s){
                if($_s == 'Аварийный Комиссар') $is_emergency_commissioner = 1;
                if($_s == 'Сбор справок в случае необходимости') $is_collection_certificates = 1;
            }
        }


        $calc_data = [];

        $minAge = isset($json->minAge)?(int)$json->minAge:0;
        $minAge = isset($json->age)?(int)$json->age:0;
        $minExperience = isset($json->minExperience)?(int)$json->minExperience:0;
        $minExperience = isset($json->experience)?(int)$json->experience:0;
        if($minAge > 0){
            $calc_data = ['type_multidriver'=>0, 'birthdate_year' => $minAge, 'birthdate_year_l' => $minAge, 'exp_year'=>$minExperience, 'exp_year_l'=>$minExperience];
        }

        $is_gap = 0;
        if(isset($json->isGap)) $is_gap = (int)$json->isGap;

        $coatings_risks_id = 1;
        if(isset($json->isDamage) && isset($json->isHijackTheft)){
            if((int)$json->isDamage == 1 && (int)$json->isHijackTheft == 1) $coatings_risks_id = 1;
            if((int)$json->isDamage == 0 && (int)$json->isHijackTheft == 1) $coatings_risks_id = 2;
        }

        $civil_responsibility_sum = 0;
        if(isset($json->isCivilLiability) && (int)$json->isCivilLiability == 1){
            if(isset($json->civilLiabilityCost)){
                $civil_responsibility_sum = (int)getFloatFormat($json->civilLiabilityCost);
            }

        }



        $contract->data()->update([
            'is_multidriver' => $is_multidriver,
            'is_only_spouses' => $is_only_spouses,
            'calc_data' => \GuzzleHttp\json_encode($calc_data),
            'is_auto_credit' => $is_auto_credit,
            'is_transition' => $is_transition,
            'official_discount' => $official_discount,
            'limit_indemnity_id' => $limit_indemnity_id,
            'insurance_term' => $insurance_term,
            'territory_id' => 1,
            'repair_options_id' => $repair_options_id,
            'franchise_id' => $franchise_id,
            'franchise_number_id' => ((int)$franchise_id>0)?(int)1:null,
            'is_collection_certificates' => $is_collection_certificates,
            'is_emergency_commissioner' => $is_emergency_commissioner,
            'coatings_risks_id' => $coatings_risks_id,
            'is_gap' => $is_gap,
            'civil_responsibility_sum' => $civil_responsibility_sum,
        ]);


        /*


        $program->is_evacuation = (int)$term->is_evacuation;
        $program->civil_responsibility_sum = (int)$term->civil_responsibility_sum;

        */

        //АВТО
        $object_insurer = $contract->object_insurer;
        $auto = $object_insurer->data();

        $mark = VehicleMarks::where('title', $json->carMark)->where('category_id', '3366')->get()->first();
        if($mark){
            $auto->ts_category = $mark->category->id;
            $auto->mark_id = $mark->id;

            $model = VehicleModels::where('title', $json->carModel)->where('mark_id2', $mark->id)->get()->first();
            if($model){
                $auto->model_id = $model->id;
            }
        }


        $color = null;
        if(isset($json->color)) $color = VehicleColor::where('title', $json->color)->get()->first();
        if(isset($json->carColor)){
            if($json->carColor == 'Другой') $json->carColor = "Иной";

            $color = VehicleColor::where('title', $json->carColor)->get()->first();
        }

        if($color){
            $auto->color_id = $color->id;
        }

        $auto->car_year = (int)$json->carYear;
        $auto->count_key = 2;
        if(isset($json->keys)) $auto->count_key = (int)$json->keys;
        if(isset($json->carIsAllKeys)) $auto->count_key = (int)$json->carIsAllKeys;

        $auto->car_price = getFloatFormat($json->carCost);



        if(isset($json->vin)) $auto->vin = $json->vin;
        if(isset($json->carVin)) $auto->vin = $json->carVin;

        if(isset($json->regNum)) $auto->reg_number = $json->regNum;
        if(isset($json->carRegNum)) $auto->reg_number = $json->carRegNum;

        if(isset($json->ptsDublicat)) $auto->is_duplicate = ($json->ptsDublicat == 'нет')?0:1;
        if(isset($json->carIsPtsOriginal)) $auto->is_duplicate = ($json->carIsPtsOriginal == 1)?0:1;



        $auto->is_credit = $is_auto_credit;

        if(isset($json->ptsNum) && strlen($json->ptsNum) > 0){
            $doc = explode('№', $json->ptsNum);
            $auto->doc_type = 220219;
            $auto->docserie = isset($doc[0])?$doc[0]:'';
            $auto->docnumber = isset($doc[1])?$doc[1]:'';

        }

        if(isset($json->carPtsNum) && strlen($json->carPtsNum) > 0){
            $doc = explode('№', $json->carPtsNum);
            $auto->doc_type = 220219;
            $auto->docserie = isset($doc[0])?$doc[0]:'';
            $auto->docnumber = isset($doc[1])?$doc[1]:'';

        }


        $auto->docdate = getDateFormatEn($json->ptsDate);

        $auto->save();

        $object_insurer->title = '';

        if($auto->mark){
            $object_insurer->title .= "{$auto->mark->title} ";
        }
        if($auto->model){
            $object_insurer->title .= "{$auto->model->title} ";
        }
        $object_insurer->title .= "{$auto->car_year}";
        $object_insurer->save();




        $SubjectsData = null;
        //Создаем контрагентов
        if(isset($json->insurant)) $SubjectsData = self::getSubjectsDataScheme1($json->insurant);
        if(isset($json->insurantLastName) || isset($json->insurantJureName)) $SubjectsData = self::getSubjectsDataScheme2((array)$json, 'insurant');

        if(!$SubjectsData) {
            dump($result->id);
            dd($json);
        }

        $insurer = $contract->insurer;
        $insurer->update($SubjectsData['subjects']);
        $info = $insurer->data();
        $info->save();
        $info->update($SubjectsData['info']);



        $is_beneficiary = '';
        if(isset($json->beneficiaryIsCopy)) $is_beneficiary = $json->beneficiaryIsCopy;
        if(isset($json->beneficiary)) $is_beneficiary = $json->beneficiary;

        if($is_beneficiary == 'Страхователь'){
            $contract->beneficiar_id = $contract->insurer_id;
        }else{

            $bankName = '';
            if(isset($json->bankName) && strlen($json->bankName) > 0) $bankName = $json->bankName;
            if(isset($json->creditBank) && strlen($json->creditBank) > 0) $bankName = $json->creditBank;

            if($json->beneficiaryType == 'Банк' && strlen($bankName) > 0){

                $general = $this->getGeneralSubjectsBank($bankName, $user);
                $subject = Subjects::create(['type' => 1, 'user_id' => $user->id]);
                $subject->general_subject_id = $general->id;
                $subject->title = $bankName;
                $subject->save();
                $contract->beneficiar_id = $subject->id;

            }else{



                $SubjectsData = null;
                //Создаем контрагентов
                if(isset($json->beneficiary) && is_object($json->beneficiary)) $SubjectsData = self::getSubjectsDataScheme1($json->beneficiary);

                if(isset($json->beneficiaryLastName) || isset($json->beneficiaryJureName)) $SubjectsData = self::getSubjectsDataScheme2((array)$json, 'beneficiary');






                $beneficiar = Subjects::create(['type' => 0, 'user_id' => $user->id]);
                $beneficiar->update($SubjectsData['subjects']);
                $info = $beneficiar->data();
                $info->save();
                $info->update($SubjectsData['info']);
                $contract->beneficiar_id = $beneficiar->id;



            }

        }


        $is_owner = '';
        if(isset($json->ownerIsCopy)) $is_owner = $json->ownerIsCopy;
        if(isset($json->owner)) $is_owner = $json->owner;

        if($is_owner == 'Страхователь'){
            $contract->owner_id = $contract->insurer_id;
        }elseif($is_owner == 'Выгодоприобретатель'){
            $contract->owner_id = $contract->beneficiar_id;
        }else{
            $contract->owner_id = $contract->beneficiar_id;
            //dump('ownerIsCopy: '.$result->id);
            //dd($json);
        }

        $contract->save();


        CalcKasko::setGeneralSubjects($contract);

        return $contract;

    }

    private function setContractDataScheme16($contract, $result, $json, $user)
    {


        $is_multidriver = 1;
        $is_only_spouses = 0;
        $limit_indemnity_id = 1;

        $is_auto_credit = 0;
        if(isset($json->creditType) && $json->creditType == 'Кредит') $is_auto_credit = 1;
        if(isset($json->carCreditType) && $json->carCreditType == 'Кредит') $is_auto_credit = 1;
        if(isset($json->carCreditType) && $json->carCreditType == 'Лизинг') $is_auto_credit = 2;


        $is_transition = 0;
        $official_discount = 0;
        if(isset($json->discount)) $official_discount = getFloatFormat((int)str_replace('-', '', $json->discount));
        $insurance_term = 0;

        $repair_options_id = 1;
        if(isset($json->repairType) && $json->repairType == 'Ремонт на СТОА по направлению Страховщика') $repair_options_id = 3;

        $franchise_id =  0;
        if(isset($json->franchiseSize) && Standard::FRANCHISE[(int)getFloatFormat($json->franchiseSize)]) $franchise_id =  (int)getFloatFormat($json->franchiseSize);

        $is_collection_certificates = 0;//Сбор справок в случае необходимости
        $is_emergency_commissioner = 0;//Аварийный Комиссар

        if(isset($json->services)){
            foreach ($json->services as $_s){
                if($_s == 'Аварийный Комиссар') $is_emergency_commissioner = 1;
                if($_s == 'Сбор справок в случае необходимости') $is_collection_certificates = 1;
            }
        }


        $calc_data = [];

        $minAge = isset($json->minAge)?(int)$json->minAge:0;
        $minAge = isset($json->age)?(int)$json->age:0;
        $minExperience = isset($json->minExperience)?(int)$json->minExperience:0;
        $minExperience = isset($json->experience)?(int)$json->experience:0;
        if($minAge > 0){
            $calc_data = ['type_multidriver'=>0, 'birthdate_year' => $minAge, 'birthdate_year_l' => $minAge, 'exp_year'=>$minExperience, 'exp_year_l'=>$minExperience];
        }

        $is_gap = 0;
        if(isset($json->isGap)) $is_gap = (int)$json->isGap;

        $coatings_risks_id = 1;
        if(isset($json->isDamage) && isset($json->isHijackTheft)){
            if((int)$json->isDamage == 1 && (int)$json->isHijackTheft == 1) $coatings_risks_id = 1;
            if((int)$json->isDamage == 0 && (int)$json->isHijackTheft == 1) $coatings_risks_id = 2;
        }


        $contract->data()->update([
            'is_multidriver' => $is_multidriver,
            'is_only_spouses' => $is_only_spouses,
            'calc_data' => \GuzzleHttp\json_encode($calc_data),
            'is_auto_credit' => $is_auto_credit,
            'is_transition' => $is_transition,
            'official_discount' => $official_discount,
            'limit_indemnity_id' => $limit_indemnity_id,
            'insurance_term' => $insurance_term,
            'territory_id' => 1,
            'repair_options_id' => $repair_options_id,
            'franchise_id' => $franchise_id,
            'franchise_number_id' => ((int)$franchise_id>0)?(int)1:null,
            'is_collection_certificates' => $is_collection_certificates,
            'is_emergency_commissioner' => $is_emergency_commissioner,
            'coatings_risks_id' => $coatings_risks_id,
            'is_gap' => $is_gap,
        ]);

        /*


        $program->is_evacuation = (int)$term->is_evacuation;
        $program->civil_responsibility_sum = (int)$term->civil_responsibility_sum;

        */

        //АВТО
        $object_insurer = $contract->object_insurer;
        $auto = $object_insurer->data();

        $mark = VehicleMarks::where('title', $json->carMark)->where('category_id', '3366')->get()->first();
        if($mark){
            $auto->ts_category = $mark->category->id;
            $auto->mark_id = $mark->id;

            $model = VehicleModels::where('title', $json->carModel)->where('mark_id2', $mark->id)->get()->first();
            if($model){
                $auto->model_id = $model->id;
            }
        }


        $color = null;
        if(isset($json->color)) $color = VehicleColor::where('title', $json->color)->get()->first();
        if(isset($json->carColor)){
            if($json->carColor == 'Другой') $json->carColor = "Иной";

            $color = VehicleColor::where('title', $json->carColor)->get()->first();
        }

        if($color){
            $auto->color_id = $color->id;
        }

        $auto->car_year = (int)$json->carYear;
        $auto->count_key = 2;
        if(isset($json->keys)) $auto->count_key = (int)$json->keys;
        if(isset($json->carIsAllKeys)) $auto->count_key = (int)$json->carIsAllKeys;

        $auto->car_price = getFloatFormat($json->carCost);



        if(isset($json->vin)) $auto->vin = $json->vin;
        if(isset($json->carVin)) $auto->vin = $json->carVin;

        if(isset($json->regNum)) $auto->reg_number = $json->regNum;
        if(isset($json->carRegNum)) $auto->reg_number = $json->carRegNum;

        if(isset($json->ptsDublicat)) $auto->is_duplicate = ($json->ptsDublicat == 'нет')?0:1;
        if(isset($json->carIsPtsOriginal)) $auto->is_duplicate = ($json->carIsPtsOriginal == 1)?0:1;



        $auto->is_credit = $is_auto_credit;

        if(isset($json->ptsNum) && strlen($json->ptsNum) > 0){
            $doc = explode('№', $json->ptsNum);
            $auto->doc_type = 220219;
            $auto->docserie = isset($doc[0])?$doc[0]:'';
            $auto->docnumber = isset($doc[1])?$doc[1]:'';

        }

        if(isset($json->carPtsNum) && strlen($json->carPtsNum) > 0){
            $doc = explode('№', $json->carPtsNum);
            $auto->doc_type = 220219;
            $auto->docserie = isset($doc[0])?$doc[0]:'';
            $auto->docnumber = isset($doc[1])?$doc[1]:'';

        }

        if(isset($json->ptsDate)){
            $auto->docdate = getDateFormatEn($json->ptsDate);
        }


        $auto->save();

        $object_insurer->title = '';

        if($auto->mark){
            $object_insurer->title .= "{$auto->mark->title} ";
        }
        if($auto->model){
            $object_insurer->title .= "{$auto->model->title} ";
        }
        $object_insurer->title .= "{$auto->car_year}";
        $object_insurer->save();




        $SubjectsData = null;
        //Создаем контрагентов
        if(isset($json->insurant)) $SubjectsData = self::getSubjectsDataScheme1($json->insurant);
        if(isset($json->insurantLastName) || isset($json->insurantJureName)) $SubjectsData = self::getSubjectsDataScheme2((array)$json, 'insurant');

        if(!$SubjectsData) {
            dump($result->id);
            dd($json);
        }

        $insurer = $contract->insurer;
        $insurer->update($SubjectsData['subjects']);
        $info = $insurer->data();
        $info->save();
        $info->update($SubjectsData['info']);



        $is_beneficiary = '';
        if(isset($json->beneficiaryIsCopy)) $is_beneficiary = $json->beneficiaryIsCopy;
        if(isset($json->beneficiary)) $is_beneficiary = $json->beneficiary;

        if($is_beneficiary == 'Страхователь'){
            $contract->beneficiar_id = $contract->insurer_id;
        }else{

            $bankName = '';
            if(isset($json->bankName) && strlen($json->bankName) > 0) $bankName = $json->bankName;
            if(isset($json->creditBank) && strlen($json->creditBank) > 0) $bankName = $json->creditBank;


            if(isset($json->beneficiaryType) && $json->beneficiaryType == 'Банк' && strlen($bankName) > 0){

                $general = $this->getGeneralSubjectsBank($bankName, $user);
                $subject = Subjects::create(['type' => 1, 'user_id' => $user->id]);
                $subject->general_subject_id = $general->id;
                $subject->title = $bankName;
                $subject->save();
                $contract->beneficiar_id = $subject->id;

            }else{



                $SubjectsData = null;
                //Создаем контрагентов
                if(isset($json->beneficiary) && is_object($json->beneficiary)) $SubjectsData = self::getSubjectsDataScheme1($json->beneficiary);

                if(isset($json->beneficiaryLastName) || isset($json->beneficiaryJureName)) $SubjectsData = self::getSubjectsDataScheme2((array)$json, 'beneficiary');



                if($SubjectsData){
                    $beneficiar = Subjects::create(['type' => 0, 'user_id' => $user->id]);
                    $beneficiar->update($SubjectsData['subjects']);
                    $info = $beneficiar->data();
                    $info->save();
                    $info->update($SubjectsData['info']);
                    $contract->beneficiar_id = $beneficiar->id;
                }else{
                    $contract->beneficiar_id = $contract->insurer_id;
                }






            }

        }


        $is_owner = '';
        if(isset($json->ownerIsCopy)) $is_owner = $json->ownerIsCopy;
        if(isset($json->owner)) $is_owner = $json->owner;

        if($is_owner == 'Страхователь'){
            $contract->owner_id = $contract->insurer_id;
        }elseif($is_owner == 'Выгодоприобретатель'){
            $contract->owner_id = $contract->beneficiar_id;
        }else{
            $contract->owner_id = $contract->beneficiar_id;
            //dump('ownerIsCopy: '.$result->id);
            //dd($json);
        }

        $contract->save();

        CalcKasko::setGeneralSubjects($contract);

        return $contract;

    }

    private function setContractDataSchemeGAP($contract, $result, $json, $user)
    {


        $insurance_option = 1;

        $sk_title = '';
        if(isset($json->kaskoSkName)) $sk_title = $json->kaskoSkName;
        if(isset($json->prolongationInsuranceName)) $sk_title = $json->prolongationInsuranceName;


        $kasko_number = '';
        if(isset($json->kaskoNum)) $kasko_number = $json->kaskoNum;
        if(isset($json->prolongationPolicyNum)) $kasko_number = $json->prolongationPolicyNum;

        $kasko_date = null;
        if(isset($json->kaskoStartDate)) $kasko_date = $json->kaskoStartDate;

        $is_auto_credit = 0;


        $contract->data()->update([
            'insurance_option' => $insurance_option,
            'sk_title' => $sk_title,
            'kasko_number' => $kasko_number,
            'kasko_date' => getDateFormat($kasko_date),
            'is_auto_credit' => $is_auto_credit,

        ]);





        //АВТО
        $object_insurer = $contract->object_insurer;
        $auto = $object_insurer->data();


        $mark = VehicleMarks::where('title', $json->carMark)->where('category_id', '3366')->get()->first();
        if($mark){
            $auto->ts_category = $mark->category->id;
            $auto->mark_id = $mark->id;

            $model = VehicleModels::where('title', $json->carModel)->where('mark_id2', $mark->id)->get()->first();
            if($model){
                $auto->model_id = $model->id;
            }
        }




        $color = null;
        if(isset($json->carColor)){
            if($json->carColor == 'Другой') $json->carColor = "Иной";

            $color = VehicleColor::where('title', $json->carColor)->get()->first();
        }

        if($color){
            $auto->color_id = $color->id;
        }

        $auto->car_year = (int)$json->carYear;
        $auto->car_price = getFloatFormat($json->carCost);



        if(isset($json->vin)) $auto->vin = $json->vin;
        if(isset($json->carVin)) $auto->vin = $json->carVin;

        if(isset($json->regNum)) $auto->reg_number = $json->regNum;
        if(isset($json->carRegNum)) $auto->reg_number = $json->carRegNum;

        if(isset($json->ptsDublicat)) $auto->is_duplicate = ($json->ptsDublicat == 'нет')?0:1;
        if(isset($json->carIsPtsOriginal)) $auto->is_duplicate = ($json->carIsPtsOriginal == 1)?0:1;


        $auto->is_credit = $is_auto_credit;

        if(isset($json->ptsNum) && strlen($json->ptsNum) > 0){
            $doc = explode('№', $json->ptsNum);
            $auto->doc_type = 220219;
            $auto->docserie = isset($doc[0])?$doc[0]:'';
            $auto->docnumber = isset($doc[1])?$doc[1]:'';

        }

        if(isset($json->carPtsNum) && strlen($json->carPtsNum) > 0){
            $doc = explode('№', $json->carPtsNum);
            $auto->doc_type = 220219;
            $auto->docserie = isset($doc[0])?$doc[0]:'';
            $auto->docnumber = isset($doc[1])?$doc[1]:'';

        }

        if(isset($json->ptsDate)) $auto->docdate = getDateFormatEn($json->ptsDate);

        $auto->save();

        $object_insurer->title = '';

        if($auto->mark){
            $object_insurer->title .= "{$auto->mark->title} ";
        }
        if($auto->model){
            $object_insurer->title .= "{$auto->model->title} ";
        }
        $object_insurer->title .= "{$auto->car_year}";
        $object_insurer->save();




        $SubjectsData = null;
        //Создаем контрагентов
        if(isset($json->insurant)) $SubjectsData = self::getSubjectsDataScheme1($json->insurant);
        if(isset($json->insurantLastName) || isset($json->insurantJureName)) $SubjectsData = self::getSubjectsDataScheme2((array)$json, 'insurant');

        if(!$SubjectsData) {
            dump($result->id);
            dd($json);
        }

        $insurer = $contract->insurer;
        $insurer->update($SubjectsData['subjects']);
        $info = $insurer->data();
        $info->save();
        $info->update($SubjectsData['info']);



        $is_beneficiary = '';
        if(isset($json->beneficiaryIsCopy)) $is_beneficiary = $json->beneficiaryIsCopy;
        if(isset($json->beneficiary)) $is_beneficiary = $json->beneficiary;

        if($is_beneficiary == 'Страхователь'){
            $contract->beneficiar_id = $contract->insurer_id;
        }else{

            $bankName = '';
            if(isset($json->bankName) && strlen($json->bankName) > 0) $bankName = $json->bankName;
            if(isset($json->creditBank) && strlen($json->creditBank) > 0) $bankName = $json->creditBank;


            if(isset($json->beneficiaryType) && $json->beneficiaryType == 'Банк' && strlen($bankName) > 0){

                $general = $this->getGeneralSubjectsBank($bankName, $user);
                $subject = Subjects::create(['type' => 1, 'user_id' => $user->id]);
                $subject->general_subject_id = $general->id;
                $subject->title = $bankName;
                $subject->save();
                $contract->beneficiar_id = $subject->id;

            }else{



                $SubjectsData = null;
                //Создаем контрагентов
                if(isset($json->beneficiary) && is_object($json->beneficiary)) $SubjectsData = self::getSubjectsDataScheme1($json->beneficiary);

                if(isset($json->beneficiaryLastName) || isset($json->beneficiaryJureName)) $SubjectsData = self::getSubjectsDataScheme2((array)$json, 'beneficiary');



                if($SubjectsData){
                    $beneficiar = Subjects::create(['type' => 0, 'user_id' => $user->id]);
                    $beneficiar->update($SubjectsData['subjects']);
                    $info = $beneficiar->data();
                    $info->save();
                    $info->update($SubjectsData['info']);
                    $contract->beneficiar_id = $beneficiar->id;
                }else{
                    $contract->beneficiar_id = $contract->insurer_id;
                }






            }

        }


        $is_owner = 'Страхователь';
        if(isset($json->ownerIsCopy)) $is_owner = $json->ownerIsCopy;
        if(isset($json->owner)) $is_owner = $json->owner;

        if($is_owner == 'Страхователь'){
            $contract->owner_id = $contract->insurer_id;
        }elseif($is_owner == 'Выгодоприобретатель'){
            $contract->owner_id = $contract->beneficiar_id;
        }else{
            $contract->owner_id = $contract->beneficiar_id;
            //dump('ownerIsCopy: '.$result->id);
            //dd($json);
        }



        $contract->save();


        CalcKasko::setGeneralSubjects($contract);

        return $contract;

    }



    private static function getGeneralSubjectsBank($bank_name, $user)
    {
        $data = new \stdClass();
        $data->title = $bank_name;
        $data->inn = '';
        $data->ogrn = '';

        $hash = GeneralSubjectsInfo::getHash(1, $data);
        $general = GeneralSubjectsSearch::search_hash(1, $hash);
        if(!$general){
            $general = GeneralSubjectsInfo::createGeneralSubjectHash(1, $hash, $user, $data);
            $general->user_id = $user->id;
            $general->person_category_id = 10;
            $general->save();
        }
        return $general;
    }

    private static function getSubjectsDataScheme1($subject)
    {
        $type = 0;
        $title = '';

        $info = [];

        $inn = '';
        $kpp = '';
        $ogrn = '';

        if($subject && isset($subject->lastName) && strlen($subject->lastName) > 0){
            $type = 0;
            $title = "{$subject->lastName} {$subject->firstName} {$subject->middleName}";
            $doc_type = 1165;
            $info = [
                'fio' => $title,
                'is_resident' => 1,
                'sex' => getSexClient($title),
                'birthdate' => getDateFormatEn($subject->birthDate),
                'address_born' => $subject->bornPlace,
                'address_register' => $subject->adrStreet,
                'address_fact' => $subject->adrStreet,
                'citizenship_id' => 51,
                'doc_type' => $doc_type,
                'doc_date' => getDateFormatEn($subject->docDate),
                'doc_serie' => $subject->docSeries,
                'doc_number' => $subject->docNumber,
                'doc_info' => $subject->docPlace,
            ];

        }else{

            $inn = $subject->inn;
            $kpp = $subject->kpp;
            $ogrn = $subject->ogrn;

            $type = 1;
            $title = $subject->companyName;
            $info = [
                'title' => $title,
                'inn' => $subject->inn,
                'kpp' => $subject->kpp,
                'ogrn' => $subject->ogrn,
                'bik' => isset($subject->bik)?$subject->bik:'',
                'rs' => $subject->rsNum,
                'manager_fio' => "{$subject->contLastName} {$subject->contFirstName} {$subject->contMiddleName}",
                'manager_birthdate' => getDateFormatEn($subject->contBirthDate),
                'manager_position' => $subject->contPosition,
                'manager_phone' => $subject->contPhone,
                'manager_email' => $subject->contEmail,

            ];
        }


        $subjects = [
            'type' => $type,
            'title' => $title,
            'citizenship_id' => 51,
            'is_resident' => 1,
            'email' => (isset($subject->email))?$subject->email:$subject->companyEmail,
            'phone' => (isset($subject->phone))?$subject->phone:$subject->companyPhone,
            'inn' => $inn,
            'kpp' => $kpp,
            'ogrn' => $ogrn,
        ];



        $data = [
            'subjects' => $subjects,
            'info' => $info,
        ];

        return $data;
    }


    private static function getSubjectsDataScheme2($subject, $name)
    {
        $type = 0;
        $title = '';

        $info = [];

        $inn = '';
        $kpp = '';
        $ogrn = '';

        if($subject){
            if(isset($subject["{$name}LastName"]) && strlen($subject["{$name}LastName"]) > 0) $type = 0;
            if(isset($subject["{$name}JureName"]) && strlen($subject["{$name}JureName"]) > 0) $type = 1;
        }


        if($type == 0){
            $title = "{$subject["{$name}LastName"]} {$subject["{$name}FirstName"]} {$subject["{$name}MiddleName"]}";
            $doc_type = 1165;
            $info = [
                'fio' => $title,
                'is_resident' => 1,
                'sex' => getSexClient($title),
                'birthdate' => getDateFormatEn($subject["{$name}BirthDate"]),
                'address_born' => $subject["{$name}BirthPlace"],
                'address_register' => $subject["{$name}Street"],
                'address_fact' => $subject["{$name}Street"],
                'citizenship_id' => 51,
                'doc_type' => $doc_type,
                'doc_date' => getDateFormatEn($subject["{$name}DocDate"]),
                'doc_serie' => $subject["{$name}DocSeries"],
                'doc_number' => $subject["{$name}DocNum"],
                'doc_info' => $subject["{$name}DocPlace"],
            ];

        }else{

            $inn = (isset($subject["{$name}JureInn"]))?$subject["{$name}JureInn"]:'';
            $kpp = (isset($subject["{$name}JureKpp"]))?$subject["{$name}JureKpp"]:'';
            $ogrn = (isset($subject["{$name}JureOgrn"]))?$subject["{$name}JureOgrn"]:'';



            $title = $subject["{$name}JureName"];

            if(strlen($inn) > 0){
                $info = [
                    'title' => $title,
                    'inn' => $inn,
                    'kpp' => $kpp,
                    'ogrn' => $ogrn,
                    'bik' => (isset($subject["{$name}JureBik"]))?$subject["{$name}JureBik"]:'',
                    'rs' => (isset($subject["{$name}JureRs"]))?$subject["{$name}JureRs"]:'',
                    'manager_fio' => "{$subject["{$name}JureContactLastName"]} {$subject["{$name}JureContactFirstName"]} {$subject["{$name}JureContactMiddleName"]}",
                    'manager_birthdate' => getDateFormatEn($subject["{$name}JureContactBirthDate"]),
                    'manager_position' => $subject["{$name}JureContactPosition"],
                    'manager_phone' => $subject["{$name}JureContactPhone"],
                    'manager_email' => $subject["{$name}JureContactEmail"],

                ];
            }else{
                $info = [
                    'title' => $title,
                    'inn' => $inn,
                    'kpp' => $kpp,
                    'ogrn' => $ogrn,
                ];
            }


        }


        $subjects = [
            'type' => $type,
            'title' => $title,
            'citizenship_id' => 51,
            'is_resident' => 1,
            'phone' => (isset($subject["{$name}Phone"]))?$subject["{$name}Phone"]:(isset($subject["{$name}JurePhone"])?$subject["{$name}JurePhone"]:''),
            'email' => (isset($subject["{$name}Email"]))?$subject["{$name}Email"]:(isset($subject["{$name}JureEmail"])?$subject["{$name}JureEmail"]:''),
            'inn' => $inn,
            'kpp' => $kpp,
            'ogrn' => $ogrn,
        ];



        $data = [
            'subjects' => $subjects,
            'info' => $info,
        ];



        return $data;
    }


}