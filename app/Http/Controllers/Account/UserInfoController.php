<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Settings\Notification;
use App\Models\User;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Hash;

class UserInfoController extends Controller {

	public function __construct() {

	}

	public function setTextSize(Request $request) {

		auth()->user()->text_size = $request->text_size;
		auth()->user()->save();

		return json_encode([
			'state' => 1
		]);
	}

    public function getPassword(Request $request) {

        return view('auth.passwords.reset', [
        ]);

    }


    public function setPassword(Request $request) {

        $result = new \stdClass();
        $result->state = 0;
        $result->msg = '';

        $user = auth()->user();
        if(Hash::check($request->password, $user->password)){

            if(strlen($request->new_password) >= 6){

                $user->password = Hash::make($request->new_password);
                $user->save();
                $result->state = 1;

            }else{
                $result->msg = 'Новый пароль должен быть не короче 6-ти символов';
            }

        }else{
            $result->msg = 'Введите корректный текущий пароль';
        }


        return response()->json($result);
    }




    public function getNotification($id)
    {
        $notification = Notification::where("user_id", auth()->id())->where('id', $id)->get()->first();
        $notification->is_read = 1;
        $notification->save();
        return redirect(url($notification->url));
    }

    public function clearNotification()
    {
        auth()->user()->getNotifications()->update(['is_read'=>1]);
        return response('200', 200);
    }




}
