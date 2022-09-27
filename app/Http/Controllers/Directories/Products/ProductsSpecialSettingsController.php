<?php

namespace App\Http\Controllers\Directories\Products;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\Directories\Products\ProductsSpecialSsettingsFiles;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\File;
use App\Processes\Tariff\Settings\Product\TariffVzr;
use Illuminate\Http\Request;
use App\Repositories\FilesRepository;

class ProductsSpecialSettingsController extends Controller
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

    public function index($product_id, Request $request)
    {

        $product = Products::findOrFail($product_id);
        $this->breadcrumbs[] = [
            'label' => $product->title,
            'url' => "{$product->id}/edit"
        ];

        $this->breadcrumbs[] = [
            'label' => 'Спец настройки',
            'url' => "special-settings"
        ];

        $this->breadcrumbs[] = [
            'label' => '',
        ];

        $view = 'index';
        if($product->slug != ''){
            if(\View::exists("directories.products.special_settings.{$product->slug}")){
                $view = $product->slug;
            }

        }

        $special_settings = ProductsSpecialSsettings::where('product_id', $product_id)->get()->first();
        if(!$special_settings){
            $special_settings = ProductsSpecialSsettings::create(['product_id'=>$product_id]);
        }

        $json = null;
        if($special_settings->json){
            $json = \GuzzleHttp\json_decode($special_settings->json, true);
        }


        return view("directories.products.special_settings.{$view}", [
            "breadcrumbs" => $this->breadcrumbs,
            "product" => $product,
            "special_settings" => $special_settings,
            "json" => $json,
            "request" => $request,
        ]);

    }

    public function save($product_id, Request $request)
    {
        $product = Products::findOrFail($product_id);
        $special_settings = $product->special_settings;

        $result = new \stdClass();
        $result->msg = 'Ошибка сохраниения данных';
        $result->state = $special_settings->savaJson($request, $product->slug);

        return response()->json($result);
    }



    public function risks_edit($product_id, $risks_id)
    {
        $product = Products::findOrFail($product_id);

        $this->breadcrumbs[] = [
            'label' => $product->title,
            'url' => "{$product->id}/edit"
        ];

        $this->breadcrumbs[] = [
            'label' => 'Спец настройки',
            'url' => "special-settings/"
        ];

        $this->breadcrumbs[] = [
            'label' => 'Риски',
        ];

        if($risks_id > 0){
            $risk = ProductsFlatsRisks::findOrFail($risks_id);
        }else{
            $risk = new ProductsFlatsRisks();
        }

        return view("directories.products.special_settings.risks.{$product->slug}", [
            "breadcrumbs" => $this->breadcrumbs,
            "product" => $product,
            'risk' => $risk
        ]);

    }


    public function risks_save($product_id, $risks_id, Request $request)
    {
        $product = Products::findOrFail($product_id);


        if($risks_id > 0){
            $risk = ProductsFlatsRisks::findOrFail($risks_id);
        }else{
            $risk = ProductsFlatsRisks::create(['product_id'=>$product_id]);
        }

        $risk->title = $request->title;
        $risk->insurance_amount_comment = $request->insurance_amount_comment;
        $risk->beneficiary = $request->beneficiary;
        $risk->insurance_territory = $request->insurance_territory;
        $risk->insurance_object = $request->insurance_object;
        $risk->risks_events = $request->risks_events;
        $risk->payment_total = getFloatFormat($request->payment_total);
        $risk->insurance_amount = getFloatFormat($request->insurance_amount);
        $risk->save();

        return redirect("/directories/products/{$product->id}/edit/special-settings/{$risk->id}/risks");
    }

    public function risks_destroy($product_id, $risks_id, Request $request)
    {
        ProductsFlatsRisks::findOrFail($risks_id)->delete();
        return response('', 200);
    }


    public function risks_sort(Request $request){
        foreach($request->infodata as $sort => $info_id){
            $info = ProductsFlatsRisks::findOrFail($info_id);
            $info->sort = $sort;
            $info->save();
        }
        return response('', 200);
    }


    public function save_files($product_id, Request $request) {

        $special_settings = ProductsSpecialSsettings::where('product_id', $product_id)->get()->first();
        $special_settings->files()->save($this->filesRepository->makeFile($request->file, ProductsSpecialSsettings::FILES_DOC . "/$product_id/"));

        return response('', 200);
    }

    public function delete_file($product_id, $files_name, Request $request) {

        $file = File::whereName($files_name)->firstOrFail();
        $special_settings = ProductsSpecialSsettings::where('product_id', $product_id)->get()->first();
        ProductsSpecialSsettingsFiles::where('special_settings_id', $special_settings->id)->where('file_id', $file->id)->delete();

        /* Хук для локальной версии */
        $url = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], $file->path_with_host), '/');
        $prefix = $file->getPrefix();
        $thumb = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], 'thumb/' . implode('/', $prefix) . '/' . $file->name . '.' . $file->ext), '/');

        if (\Storage::exists($url)) {
            \Storage::delete($url);
            $file->delete();
        }

        if (\Storage::exists($thumb)) {
            \Storage::delete($thumb);
        }
        return response('', 200);

    }


    public function official_discount_edit($product_id, $discount_id)
    {
        $product = Products::findOrFail($product_id);

        if($discount_id > 0){
            $discount = ProductsOfficialDiscount::findOrFail($discount_id);
        }else{
            $discount = new ProductsOfficialDiscount();
        }

        $json = [];
        if($discount->json){
            $json = \GuzzleHttp\json_decode($discount->json);
        }

        return view("directories.products.special_settings.official_discount.index", [
            "product" => $product,
            'discount' => $discount,
            'json' => $json
        ]);

    }


    public function official_discount_save($product_id, $discount_id, Request $request)
    {
        $product = Products::findOrFail($product_id);
        if($discount_id > 0){
            $discount = ProductsOfficialDiscount::findOrFail($discount_id);
        }else{
            $discount = ProductsOfficialDiscount::create(['product_id'=>$product_id]);
        }

        $discount->type_id = (int)$request->type_id;
        $discount->discount = getFloatFormat($request->discount);
        $discount->json = \GuzzleHttp\json_encode($request->get('risks'));
        $discount->save();

        return parentReload();
    }

    public function official_discount_destroy($product_id, $discount_id, Request $request)
    {
        ProductsOfficialDiscount::findOrFail($discount_id)->delete();
        return response('', 200);
    }



    public function assistance_edit($product_id, $assistance_id, Request $request)
    {
        if($assistance_id > 0){
            $assistance = Products\ProductsAssistanceInfo::findOrFail($assistance_id);
        }else{
            $assistance = new Products\ProductsAssistanceInfo();
            $assistance->product_id = $product_id;
        }


        return view('directories.products.special_settings.assistance.index', [
            'product_id' => (int)$product_id,
            'assistance_id' => (int)$assistance_id,
            'assistance' => $assistance,
        ]);

    }

    public function assistance_save($product_id, $assistance_id, Request $request)
    {
        if($assistance_id > 0){
            $assistance = Products\ProductsAssistanceInfo::findOrFail($assistance_id);
        }else{
            $assistance = new Products\ProductsAssistanceInfo();
            $assistance->product_id = $product_id;
        }

        $assistance->country_id = $request->country_id;
        $assistance->title = $request->title;
        $assistance->phone = $request->phone;
        $assistance->comments = $request->comments;
        $assistance->save();

        return parentReload();
    }

    public function assistance_destroy($product_id, $assistance_id, Request $request)
    {
        Products\ProductsAssistanceInfo::findOrFail($assistance_id)->delete();
        return parentReload();
    }



    public function tableTariffEdit($product_id, $risks_id)
    {
        $product = Products::findOrFail($product_id);


        return view("directories.products.special_settings.table_tariff.{$product->slug}.index", [
            "product" => $product,
            'risks_id' => $risks_id
        ]);

    }

    public function tableTariffSave($product_id, $risks_id, Request $request)
    {
        $product = Products::findOrFail($product_id);
        $data = $request->conf;
        if($product->slug == "gap"){
            Products\Data\GAP\BaseRateGap::saveBaseRateList($risks_id, $data);
        }

        if($product->slug == "dgo"){
            Products\Data\DGO\BaseRateDgo::saveBaseRateList($data);
        }

        return response('', 200);
    }




}
