<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clients\GeneralSubjects;
use App\Models\Directories\Products;
use App\Processes\Operations\GeneralSubjects\GeneralSubjectsSearch;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/';

    protected $guard = 'client';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm(Request $request)
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectTo);
        }

        return view('client.auth.login');
    }

    public function showRegistrationForm(Request $request)
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectTo);
        }

        return view('client.auth.registration', [
            'products' => Products::all()
        ]);
    }


    public function registration(Request $request)
    {
        $is_registration = GeneralSubjectsSearch::registration_general($request);
        if($is_registration == true){
            return redirect('/login')->with('success', 'На указанный Email отправлен пароль авторизации!');
        }

        return back()->withInput($request->input())->withErrors('Клиент не найден');
    }


    protected function guard()
    {
        return Auth::guard('client');
    }


    public function username()
    {
        return 'email';
    }



    public function change_pass(Request $request)
    {

        $result = new \stdClass();
        $result->state = 0;
        $result->msg = '';

        $client = auth()->guard('client')->user();
        if(\Hash::check($request->password, $client->password)){


            if($request->passwor_new == $request->passwor_new_duble){
                if(strlen($request->passwor_new) >= 6){

                    $client->password = \Hash::make($request->passwor_new);
                    $client->save();
                    $result->state = 1;
                    $result->msg = 'Новый пароль установлен!';

                }else{
                    $result->msg = 'Новый пароль должен быть не короче 6-ти символов';
                }
            }else{
                $result->msg = 'Введите павторно пароль';
            }


        }else{
            $result->msg = 'Введите корректный текущий пароль';
        }


        return response()->json($result);

    }


}
