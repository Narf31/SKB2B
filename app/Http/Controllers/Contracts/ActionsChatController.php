<?php

namespace App\Http\Controllers\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Contracts\Contracts;
use App\Models\Contracts\ContractsChat;
use App\Models\Settings\Notification;
use App\Models\User;
use App\Services\Pushers\PusherRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\FilesRepository;

class ActionsChatController extends Controller
{

    protected $filesRepository;

    protected $pusherRepository;

    public function __construct(PusherRepository $pusherRepository, FilesRepository $filesRepository)
    {
        $this->pusherRepository = $pusherRepository;
        $this->filesRepository = $filesRepository;
    }

    public function setPush($contract_id, $type, Request $request)
    {

        $contract = Contracts::findOrFail($contract_id);

        $message = new ContractsChat([
            'type_id' => $type,
            'sender_id' => auth()->id(),
            'text' => $request->text,
            'date_sent' => Carbon::now(),
            'status' => ContractsChat::STATUS_SENT,
            'is_player' => ContractsChat::EMPLOYEE,
        ]);

        $contract->chats()->save($message);

        $res = $this->pusherRepository->triggerContractChat($message, $type);

        if($contract->agent_id != auth()->id()){
            //Notification::setNotificationContractsChat($contract->agent_id, $contract);
        }


        return response()->json([
            'type_id' => $type,
            'sender' => $message->sender->name,
            'date' => $message->date_sent->format('d.m.Y H:i'),
            'text' => $message->text,
            'status' => $message->status,
            'is_player' => $message->is_player,
            'is_file' => 0,
        ]);

    }


    public function setNotes($contract_id, $type, Request $request)
    {
        $contract = Contracts::findOrFail($contract_id);

        $message = new ContractsChat([
            'type_id' => 3,
            'sender_id' => auth()->id(),
            'text' => htmlspecialchars($request->text, ENT_QUOTES),
            'date_sent' => Carbon::now(),
            'status' => ContractsChat::STATUS_SENT,
            'is_player' => ContractsChat::EMPLOYEE,
            'is_file' => 0,
        ]);

        $contract->chats()->save($message);

        return response()->json([
            'type_id' => 3,
            'sender' => $message->sender->name,
            'date' => $message->date_sent->format('d.m.Y H:i'),
            'text' => str_replace("\n", '<br/>', htmlspecialchars_decode($message->text, ENT_NOQUOTES)),
            'status' => $message->status,
            'is_player' => $message->is_player,
            'is_file' => $message->is_file,
        ]);
    }



    public function read($contract_id, $type, Request $request)
    {
        ContractsChat::unread()->where('contract_id', $contract_id)->where('type_id', $type)->where('sender_id', '!=', auth()->id())
            ->update(['status' => ContractsChat::STATUS_RECEIPT, 'date_receipt' => Carbon::now(), 'receipt_id' => auth()->id()]);
    }


    public function documents($contract_id, $type, Request $request)
    {
        $contract = Contracts::findOrFail($contract_id);
        $file = $contract->scans->where('id', $request->id)->last();

        return view('contracts.chat.documents', [
            "file" => $file,
        ]);

    }


    public function setDocuments($contract_id, $type, Request $request)
    {
        $contract = Contracts::findOrFail($contract_id);
        $files = $request->file("file");
        $type_chat = $type;
        if(isset($request->type_chat)){
            $type_chat = $request->type_chat;
        }

        foreach ($files as $key => $file) {


            $message = new ContractsChat([
                'type_id' => $type_chat,
                'sender_id' => auth()->id(),
                'text' => '',
                'date_sent' => Carbon::now(),
                'status' => ContractsChat::STATUS_SENT,
                'is_player' => ContractsChat::EMPLOYEE,
                'is_file' => 1,
                'file_id' => $this->filesRepository->makeFile($file, ContractsChat::FILES_DOC . "/$contract_id/")->id,
            ]);

            $contract->chats()->save($message);
            $this->pusherRepository->triggerContractChat($message, $type_chat);

        }
        if($message){
            return response()->json([
                'type_id' => $message->type_id,
                'sender' => $message->sender->name,
                'date' => $message->date_sent->format('d.m.Y H:i'),
                'text' => '',
                'status' => $message->status,
                'is_player' => $message->is_player,
                'is_file' => $message->is_file,
                'file' => $message->getFileArray(),
            ]);
        }

        return response('', 200);

    }


}
