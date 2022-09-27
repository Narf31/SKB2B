<?php

namespace App\Http\Controllers\Orders\Pso;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Orders\Pso;
use App\Models\User;
use App\Processes\Operations\Contracts\Damages\DamagesLogs;
use App\Processes\Operations\Mails\NotificationMails;
use App\Processes\Scenaries\Contracts\Matchings\MatchingsContract;
use Illuminate\Http\Request;

class PsoOrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:orders,pso');
        $this->breadcrumbs[] = [
            'label' => 'Предстраховой осмотр',
            'url' => 'orders/pso'
        ];
    }

    public function index(Request $request)
    {

        return view('orders.pso.index', [
            'subpermissions' => auth()->user()->role->getSubpermissions(72)
        ])->with('breadcrumbs', $this->breadcrumbs);
    }


    public function list_view(Request $request)
    {
        $status = (int)$request->status;
        $data = (object)$request->data;

        $page = (int)$data->PAGE;
        $page_count = (int)$data->pageCount;

        $orders = Pso::getPsos();
        $orders->where('orders.status_id', $status);

        if(isset($data->city_id) && (int)$data->city_id>0){
            $orders->where('orders.city_id', (int)$data->city_id);
        }

        if(isset($data->order_id) && (int)$data->order_id>0){
            $orders->where('orders.id', (int)$data->order_id);
        }

        if(isset($data->date_from) && strlen($data->date_from)>3){
            $orders->where('orders.begin_date', '>=', getDateFormatEn($data->date_from).' 00:00:00');
        }

        if(isset($data->date_from) && strlen($data->date_to)>3){
            $orders->where('orders.begin_date', '<=', getDateFormatEn($data->date_to).' 23:59:59');
        }

        if(isset($data->position_type_id) && (int)$data->position_type_id>-1){
            $orders->where('orders.position_type_id', (int)$data->position_type_id);
        }


        if(isset($data->contract_bso_title) && strlen($data->contract_bso_title)>3){
            $orders->leftJoin('bso_items', 'bso_items.id', '=', 'orders.bso_id');
            $orders->where('bso_items.bso_title', 'like', "%{$data->contract_bso_title}%");
        }

        if(isset($data->contract_insurer) && strlen($data->contract_insurer)>3){
            $orders->where('orders.insurer_title', 'like', "%{$data->contract_insurer}%");
        }

        $orders->select(['orders.*']);


        $result = PaginationHelper::paginate($orders, $page, $page_count);
        $result['html'] = '';
        if($status == 1){

            $result['html'] = view('orders.pso.partials.plans', ['orders' => $result['builder']->get()])->render();

        }else{

            $result['html'] = view('orders.pso.partials.table', ['orders' => $result['builder']->get()])->render();

        }

        return response()->json($result);
    }


    public function people_list(Request $request)
    {
        $order = Pso::getPsoId($request->order_id);


        $users = User::getUserIsRole('is_pso')->select(['users.*'])
            ->leftJoin('points_sale', 'points_sale.id', '=', 'users.point_sale_id')
            ->where('city_id', $order->city_id);


        return view('orders.default.partials.people_pso_list', [
            'order' => $order,
            'users' => $users->get()
        ]);
    }

    public function assign_user(Request $request)
    {

        $order = Pso::find($request->order_id);
        $users = User::find($request->user_id);
        $order->status_id = 2;
        $order->work_user_id = $users->id;
        $order->work_status_id = 0;
        $order->save();

        DamagesLogs::setLogs($order->id, auth()->id(), $order->status_id, Pso::STATYS[$order->status_id], "Назначен - {$users->name}", '', 'bg-yellow');

        return response()->json((object)['state'=> true, 'msg' => '']);
    }


    public function edit($id, Request $request)
    {
        $order = Pso::getPsoId($id);

        $this->breadcrumbs[] = [
            'label' => "# {$order->id} - " . Pso::STATYS[$order->status_id],
        ];

        $subpermissions = auth()->user()->role->getSubpermissions(72, $order->status_id);
        $view = 'view';
        if ($subpermissions && (int)$subpermissions->edit == 1) $view = 'edit';

        return view('orders.pso.edit', [
            'view' => $view,
            'order' => $order
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function get_html_block($id, Request $request)
    {
        $order = Pso::getPsoId($id);
        $subpermissions = auth()->user()->role->getSubpermissions(72, $order->status_id);
        $view = 'view';
        if ($subpermissions && (int)$subpermissions->edit == 1) $view = 'edit';

        return view($request->view, [
            'view' => $view,
            'order' => $order,
            'url_scan' => url("/orders/actions/{$order->id}/scan_damages"),
            'info' => $order->info,
        ]);

    }


    public function work_status($id, Request $request)
    {

        $result = (object)['state'=> true, 'msg' => ''];
        $order = Pso::getPsoId($id);

        $work_status = (int)$request->work_status;
        $work_comments = $request->work_comments;
        $users = auth()->user();
        $work_user_id = $users->id;

        $color = '';
        if($work_status == 1){
            $order->status_id = 1;
            $color = 'bg-red';
        }
        if($work_status == 2){
            $color = 'bg-green';
            if($order->position_type_id != 0){
                $work_status = 3;
            }
        }

        if($work_status == 4){
            $order->status_id = 3;
            $color = 'bg-blue';
            if($order->position_type_id == 1){
                $order->begin_date = getDateTime();
            }

            $email = new \stdClass();
            $email->title = "Согласование ПСО #{$order->id}";
            $email->body = '<a href="'.url("/orders/pso/{$order->id}").'">Ссылка на заявку</a>';
            $email->user_email = 'dbiryukov@titins.ru';
            NotificationMails::sendEmail($email);


        }


        if($work_status == 5){
            $order->status_id = 5;
            $color = 'bg-red';
        }

        if($work_status == 6){
            $order->status_id = 4;
            $color = 'bg-green';
        }

        $order->work_user_id = $work_user_id;
        $order->work_status_id = $work_status;
        $order->save();

        DamagesLogs::setLogs($order->id, $work_user_id, $order->status_id, Pso::STATYS[$order->status_id],
            Pso::WORK_STATYS[$work_status]." - {$users->name} $work_comments", '', $color);

        if($order->status_id == 4 && $order->contract){
            //Переводим договор дальше
            $contract = $order->contract;
            $matching_num = (int)$contract->matching_num;
            if($matching_num == 0) $matching_num = 1;
            if($contract->statys_id != 4){
                $contract->matching_num = $matching_num+1;
                $contract->save();
                MatchingsContract::check($contract);
            }



        }

        return response()->json($result);
    }

}
