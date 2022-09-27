<?php

namespace App\Http\Controllers\Client\Сontracts;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsCategory;
use Illuminate\Http\Request;

class СontractsClientController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {

        //dd(auth()->guard('client')->user());

        $products = Products::where('is_online', 1)->where('is_actual', 1);
        $categories = ProductsCategory::whereIn('id', $products->distinct('category_id')->pluck('category_id')->toArray())->orderBy('sort', 'asc')->get();

        return view('client.contracts.index', [
            'categories' => $categories
        ]);

    }


    public function contracts(Request $request)
    {

        if(!auth()->guard('client')->check())
        {
            return redirect(urlClient("/login"));
        }

        $client = auth()->guard('client')->user();
        $products = Products::where('is_online', 1)->where('is_actual', 1)->get();


        return view('client.contracts.list', [
            'contracts' => $client->contracts()->get(),
            'products' => $products
        ]);

    }



}
