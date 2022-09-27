<?php

namespace App\Http\Controllers\Directories\Products\Program\Arbitration;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsPrograms;
use App\Models\File;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
use App\Processes\Tariff\Settings\Product\TariffLiabilityArbitrationManager;
use Illuminate\Http\Request;
use App\Repositories\FilesRepository;

class ProgramArbitrationController extends Controller
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

    public function index($product_id, $program_id, Request $request)
    {

        $product = Products::findOrFail($product_id);
        $program = ProductsPrograms::findOrFail($program_id);
        $this->breadcrumbs[] = [
            'label' => $product->title,
            'url' => "{$product->id}/edit"
        ];

        $this->breadcrumbs[] = [
            'label' => 'Спец настройки - '.$program->title,
            'url' => "special-settings/program/{$program->id}/arbitration"
        ];

        $this->breadcrumbs[] = [
            'label' => '',
        ];

        return view("directories.products.special_settings.program.arbitration.index", [
            "breadcrumbs" => $this->breadcrumbs,
            "product" => $product,
            'program' => $program,
        ]);

    }


    public function getFormHtml($product_id, $program_id, Request $request)
    {


        $product = Products::findOrFail($product_id);
        $program = ProductsPrograms::findOrFail($program_id);

        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
        if(!$spec){
            $spec = Products\ProductsSpecialSsettings::create(['product_id'=>$product_id, 'program_id'=>$program_id]);
        }

        return view("directories.products.special_settings.program.arbitration.form.{$request->view}", [
            "product" => $product,
            'program' => $program,
            'spec' => $spec,
        ]);
    }



    public function saveTariff($product_id, $program_id, Request $request)
    {
        $product = Products::findOrFail($product_id);
        $program = ProductsPrograms::findOrFail($program_id);

        $json = null;
        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
        if(!$spec){
            $spec = Products\ProductsSpecialSsettings::create(['product_id'=>$product_id, 'program_id'=>$program_id]);
            $json['tariff'] = TariffLiabilityArbitrationManager::defaultJson();
        }else{

            if(strlen($spec->json) > 0){
                $json = \GuzzleHttp\json_decode($spec->json, true);
                if(!isset($json['tariff'])){
                    $json['tariff'] = TariffLiabilityArbitrationManager::defaultJson();
                }
            }else{
                $json['tariff'] = TariffLiabilityArbitrationManager::defaultJson();
            }

        }

        if(isset($json['tariff']) && isset($json['tariff']['programs']) && isset($json['tariff']['programs'][$program->slug])){
           $json['tariff']['programs'][$program->slug]['values'] = $request->value;
        }


        $spec->json = \GuzzleHttp\json_encode($json);
        $spec->save();

        return response('',200);
    }


    public function saveDefault($product_id, $program_id, Request $request)
    {
        $arbitration = $request->arbitration;
        $json = null;
        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
        if(!$spec){
            $spec = Products\ProductsSpecialSsettings::create(['product_id'=>$product_id, 'program_id'=>$program_id]);
            $json = new \stdClass();
            $json['matching'] = [];
        }else{
            if(strlen($spec->json) > 0){
                $json = \GuzzleHttp\json_decode($spec->json, true);
                if(!isset($json['matching'])){
                    $json['matching'] = [];
                }
            }else{
                $json['matching'] = [];
            }

        }

        $json['matching'] = $arbitration['matching'];

        $spec->json = \GuzzleHttp\json_encode($json);
        $spec->save();

        return response('',200);

    }


    public function saveDocuments($product_id, $program_id, Request $request)
    {
        $type_name = 'contract';
        if ($request->hasFile($type_name)) {
            $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
            if (!$spec) {
                $spec = Products\ProductsSpecialSsettings::create(['product_id' => $product_id, 'program_id' => $program_id]);
            }

            $file = Products\ProductsSpecialSsettingsFiles::where('special_settings_id', $spec->id)
                ->where('type_name', $type_name)
                ->get();

            if (!sizeof($file)) {
                $file = new Products\ProductsSpecialSsettingsFiles();
                $file->special_settings_id = $spec->id;
            } else {
                $file = $file->first();
            }

            $file->type_name = $type_name;

            $saved_file = $this->filesRepository->makeFile($request->file($type_name), Products\ProductsSpecialSsettings::FILES_DOC . "/$product_id/$program_id/");
            $file->file_id = $saved_file->id;
            $file->template_print = $request->template_print;
            $file->template_print_page = $request->template_print_page > 0 ? $request->template_print_page : null;
            $file->template_print_x = $request->template_print_x;
            $file->template_print_y = $request->template_print_y;
            $file->template_signature = $request->template_signature;
            $file->template_signature_x = $request->template_signature_x;
            $file->template_signature_y = $request->template_signature_y;
            $file->save();
        }
        $type_name = 'policy';
        if ($request->hasFile($type_name)) {
            $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
            if (!$spec) {
                $spec = Products\ProductsSpecialSsettings::create(['product_id' => $product_id, 'program_id' => $program_id]);
            }

            $file = Products\ProductsSpecialSsettingsFiles::where('special_settings_id', $spec->id)
                ->where('type_name', $type_name)
                ->get();

            if (!sizeof($file)) {
                $file = new Products\ProductsSpecialSsettingsFiles();
                $file->special_settings_id = $spec->id;
            } else {
                $file = $file->first();
            }

            $file->type_name = $type_name;

            $saved_file = $this->filesRepository->makeFile($request->file($type_name), Products\ProductsSpecialSsettings::FILES_DOC . "/$product_id/$program_id/");
            $file->file_id = $saved_file->id;
            $file->save();
        }

        return redirect(url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/arbitration?view=documents"))->with('success', 'Данные успешно сохранены!');


    }
    public function save_files($product_id, $program_id, Request $request) {

        $type_name = 'others';
        if ($request->file) {
            $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
            if (!$spec) {
                $spec = Products\ProductsSpecialSsettings::create(['product_id' => $product_id, 'program_id' => $program_id]);
            }

            $file = new Products\ProductsSpecialSsettingsFiles();
            $file->special_settings_id = $spec->id;

            $file->type_name = $type_name;

            $saved_file = $this->filesRepository->makeFile($request->file, Products\ProductsSpecialSsettings::FILES_DOC . "/$product_id/$program_id/");
            $file->file_id = $saved_file->id;
            $file->save();
        }
        return redirect(url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/arbitration?view=documents"))->with('success', 'Данные успешно сохранены!');

    }

    public function delete_file($product_id, $program_id, $files_name, Request $request) {

        $file = File::whereName($files_name)->firstOrFail();
        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
        Products\ProductsSpecialSsettingsFiles::where('special_settings_id', $spec->id)->where('file_id', $file->id)->delete();

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
        return redirect(url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/arbitration?view=documents"))->with('success', 'Данные успешно сохранены!');


    }


    public function getCoefficientsList($product_id, $program_id, $category, Request $request)
    {

        $coefficients = Products\Data\Arbitration\ArbitrationCoefficient::where('product_id', $product_id)
            ->where('program_id', $program_id)
            ->where('category', $category)
            ->get();

        return view("directories.products.special_settings.program.arbitration.form.coefficients.list", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'coefficients' => $coefficients,
        ]);

    }

    public function getCoefficient($product_id, $program_id, $category, $coefficient_id, Request $request)
    {
        $coefficients = Coefficients::getCoefficientsaAbitrationToCategoryDefault($category);
        if(count($coefficients) == 0){
            abort(303);
        }

        if($coefficient_id > 0){
            $coefficient = Products\Data\Arbitration\ArbitrationCoefficient::findOrFail($coefficient_id);
        }else{
            $coefficient = new Products\Data\Arbitration\ArbitrationCoefficient();
        }


        return view("directories.products.special_settings.program.arbitration.form.coefficients.coefficient", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'coefficient_id' => (int)$coefficient_id,
            'coefficients' => $coefficients,
            'coefficient' => $coefficient,
        ]);


    }

    public function getCoefficientElement($product_id, $program_id, $category, $coefficient_id, Request $request)
    {

        $group = Coefficients::getCoefficientsAbitrationGroupDefault($category, $request->group);

        if($coefficient_id > 0){
            $coefficient = Products\Data\Arbitration\ArbitrationCoefficient::findOrFail($coefficient_id);
        }else{
            $coefficient = new Products\Data\Arbitration\ArbitrationCoefficient();
        }

        return view("directories.products.special_settings.program.arbitration.form.coefficients.element", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'coefficient_id' => (int)$coefficient_id,
            'group' => $group,
            'coefficient' => $coefficient,
        ]);

    }


    public function saveCoefficient($product_id, $program_id, $category, $coefficient_id, Request $request)
    {
        $group = Coefficients::getCoefficientsAbitrationGroupDefault($category, $request->group);

        if($coefficient_id > 0){
            $coefficient = Products\Data\Arbitration\ArbitrationCoefficient::findOrFail($coefficient_id);
        }else{
            $coefficient = new Products\Data\Arbitration\ArbitrationCoefficient();
            $coefficient->product_id = $product_id;
            $coefficient->program_id = $program_id;
            $coefficient->category = $category;
        }

        $coefficient->group = $request->group;
        $coefficient->type = $group['control']['type'];
        $coefficient->tarrif_name = 'К'.$group['tarrif_name'];
        $coefficient->field = $group['field'];
        if($group['control']['type'] == 'select'){
            $coefficient->value = $request->value;
        }

        if($group['control']['type'] == 'range'){
            $coefficient->value_to = $request->value_to;
            $coefficient->value_from = $request->value_from;
        }

        $coefficient->tarife = getFloatFormat($request->tarife);
        $coefficient->save();

        return parentReloadSelect();
    }

    public function deleteCoefficient($product_id, $program_id, $category, $coefficient_id, Request $request)
    {
        Products\Data\Arbitration\ArbitrationCoefficient::findOrFail($coefficient_id)->delete();
        return parentReloadSelect();
    }


}
