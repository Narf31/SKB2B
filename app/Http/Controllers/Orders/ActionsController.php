<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;


use App\Models\Orders\Damages;
use App\Models\Orders\OrdersChat;
use App\Models\Orders\Pso;
use App\Models\User;
use App\Repositories\FilesRepository;
use App\Models\Settings\PointsSale;
use App\Services\Pushers\PusherRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActionsController extends Controller
{

    protected $filesRepository;
    protected $pusherRepository;

    public function __construct(FilesRepository $filesRepository, PusherRepository $pusherRepository) {

        $this->filesRepository = $filesRepository;
        $this->pusherRepository = $pusherRepository;
    }


    public function get_point_sale(Request $request)
    {

        $point_sale = PointsSale::where('is_actual', 1)
            ->where('city_id', $request->city_id)
            ->orderBy('title', 'asc');

        if($request->type == 1){
            $point_sale->where('is_damages', 1);
        }



        return response()->json($point_sale->get());

    }


    public function scan_damages($order_id, Request $request) {

        Damages::findOrFail($order_id)->scans()->save($this->filesRepository->makeFile($request->file, Damages::getPath($order_id)));

        return response('', 200);
    }

    public function comment_pso($order_id, Request $request) {

        $result = new \stdClass();
        $result->state = 0;
        $result->msg = 'Не удалось сохранить комментарий!';

        $order = Pso::findOrFail($order_id);

        if(isset($order)){
            $order->comment_pso = $request->comment_pso;
            $order->save();
            $result->state = 1;
        }

        return response()->json($result);
    }


    public function setPush($order_id, Request $request)
    {


        $order = Damages::findOrFail($order_id);

        $message = new OrdersChat([
            'sender_id' => auth()->id(),
            'text' => $request->text,
            'date_sent' => Carbon::now(),
            'status' => OrdersChat::STATUS_SENT,
            'is_player' => OrdersChat::EMPLOYEE,
        ]);


        $inspection = $order->inspection;
        if ($inspection && $inspection->processing_user_id > 0) {
            $user = User::find($inspection->processing_user_id);
            if ($user->firebase && $user->is_work == 1) {
                //$fcm = new FireBaseAndroid();
                //$fcm->push("Сообщение", $request->text, $order->id, 2, $user->firebase);
            }
        }


        $order->chats()->save($message);

        //$this->pusherRepository->triggerContractChat($message);

        return response()->json([
            'sender' => $message->sender->name,
            'date' => $message->date_sent->format('d.m.Y H:i'),
            'text' => $message->text,
            'status' => $message->status,
            'is_player' => $message->is_player,
        ]);

    }


    public function read($order_id, Request $request)
    {
        OrdersChat::unread()->where('order_id', $order_id)->where('sender_id', '!=', auth()->id())->update(['status' => OrdersChat::STATUS_RECEIPT, 'date_receipt' => Carbon::now()]);
    }


    public function documents($order_id, Request $request)
    {
        $order = Damages::findOrFail($order_id);
        $file = $order->scans->where('id', $request->id)->last();

        return view('orders.default.partials.documents', [
            "file" => $file,
        ]);

    }


    public function setPso($order_id, Request $request)
    {
        $result = new \stdClass();
        $result->state = false;
        $result->msg = "Ошибка!";
        //Обработка ошибок



        $inspection = Pso::findOrFail($order_id);
        $order = (object)$request->order;
        $inspection->city_id = $order->city_id;
        $inspection->address = $order->address;
        $inspection->latitude = $order->latitude;
        $inspection->longitude = $order->longitude;
        $inspection->begin_date = setDateTimeFormat($order->date." ".$order->time);
        $inspection->phone = $order->phone;
        $inspection->insurer_title = $order->fio;
        $inspection->comments = $inspection->contract->object_insurer->title;
        $inspection->status_id = 1;
        $inspection->save();

        $result->state = true;

        return response()->json($result);
    }



}
