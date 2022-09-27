<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

	protected function sendLoginResponse(Request $request)
	{
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $msg = 'Ошибка авторизации';

        if($this->guard()->user()){
            if($this->guard()->user()->status_user_id == 0){
                //$this->swapUserSession($this->guard()->user());
                return redirect()->intended($this->redirectPath())->with('login-success', true);
            }else{
                $msg = 'Пользователь заблокирован';
            }
        }


        auth()->logout();

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => $msg,
            ]);
	}

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/');
    }

    /**
     * Swap a user session with a current one
     *
     * @param \App\Models\Users $user
     * @return boolean
     */
    /*
    protected function swapUserSession($user)
    {

        if (!($user instanceof \App\Models\User)) {
            return false;
        }

        $new_session_id = \Session::getId(); //get new session_id after user sign in
        $last_session = \Session::getHandler()->read($user->last_session_id); // retrive last session

        if ($last_session) {
            \Session::getHandler()->destroy($user->last_session_id);
        }

        $user->last_session_id = $new_session_id;
        $user->save();

        return true;
    }
    */
}
