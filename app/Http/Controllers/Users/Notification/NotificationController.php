<?php

namespace App\Http\Controllers\Users\Notification;

use App\Http\Controllers\Controller;


class NotificationController extends Controller{



    public function index(){


        return view('users.notifications.index');
    }


    public function get_table(){

        $this->validate(request(), [
            'is_read' => 'integer|in:1,0'
        ]);

        $notifications = auth()->user()->notifications()
            ->where('user_id', auth()->id());

        if(request()->has('is_read')){
            $notifications->where('is_read', (int)request('is_read'));
        }


        $notifications = $notifications->orderBy('create_date', 'desc')->limit(100)->get();

        return view('users.notifications.table',[
            'notifications' => $notifications

        ]);

    }






    public function read($id){

        $notification = auth()->user()->getNotifications()->where('id', (int)$id)->firstOrFail();

        $notification->update(['is_read' => 1]);

        return response()->json(['status' => 'ok']);

    }

}