<?php

namespace App\Http\Controllers\Directories\Auto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:directories,auto');
        
        $this->breadcrumbs[] = [
            'label' => 'Справочники',
        ];
        
        $this->breadcrumbs[] = [
            'label' => 'Авто',
            'url' => 'directories/auto',
        ];

    }

    public function index(Request $request)
    {
        return view("directories.auto.index", [
            "breadcrumbs" => $this->breadcrumbs,
        ]);
    }

}
