<?php

namespace App\Http\Controllers;

class HomeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {


        if(auth()->user()->department && strlen(auth()->user()->department->page_enthy) > 3){
            return redirect(auth()->user()->department->page_enthy);
        }

        return view('home', [
        ]);
    }

}
