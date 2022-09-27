<?php

namespace App\Http\Controllers\Directories\Products\Program\Mortgage;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use Illuminate\Http\Request;
use App\Repositories\FilesRepository;

class ProgramMortgageController extends Controller
{
    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->filesRepository = $filesRepository;

        $this->middleware('permissions:directories,products');
        $this->breadcrumbs[] = [
            'label' => 'Продукты',
            'url' => 'directories/products'
        ];

    }


    public function getFormHtml($product_id, Request $request)
    {

        $product = Products::findOrFail($product_id);

        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->get()->first();
        if(!$spec){
            $spec = Products\ProductsSpecialSsettings::create(['product_id'=>$product_id, 'program_id'=>0]);
        }

        return view("directories.products.special_settings.program.mortgage.form.{$request->view}", [
            "product" => $product,
            'spec' => $spec,
        ]);
    }


    public function getBaserateLife($product_id, $baserate_id, Request $request)
    {

        $product = Products::findOrFail($product_id);

        if((int)$baserate_id > 0){
            $baserate = Products\Data\Mortgage\BaseRateLife::findOrFail($baserate_id);
        }else{
            $baserate = new Products\Data\Mortgage\BaseRateLife();
        }

        return view("directories.products.special_settings.program.mortgage.form.edit.baserate-life", [
            "product" => $product,
            "product_id" => $product_id,
            'baserate_id' => $baserate_id,
            'baserate' => $baserate,
        ]);


    }

    public function saveBaserateLife($product_id, $baserate_id, Request $request)
    {
        if((int)$baserate_id > 0){
            $baserate = Products\Data\Mortgage\BaseRateLife::findOrFail($baserate_id);
        }else{
            $baserate = new Products\Data\Mortgage\BaseRateLife();
            $baserate->product_id = $product_id;
        }

        $baserate->tarife_man = (strlen($request->tarife_man) > 0) ? getFloatFormat($request->tarife_man) : null;
        $baserate->tarife_woman = (strlen($request->tarife_woman) > 0) ? getFloatFormat($request->tarife_woman) : null;
        $baserate->age_from = (strlen($request->age_from) > 0) ? (int)$request->age_from : null;
        $baserate->age_to = (strlen($request->age_to) > 0) ? (int)$request->age_to : null;
        $baserate->save();

        return parentReloadTab();
    }


    public function deleteBaserateLife($product_id, $baserate_id, Request $request)
    {
        Products\Data\Mortgage\BaseRateLife::findOrFail($baserate_id)->delete();
        return parentReloadTab();
    }


    public function getBaserateProperty($product_id, $baserate_id, Request $request)
    {

        $product = Products::findOrFail($product_id);

        if((int)$baserate_id > 0){
            $baserate = Products\Data\Mortgage\BaseRateProperty::findOrFail($baserate_id);
        }else{
            $baserate = new Products\Data\Mortgage\BaseRateProperty();
        }

        return view("directories.products.special_settings.program.mortgage.form.edit.baserate-property", [
            "product" => $product,
            "product_id" => $product_id,
            'baserate_id' => $baserate_id,
            'baserate' => $baserate,
        ]);

    }

    public function saveBaserateProperty($product_id, $baserate_id, Request $request)
    {
        if((int)$baserate_id > 0){
            $baserate = Products\Data\Mortgage\BaseRateProperty::findOrFail($baserate_id);
        }else{
            $baserate = new Products\Data\Mortgage\BaseRateProperty();
            $baserate->product_id = $product_id;
        }

        $baserate->tarife = (strlen($request->tarife) > 0) ? getFloatFormat($request->tarife) : null;
        $baserate->class_realty = (int)$request->class_realty;
        $baserate->save();

        return parentReloadTab();
    }

    public function deleteBaserateProperty($product_id, $baserate_id, Request $request)
    {
        Products\Data\Mortgage\BaseRateProperty::findOrFail($baserate_id)->delete();
        return parentReloadTab();
    }


    public function getBaserateTitle($product_id, $baserate_id, Request $request)
    {

        $product = Products::findOrFail($product_id);

        if((int)$baserate_id > 0){
            $baserate = Products\Data\Mortgage\BaseRateTitle::findOrFail($baserate_id);
        }else{
            $baserate = new Products\Data\Mortgage\BaseRateTitle();
        }

        return view("directories.products.special_settings.program.mortgage.form.edit.baserate-title", [
            "product" => $product,
            "product_id" => $product_id,
            'baserate_id' => $baserate_id,
            'baserate' => $baserate,
        ]);

    }

    public function saveBaserateTitle($product_id, $baserate_id, Request $request)
    {
        if((int)$baserate_id > 0){
            $baserate = Products\Data\Mortgage\BaseRateTitle::findOrFail($baserate_id);
        }else{
            $baserate = new Products\Data\Mortgage\BaseRateTitle();
            $baserate->product_id = $product_id;
        }

        $baserate->tarife = (strlen($request->tarife) > 0) ? getFloatFormat($request->tarife) : null;
        $baserate->class_realty = (int)$request->class_realty;
        $baserate->type_realty = (int)$request->type_realty;
        $baserate->save();

        return parentReloadTab();
    }

    public function deleteBaserateTitle($product_id, $baserate_id, Request $request)
    {
        Products\Data\Mortgage\BaseRateTitle::findOrFail($baserate_id)->delete();
        return parentReloadTab();
    }
}
