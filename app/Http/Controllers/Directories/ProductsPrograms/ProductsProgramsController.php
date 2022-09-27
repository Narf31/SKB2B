<?php

namespace App\Http\Controllers\Directories\ProductsPrograms;

use App\Http\Controllers\Controller;
use App\Models\Directories\InsuranceCompaniesPrograms;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsPrograms;
use App\Models\Log\LogEvents;
use Illuminate\Http\Request;
use Auth;

class ProductsProgramsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:directories,products');
    }

    public function index($id, $program)
    {
        if ((int)$program > 0) {
            $programs = ProductsPrograms::find($program);
        } else {
            $programs = new ProductsPrograms();
            $programs->product_id = $id;
        }


        return view('directories.products.programs.index', [
            'programs' => $programs,
            'product_id' => $id,
            'product' => Products::find($id),
        ]);
    }


    public function save($id, $program, Request $request)
    {

        if ((int)$program > 0) {
            $programs = ProductsPrograms::find($program);
        } else {
            $programs = new ProductsPrograms();
            $programs->product_id = $id;
        }

        $product = Products::find($id);

         if(strlen($request->title) > 0){
             $programs->title = $request->title;
         }else{
             $programs->title = ProductsPrograms::SLUG[$product->slug][$request->slug];
         }




        $programs->is_actual = (int)$request->is_actual;
        $programs->slug = $request->slug;
        $programs->description = $request->description;
        $programs->save();

        return parentReload();

    }

    public function destroy($id, $program)
    {
        ProductsPrograms::findOrFail($program)->delete();
        return response('', 200);
    }



}
