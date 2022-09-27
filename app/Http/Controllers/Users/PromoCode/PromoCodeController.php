<?php

namespace App\Http\Controllers\Users\PromoCode;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Users\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller{


    public function __construct() {

        $this->middleware('permissions:users,promocode');

        $this->breadcrumbs[] = [
            'label' => 'Промокод',
            'url' => 'users/promocode',
        ];
    }

    public function index(){

        $users = User::getALLUserWhere();
        $users->whereIn('users.id', function($query)
        {
            $query->select(\DB::raw('distinct users_promocode.user_id'))
                ->from('users_promocode');

        });


        return view('users.promocode.index', [
            'users' => $users->get()
        ]);
    }

    public function create(Request $request){



        return view('users.promocode.create', [
            'users' => User::getALLUser(),
            'user_id' => $request->user
        ]);

    }

    public function view($user_id, Request $request){

        $user = User::getUserId($user_id);
        $codes = PromoCode::where("user_id", $user_id)->get();


        return view('users.promocode.view', [
            'user' => $user,
            'codes' => $codes
        ]);

    }

    public function save(Request $request){

        $user_id = (int)$request->user_id;
        $count_promocode = (int)getFloatFormat($request->count_promocode);
        $valid_date = getDateFormatEn($request->valid_date);

        while($count_promocode--){
            $code = PromoCode::generateCode();
            PromoCode::create([
                'user_id' => $user_id,
                'is_actual' => 1,
                'valid_date' => $valid_date,
                'title' => $code,
            ]);
        }

        return parentReload();
    }


}