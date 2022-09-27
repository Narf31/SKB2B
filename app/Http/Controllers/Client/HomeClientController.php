<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeClientController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {

        if(auth()->guard('client')->check()){
            return redirect(urlClient("/profile"));
        }

        return redirect(urlClient("/login"));


    }


    public function profile(Request $request)
    {

        if(!auth()->guard('client')->check()){
            return redirect(urlClient("/login"));
        }

        $client = auth()->guard('client')->user();


        return view('client.profile.index', [
            'client' => $client,
        ]);


    }


    public function showContent(Request $request)
    {
        if(!auth()->guard('client')->check()){
            return redirect(urlClient("/login"));
        }

        $client = auth()->guard('client')->user();

        return view($request->view_content, [
            'client' => $client,
        ]);

    }






}
