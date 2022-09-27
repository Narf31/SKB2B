<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Users\Notification\NotificationController;
use App\Models\Acts\ReportAct;
use App\Models\BSO\BsoActs;
use App\Models\BSO\BsoActsItems;
use App\Models\BSO\BsoCarts;
use App\Models\BSO\BsoItem;
use App\Models\BSO\BsoLogs;
use App\Models\Cashbox\Cashbox;
use App\Models\Cashbox\CashboxTransactions;
use App\Models\Clients\GeneralPodftFl;
use App\Models\Clients\GeneralSubjects;
use App\Models\Clients\GeneralSubjectsAddress;
use App\Models\Clients\GeneralSubjectsDocuments;
use App\Models\Clients\GeneralSubjectsFl;
use App\Models\Clients\GeneralSubjectsLogs;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsDocuments;
use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Payments;
use App\Models\Contracts\Subjects;
use App\Models\Directories\BsoSerie;
use App\Models\Directories\HoldKvMatching;
use App\Models\Directories\Products\Data\Kasko\BaseRateKasko;
use App\Models\Directories\Products\Data\LiabilityArbitrationManager;
use App\Models\Directories\Products\ProductsSpecialSsettingsFiles;
use App\Models\Finance\Invoice;
use App\Models\MailsNotification\MailsNotification;
use App\Models\Orders\Pso;
use App\Models\Organizations\Organization;
use App\Models\Reports\ReportOrders;
use App\Models\User;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehicleModelsClassificationKasko;
use App\Processes\Operations\Contracts\Contract\ContractAccept;
use App\Processes\Operations\Contracts\Contract\ContractCancel;
use App\Processes\Operations\Contracts\Contract\ContractMasks;
use App\Processes\Operations\Contracts\ContractOnlineProduct;
use App\Processes\Operations\Contracts\Matchings\MatchingArbitration;
use App\Processes\Operations\Contracts\Matchings\MatchingKasko;
use App\Processes\Operations\Contracts\Payments\PaymentsAccept;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\Contracts\Payments\PaymentsReports;
use App\Processes\Operations\Contracts\Products\CalcArbitration;
use App\Processes\Operations\Contracts\Products\CalcGap;
use App\Processes\Operations\Contracts\Products\CalcKasko;
use App\Processes\Operations\Contracts\Products\CalcMigrants;
use App\Processes\Operations\Contracts\Products\CalcMortgage;
use App\Processes\Operations\Contracts\Products\CalcNSPrisoners;
use App\Processes\Operations\Contracts\Products\CalcOsago;
use App\Processes\Operations\Contracts\Products\CalcVzr;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsInfo;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsUpdateContourPrism;
use App\Processes\Operations\Mails\NotificationMails;
use App\Processes\Operations\Mails\PaymentMails;
use App\Processes\Operations\Mails\UserLoginMails;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsAutomatic;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use App\Processes\Scenaries\Contracts\OnlineContractSave;
use App\Processes\Scenaries\Contracts\Products\Kasko;
use App\Processes\Scenaries\Contracts\Products\NsPrisoners;
use App\Processes\Scenaries\Contracts\Products\Vzr;
use App\Processes\Scenaries\Contracts\Scorings\Defaults\General;
use App\Processes\Scenaries\Contracts\Scorings\MainScorings;
use App\Services\CBR\CBRCurrency;
use App\Services\Integration\TITOld\TITContracts;
use App\Services\Integration\TITOld\TITMarkModels;
use App\Services\Integration\TITOld\TITSend;
use App\Services\Integration\TITOld\TITUser;
use App\Services\Integration\VernaControllers\Auxiliary\Tariff;
use App\Services\Integration\VernaControllers\VernaDirectories;
use App\Services\Integration\VernaControllers\VernaMask;
use App\Services\Integration\VernaControllers\VernaPayment;
use App\Services\Integration\VernaControllers\VernaSubjects;
use App\Services\Integration\VtigerCRM;
use App\Services\PaymentAgent\IntegrationPaymentAgent;
use App\Services\PaymentAgent\KkmServer\KkmServerConnect;
use App\Services\PaymentAgent\OrangeData\KkmOrangeData;
use App\Services\PaymentAgent\SmartFin\SmartFinConnect;
use App\Services\PaymentAgent\YooKassa\KkmYooKassa;
use App\Services\Scorings\AudaTex;
use App\Services\Scorings\AutoCod;
use App\Services\Scorings\CheckPerson;
use App\Services\Scorings\ContourPrism;
use App\Services\Scorings\SpectrumData;
use App\Services\SignSecret\Amicus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CronController extends Controller
{

    public function __construct()
    {
        set_time_limit('3600');
    }

    public function actualsCurrency()
    {
        return response((string)CBRCurrency::updateValue());
    }

    public function sendNotifications()
    {
        $mails = MailsNotification::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-5 min')))->get();
        if (sizeof($mails)) {
            foreach ($mails as $mail) {
                NotificationMails::send($mail->id);
            }
        }

        return $this->sendKKTPayments();

        //return response('', 200);
    }

    public function sendKKTPayments(){

        /*
        $payments = Payments::query()
            ->where('statys_id', 1)
            ->where('is_deleted', 0)
            ->where('payment_number', 1)
            ->where('invoice_payment_date', '>=', date('Y-m-d 00:00:00'))
            ->where('invoice_payment_date', '<=', date('Y-m-d 23:59:59'))
            ->whereNull('kkt_number');

        foreach ($payments->get() as $payment){
            KkmOrangeData::sendKKT($payment);
        }

        */
        return response('', 200);
    }

    public function updateAllUserPass()
    {
        $users = User::query()->where('temp_status_user_id', 0)->get();
        foreach ($users as $user){
            $pass = GeneralSubjectsInfo::createGeneralSubjectPassword(8);
            $user->password = bcrypt(trim($pass));
            $user->save();
            UserLoginMails::send($user, $pass);
        }

        return response('OK', 200);
    }


    public function clearSystem()
    {
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

        \DB::table('products_vzr')->truncate();


        \DB::table('log_events')->truncate();


        \DB::table('object_insurer')->truncate();
        \DB::table('object_insurer_auto')->truncate();
        \DB::table('object_insurer_flats')->truncate();


        \DB::table('contracts_calculations')->truncate();
        \DB::table('contracts_insurer')->truncate();
        \DB::table('contracts_documents')->truncate();
        \DB::table('contracts_logs')->truncate();
        \DB::table('contracts_masks')->truncate();
        \DB::table('contracts_scans')->truncate();
        \DB::table('contracts_scorings')->truncate();

        \DB::table('contracts_supplementary')->truncate();


        \DB::table('products_liability_arbitration_manager')->truncate();
        \DB::table('products_migrants')->truncate();
        \DB::table('products_vzr')->truncate();
        \DB::table('products_ns_prisoners')->truncate();
        \DB::table('products_supplementary_liability_arbitration_manager')->truncate();

        \DB::table('log_events')->truncate();


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

        \DB::table('la_documents')->truncate();
        \DB::table('la_procedures')->truncate();
        \DB::table('contracts_chat')->truncate();

        \DB::table('users_promocode')->truncate();
        \DB::table('mails_notification')->truncate();

        \DB::table('object_equipment')->truncate();

        \DB::table('products_kasko_drive')->truncate();
        \DB::table('products_kasko_standard')->truncate();
        \DB::table('products_liability_arbitration_manager')->truncate();

        \DB::table('products_gap')->truncate();
        \DB::table('products_dgo')->truncate();


        \DB::table('reports_payments')->truncate();


        return true;
    }

    public function getVehicleModels($vehicle_models_sk_code)
    {
        $vehicle_models = VehicleModels::query()
            ->whereIn('isn', function ($query) use ($vehicle_models_sk_code) {
                $query->select(['vehicle_models_classification_kasko.ModelISN'])
                    ->from('vehicle_models_classification_kasko')
                    ->where('vehicle_models_classification_kasko.PARENTCODE', $vehicle_models_sk_code);
            })
            ->get()->first();


        return $vehicle_models;
    }

    public static function DeleteDuplicateItem($tableName, $field)
    {
        $allItems = DB::table($tableName)
            ->select(DB::raw('min(id)'))
            ->groupBy($field)
            ->get();
        $arr = [];
        foreach ($allItems as $item) {
            $item = (array)$item;
            $arr[] = $item['min(id)'];
        }

        $countDeleteDuplicateItems = DB::table($tableName)
            ->whereNotIn('id', $arr)
            ->delete();

        return $countDeleteDuplicateItems;
    }

    public function updateTariffDefault()
    {
        BaseRateKasko::query()->truncate();
        $program_id = 2;
        $product_id = 2;

        foreach (VehicleMarks::where('category_id', 3366)->get() as $marks) {

            $mark_id = $marks->id;
            $model_id = null;


            $base_triff_marks = Tariff::getCarTariff($marks->title, '_PARENT');

            if ($base_triff_marks) {
                $this->setDefBaserate($product_id, $program_id, $mark_id, $model_id, $base_triff_marks[0], $base_triff_marks[1], $base_triff_marks[2]);
            }

            foreach ($marks->models() as $model) {
                $model_id = $model->id;
                $base_triff = Tariff::getCarTariff($marks->title, $model->title);
                if ($base_triff) {
                    $this->setDefBaserate($product_id, $program_id, $mark_id, $model_id, $base_triff_marks[0], $base_triff_marks[1], $base_triff_marks[2]);
                }

            }

        }

        return true;
    }

    public function setDefBaserate($product_id, $program_id, $mark_id, $model_id, $payment_damage, $total, $theft)
    {

        BaseRateKasko::create([
            'program_id' => $program_id,
            'product_id' => $product_id,
            'mark_id' => $mark_id,
            'model_id' => $model_id,
            'year' => null,
            'payment_damage' => (strlen($payment_damage)) ? getFloatFormat($payment_damage) : null,
            'total' => (strlen($total)) ? getFloatFormat($total) : null,
            'theft' => (strlen($theft)) ? getFloatFormat($theft) : null,
        ]);
        return true;
    }



    public function test(Request $request)
    {

        $contract = Contracts::find(9);

        dd(CalcKasko::calcCalculator($contract));


        dd('cron/test');
    }
}


