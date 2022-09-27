<?php

namespace App\Http\Controllers\Matching\SecurityService;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Models\Contracts\ContractsLogs;
use App\Models\Contracts\Matching;
use App\Models\Contracts\UnderwritingCheckUserLog;
use App\Models\Settings\Notification;
use App\Processes\Operations\Mails\NotificationMails;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use Illuminate\Http\Request;

class MatchingSecurityServiceController extends Controller
{

    public function __construct() {
        $this->middleware('permissions:matching,security_service');
        $this->breadcrumbs[] = [
            'label' => 'Служба безопасности',
            'url' => 'matching/security-service'
        ];
    }

    public function index(Request $request)
    {

        $result = [];
        $result[0] = ['title' => 'В работе', 'count' => Matching::getQuery()->where('type_id', 1)->whereIn('matching.status_id', [0, 1])->count()];
        $result[1] = ['title' => 'Возвращена с доработки', 'count' => Matching::getQuery()->where('type_id', 1)->whereIn('matching.status_id', [3])->count()];


        return view('matching.security_service.index', [
            'result' => $result,
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function getTable(Request $request)
    {

        $matching = Matching::getQuery()->where('type_id', 1);

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


        return [
            'html' => view('matching.security_service.table', ['matchings' => $result['builder']->get(), 'is_not_work'=>$is_not_work])->render(),
            'page_max' => $result['page_max'],
            'page_sel' => $result['page_sel'],
            'max_row'  => $result['max_row'],
            'view_row' => $result['view_row'],
        ];

    }



    public function logCheckUser($id, Request $request)
    {
        $logs = UnderwritingCheckUserLog::where("matching_id", $id)->get();

        return view("matching.security_service.logs", [
            "logs" => $logs,
        ]);
    }


    public function setCheckUser($id, Request $request)
    {
        $matching = Matching::find($id);
        $matching->check_user_id = auth()->id();
        $matching->check_date = getDateTime();
        $matching->status_id = 1;
        $matching->save();

        if($matching->contract){
            ContractsLogs::setContractLogs($matching->contract->id, auth()->id(), $matching->contract->statys_id, 'Отправлен на согласования', 'Взята в работу');

            Notification::setNotificationContract($matching->initiator_user_id, $matching->contract, $matching->insurer_title.' Cогласования - Взята в работу '.$matching->check_user->name);

        }


        UnderwritingCheckUserLog::create([
            'user_id' => auth()->id(),
            'matching_id' => $matching->id,
            'start_date' => getDateTime(),
        ]);



        return response('', 200);
    }

    public function clearCheckUser($id, Request $request)
    {
        $matching = Matching::find($id);
        $matching->check_user_id = null;
        $matching->check_date = null;
        $matching->status_id = 0;
        $matching->save();

        UnderwritingCheckUserLog::where("matching_id", $id)->whereNull('end_date')->update([
            'end_date' => getDateTime(),
        ]);

        return response('', 200);
    }


    public function edit($id)
    {
        $matching = Matching::find($id);

        $this->breadcrumbs[] = [
            'label' => \App\Models\Contracts\Matching::CATEGORY[$matching->category_id].' '.$matching->category_title,
        ];

        NotificationMails::delete(auth()->user()->email,'/matching/security-service/'.$id.'/');

        return view("matching.security_service.edit", [
            "matching" => $matching,
        ])->with('breadcrumbs', $this->breadcrumbs);
    }


    public function setStatus($id, Request $request)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = "Ошибка акцепта!";

        $matching = Matching::find($id);
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


        UnderwritingCheckUserLog::where("matching_id", $id)->whereNull('end_date')->update([
            'end_date' => getDateTime(),
        ]);


        return response()->json($result);
    }





}
