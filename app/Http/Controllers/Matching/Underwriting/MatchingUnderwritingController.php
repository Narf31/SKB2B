<?php

namespace App\Http\Controllers\Matching\Underwriting;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\ContractsLogsPayments;
use App\Models\Contracts\Matching;
use App\Models\Contracts\UnderwritingCheckUserLog;
use App\Models\Directories\FinancialPolicy;
use App\Models\Settings\Notification;
use App\Processes\Operations\Contracts\Contract\ContractMasks;
use App\Processes\Operations\Contracts\Payments\PaymentsFinancialPolicy;
use App\Processes\Operations\Mails\NotificationMails;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use App\Processes\Scenaries\Contracts\Scorings\Defaults\General;
use Illuminate\Http\Request;

class MatchingUnderwritingController extends Controller
{

    public function __construct() {
        $this->middleware('permissions:matching,underwriting');
        $this->breadcrumbs[] = [
            'label' => 'Андеррайтинг',
            'url' => 'matching/underwriting'
        ];
    }

    public function index(Request $request)
    {

        $result = [];
        $result[0] = ['title' => 'В работе', 'count' => Matching::getQuery()->where('type_id', 0)->whereIn('matching.status_id', [0, 1])->count()];
        $result[1] = ['title' => 'Возвращена с доработки', 'count' => Matching::getQuery()->where('type_id', 0)->whereIn('matching.status_id', [3])->count()];


        return view('matching.underwriting.index', [
            'result' => $result,
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function getTable(Request $request)
    {

        $matching = Matching::getQuery()->where('type_id', 0);

        $is_not_work = 0;

        switch ((int)$request->statys) {
            case 0:
                $matching->whereIn('matching.status_id', [0, 1]);
                $is_not_work = 0;
                break;
            case 1:
                $matching->whereIn('matching.status_id', [3]);
                $is_not_work =1;
                break;

        }

        if(isset($request->category_id) && is_array($request->category_id)){
            $matching->whereIn('matching.category_id', $request->category_id);
        }

        $matching->orderByRaw(getOrderByIfSQL("matching.check_user_id=".auth()->id(),'1', '0', 'desc'));
        $matching->orderByRaw(getOrderByIfSQL("matching.check_user_id > 0", '1', '0', 'desc'));
        $matching->orderBy("matching.check_user_id", "desc");
        $matching->orderBy("matching.updated_at", "desc");

        $matching->select(['matching.*']);


        $page = request()->get('PAGE') > 0 ? (int)request()->get('PAGE') : 1;
        $page_count = request()->get('page_count') > 0 ? (int)request()->get('page_count') : 10;
        $result = PaginationHelper::paginate($matching, $page, $page_count);


        NotificationMails::delete(auth()->user()->email,'/matching/underwriting/');

        return [
            'html' => view('matching.underwriting.table', ['matchings' => $result['builder']->get(), 'is_not_work'=>$is_not_work])->render(),
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row'  => $result['max_row'],
            'view_row' => $result['view_row'],
        ];

    }



    public function logCheckUser($id, Request $request)
    {
        $logs = UnderwritingCheckUserLog::where("matching_id", $id)->get();

        return view("matching.underwriting.logs", [
            "logs" => $logs,
        ]);
    }


    public function setCheckUser($id, Request $request)
    {
        $matching = Matching::find($id);


        if($matching->contract){

            if($matching->status_id != 4){
                $matching->check_user_id = auth()->id();
                $matching->check_date = getDateTime();
                $matching->status_id = 1;
                $matching->save();


                ContractsLogs::setContractLogs($matching->contract->id, auth()->id(), $matching->contract->statys_id, 'Отправлен на согласования', 'Взята в работу');

                Notification::setNotificationContract($matching->initiator_user_id, $matching->contract, $matching->insurer_title.' Cогласования - Взята в работу '.$matching->check_user->name);

                UnderwritingCheckUserLog::create([
                    'user_id' => auth()->id(),
                    'matching_id' => $matching->id,
                    'start_date' => getDateTime(),
                ]);

            }
        }






        return response('', 200);
    }

    public function clearCheckUser($id, Request $request)
    {
        $matching = Matching::find($id);
        if($matching->status_id != 4 || $matching->status_id != 5){
            $matching->check_user_id = null;
            $matching->check_date = null;
            $matching->status_id = 0;
            $matching->save();

            UnderwritingCheckUserLog::where("matching_id", $id)->whereNull('end_date')->update([
                'end_date' => getDateTime(),
            ]);
        }


        return response('', 200);
    }


    public function edit($id)
    {
        $matching = Matching::find($id);

        $this->breadcrumbs[] = [
            'label' => \App\Models\Contracts\Matching::CATEGORY[$matching->category_id].' '.$matching->category_title,
        ];

        NotificationMails::delete(auth()->user()->email,'/matching/underwriting/'.$id.'/');


        return view("matching.underwriting.edit", [
            "matching" => $matching,
        ])->with('breadcrumbs', $this->breadcrumbs);
    }


    public function setStatus($id, Request $request)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = "Ошибка акцепта!";

        $matching = Matching::find($id);


        if($matching->status_id == 4){
            $result->msg = "Договор согласован изменения запрещены";
            return $result;
        }


        $matching->comments = $request->comments;
        $matching->status_id = (int)$request->state;
        $matching->save();

        if($matching->contract)
        {

            $contract_log = 'Cогласования - '.Matching::STATYS[$matching->status_id];
            $notification = 'Cогласования - '.$matching->insurer_title.' - '.Matching::STATYS[$matching->status_id].' '.$matching->check_user->name;
            if($matching->supplementary){

                $contract_log = 'Cогласования - Доп. соглашение '.$matching->supplementary->title.' - '.Matching::STATYS[$matching->status_id];
                $notification = 'Cогласования - Доп. соглашение '.$matching->supplementary->title.' - '.$matching->insurer_title.' - '.Matching::STATYS[$matching->status_id].' '.$matching->check_user->name;

            }

            ContractsLogs::setContractLogs($matching->contract->id, auth()->id(), $matching->contract->statys_id, $contract_log);
            Notification::setNotificationContract($matching->initiator_user_id, $matching->contract, $notification);

            if($matching->initiator_user && (int)$matching->initiator_user->is_notification == 1){
                $mail_template = 'emails.notification.notification';
                $mail_title = 'Согласование договора';
                $mail_url = url("/contracts/online/{$matching->contract_id}");
                $mail_body = $notification. '. Детали смотрите по ссылке <a href="'.url($mail_url).'" target="_blank">'.url($mail_url).'</a>';
                NotificationMails::create($matching->initiator_user->email, $mail_template, $mail_title, $mail_body, $mail_url);
            }

            if($matching->status_id == 4){
                $contract = $matching->contract;
                $matching_num = (int)$contract->matching_num;
                if($matching_num == 0) $matching_num = 1;
                $contract->matching_num = $matching_num+1;
                $contract->save();
                MatchingsContract::check($contract);
            }

        }

        $result->state = true;
        $result->msg = "";

        return response()->json($result);
    }


    public function setTariff($id, Request $request)
    {
        $matching = Matching::find($id);

        $result = new \stdClass();
        $result->msg = "Класс контроллер не найден!";
        $result->state = false;

        if($matching->status_id == 4){
            $result->msg = "Договор согласован изменения запрещены";
            return $result;
        }

        $is_contract = 0;
        if($matching->contract){
            $contract = $matching->contract;
            if(isset($request->contract)){
                $data = $request->contract;
                if(isset($data['financial_policy_manually_set'])){

                    $contract->financial_policy_manually_set = $data['financial_policy_manually_set'];
                    $contract->financial_policy_kv_bordereau = getFloatFormat($data['financial_policy_kv_bordereau']);
                    $contract->financial_policy_kv_dvoy = getFloatFormat($data['financial_policy_kv_dvoy']);
                    $contract->financial_policy_kv_parent = getFloatFormat($data['financial_policy_kv_parent']);

                    $contract->save();
                    $is_contract = 1;
                }else{

                    $contract->financial_policy_id = $data['financial_policy_id'];
                    $contract->financial_policy_manually_set = 0;

                    $contract->save();
                    $is_contract = 1;
                }
            }

            $tariff = (array)$request->group;

            $result->state = MatchingsContract::editTariff($contract, $tariff, $is_contract);
            if($result->state == true) {
                ContractsLogs::setContractLogs($contract->id, auth()->id(), $contract->statys_id, 'Согласования', 'Изменения тарифа');
                ContractsLogsPayments::setContractLogsPayments($contract->id, auth()->id(), getFloatFormat($contract->payment_total), 'Изменения тарифа');

                ContractMasks::contract($contract);

            }
        }


        return response()->json($result);
    }


    public function refreshScoring($id, Request $request)
    {
        $matching = Matching::find($id);
        if($matching->contract){
            $contract = $matching->contract;

            $contract->scorings()->update(['state_id' => 0, 'is_actual' => 0]);

            General::checkSpectrumData($contract);


        }

        return response('', 200);
    }


}
