<?php

namespace App\Http\Controllers\Directories\Products;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsInfo;
use Illuminate\Http\Request;
use Auth;

class ProductsInfoController extends Controller
{


    public function __construct()
    {
        $this->middleware('permissions:directories,products');
        $this->breadcrumbs[] = [
            'label' => 'Продукты',
            'url' => 'directories/products'
        ];



    }

    public function index($product_id)
    {

        $product = Products::findOrFail($product_id);
        $this->breadcrumbs[] = [
            'label' => $product->title,
            'url' => "{$product->id}/edit"
        ];

        $this->breadcrumbs[] = [
            'label' => 'Инструкция',
        ];

        return view('directories.products.info.index', [
            "breadcrumbs" => $this->breadcrumbs,
            "product" => $product,
        ]);

    }

    public function edit($product_id, $type_id, $id)
    {
        $product = Products::findOrFail($product_id);

        if($id == 0){
            $info = new ProductsInfo();
            $info->type_id = $type_id;
        }else{
            $info = ProductsInfo::findOrFail($id);
        }

        return view('directories.products.info.edit', [
            "info" => $info,
            "product" => $product,
            "product_id" => $product_id,
            "type_id" => $type_id,
            "id" => $id,
        ]);


    }

    public function save($product_id, $type_id, $id, Request $request)
    {
        if($id == 0){
            $info = new ProductsInfo();
            $info->type_id = $type_id;
            $info->product_id = $product_id;
        }else{
            $info = ProductsInfo::findOrFail($id);
        }

        $info->user_id = auth()->id();
        $info->title = $request->title;
        $info->info_text = $request->info_text;
        $info->save();

        return parentReload();
    }

    public function sort(Request $request){
        foreach($request->infodata as $sort => $info_id){
            $info = ProductsInfo::findOrFail($info_id);
            $info->sort = $sort;
            $info->save();
        }
        return response('', 200);
    }


    public function destroy($product_id, $type_id, $id)
    {
        ProductsInfo::findOrFail($id)->delete();
        return response('', 200);
    }


}
