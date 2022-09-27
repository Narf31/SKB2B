<?php

namespace App\Http\Controllers\Orders\Damages;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\BSO\BsoItem;
use App\Models\Orders\DamageOrderPayments;
use App\Models\Orders\Damages;
use App\Models\User;
use App\Processes\Scenaries\Contracts\Damages\Damage;
use Illuminate\Http\Request;

class DamagesOrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:orders,damages');
        $this->breadcrumbs[] = [
            'label' => 'Убытки',
            'url' => 'orders/damages'
        ];
    }

    public function index(Request $request)
    {


        return view('orders.damages.index', [
            'subpermissions' => auth()->user()->role->getSubpermissions(73)

        ])->with('breadcrumbs', $this->breadcrumbs);
    }

    public function create(Request $request)
    {
        return view('orders.damages.create', [

        ]);
    }

    public function store(Request $request)
    {

        $bso_id = (int)$request->bso_id;
        $comments = $request->comments;
        $bso = BsoItem::find($bso_id);

        $damage = Damage::create($bso, auth()->id(), 'Сотрудник компании', $comments);

        return parentRedirect(url("/orders/damages/{$damage->id}"));

    }

    public function list_view(Request $request)
    {
        $status = (int)$request->status;
        $data = (object)$request->data;

        $page = (int)$data->PAGE;
        $page_count = (int)$data->pageCount;

        $damages = Damages::getDamages();
        $damages->where('orders.status_id', $status);

        if(isset($data->city_id) && (int)$data->city_id>0){
            $damages->where('orders.city_id', (int)$data->city_id);
        }

        if(isset($data->damage_id) && (int)$data->damage_id>0){
            $damages->where('orders.id', (int)$data->damage_id);
        }

        if(isset($data->date_from) && strlen($data->date_from)>3){
            $damages->where('orders.begin_date', '>=', getDateFormatEn($data->date_from).' 00:00:00');
        }

        if(isset($data->date_from) && strlen($data->date_to)>3){
            $damages->where('orders.begin_date', '<=', getDateFormatEn($data->date_to).' 23:59:59');
        }

        if(isset($data->position_type_id) && (int)$data->position_type_id>-1){
            $damages->where('orders.position_type_id', (int)$data->position_type_id);
        }


        if(isset($data->contract_bso_title) && strlen($data->contract_bso_title)>3){
            $damages->leftJoin('bso_items', 'bso_items.id', '=', 'orders.bso_id');
            $damages->where('bso_items.bso_title', 'like', "%{$data->contract_bso_title}%");
        }

        if(isset($data->contract_insurer) && strlen($data->contract_insurer)>3){
            $damages->leftJoin('contracts', 'contracts.id', '=', 'orders.contract_id');
            $damages->leftJoin('subjects', 'subjects.id', '=', 'contracts.insurer_id');
            $damages->where('subjects.title', 'like', "%{$data->contract_insurer}%");
        }

        $damages->select(['orders.*']);

        $result = PaginationHelper::paginate($damages, $page, $page_count);
        $result['html'] = '';
        if($status == 1){

            $result['html'] = view('orders.damages.partials.plans', ['damages' => $result['builder']->get()])->render();

        }else{

            $result['html'] = view('orders.damages.partials.table', ['damages' => $result['builder']->get()])->render();

        }

        return response()->json($result);
    }

    public function people_list(Request $request)
    {
        $damage = Damages::getDamagesId($request->order_id);


        $raw = \DB::raw("( 6371 * 2 * ASIN(SQRT(
                          POWER(SIN((`users`.`latitude` - ABS($damage->latitude)) * PI()/180 / 2), 2) +
                          COS(`users`.`latitude` * PI()/180) *
                          COS(ABS($damage->latitude) * PI()/180)  *
                          POWER(SIN((`users`.`longitude` - $damage->longitude) * PI()/180 / 2), 2)
                        ))
                    ) as distance");

        $distance = 70;

        $users = User::getUserIsRole('is_damage')->select([
                'users.name as name',
                'users.id as id' ,
                'users.work_phone as phone' ,
                'organizations.title as organizations_title',
                'users.latitude as latitude',
                'users.longitude as longitude',
                $raw
            ])
            ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
            ->where('users.is_work', '=', 1)
            ->orderBy('distance', 'ASC')
            ->having('distance', '<=', $distance)
            ->get();


        return view('orders.default.partials.people_list', [
            'damage' => $damage,
            'users' => $users
        ]);
    }

    public function assign_user(Request $request)
    {
        return response()->json(Damage::assign($request->order_id, auth()->id(), $request->user_id));
    }



    public function edit($id, Request $request)
    {
        $damage = Damages::getDamagesId($id);

        $this->breadcrumbs[] = [
            'label' => "# {$damage->id} - " . Damages::STATYS[$damage->status_id],
        ];

        $subpermissions = auth()->user()->role->getSubpermissions(73, $damage->status_id);
        $view = 'view';
        if ((int)$subpermissions->edit == 1) $view = 'edit';

        return view('orders.damages.edit', [
            'view' => $view,
            'damage' => $damage
        ])->with('breadcrumbs', $this->breadcrumbs);

    }

    public function get_html_block($id, Request $request)
    {
        $damage = Damages::getDamagesId($id);
        $subpermissions = auth()->user()->role->getSubpermissions(73, $damage->status_id);
        $view = 'view';
        if ((int)$subpermissions->edit == 1) $view = 'edit';

        return view($request->view, [
            'view' => $view,
            'damage' => $damage,
            'order' => $damage,
            'url_scan' => url("/orders/actions/{$damage->id}/scan_damages"),
            'info' => $damage->info,
        ]);

    }



    public function save($id, Request $request)
    {
        return response()->json(Damage::save($id, auth()->id(), (int)$request->status, (object)$request->order));
    }

    public function save_status_payment($id, Request $request)
    {
        return response()->json(Damage::save_status_payment($id, auth()->id(), (int)$request->status_payments_id, $request->payments_comments));
    }

    public function work_status($id, Request $request)
    {
        return response()->json(Damage::save_work_status($id, auth()->id(), (int)$request->work_status, $request->work_comments));
    }




    public function payment_edit($id, $payment_id, Request $request)
    {
        $damage = Damages::getDamagesId($id);
        if((int)$payment_id == 0){
            $payment = new DamageOrderPayments();
        }else{
            $payment = DamageOrderPayments::find($payment_id);
        }


        return view('orders.damages.partials.payment', [
            'payment' => $payment,
            'payment_id' => (int)$payment_id,
            'damage' => $damage,
        ]);
    }


    public function payment_save($id, $payment_id, Request $request)
    {
        $damage = Damages::getDamagesId($id);
        if((int)$payment_id == 0){
            $payment = new DamageOrderPayments();
            $payment->order_id = $damage->id;
        }else{
            $payment = DamageOrderPayments::find($payment_id);
        }

        $payment->payment_total = getFloatFormat($request->payment_total);
        $payment->payment_data = getDateFormatEn($request->payment_data);
        $payment->comments = $request->comments;
        $payment->save();

        Damage::refresh_payment_total($id);

        return parentReloadTab();

    }

    public function payment_delete($id, $payment_id, Request $request)
    {
        DamageOrderPayments::find($payment_id)->delete();
        Damage::refresh_payment_total($id);
        return response('', 200);
    }


}
