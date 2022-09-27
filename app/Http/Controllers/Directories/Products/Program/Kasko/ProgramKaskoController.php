<?php

namespace App\Http\Controllers\Directories\Products\Program\Kasko;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Directories\ProductsPrograms;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Processes\Operations\Contracts\Settings\Kasco\Coefficients;
use App\Processes\Operations\Contracts\Settings\Kasco\Dopwhere;
use Illuminate\Http\Request;
use App\Repositories\FilesRepository;

class ProgramKaskoController extends Controller
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
            'url' => "special-settings/program/{$program->id}/kasko"
        ];

        $this->breadcrumbs[] = [
            'label' => '',
        ];

        return view("directories.products.special_settings.program.kasko.index", [
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

        return view("directories.products.special_settings.program.kasko.form.{$request->view}", [
            "product" => $product,
            'program' => $program,
            'spec' => $spec,
        ]);
    }


    public function getAutoMark($product_id, $program_id, Request $request)
    {
        $category = VehicleCategories::findOrFail($request->category_id);

        $result = VehicleMarks::where('vehicle_marks.category_id', $category->isn)
            ->leftJoin('kasko_baserate', function($leftJoin) use($product_id, $program_id)
            {
                $leftJoin->on('kasko_baserate.mark_id', '=', 'vehicle_marks.id')
                    ->where('kasko_baserate.product_id', '=', $product_id )
                    ->where('kasko_baserate.program_id', '=', $program_id )
                    ->whereNull('kasko_baserate.model_id');


            })
            ->orderBy('vehicle_marks.title', 'asc')
            ->get(['vehicle_marks.id', 'vehicle_marks.title', 'kasko_baserate.payment_damage', 'kasko_baserate.total', 'kasko_baserate.theft'])
            ->toArray();

        return response()->json($result);
    }

    public function getAutoModels($product_id, $program_id, Request $request)
    {

        if((int)$request->mark_id == 0){
            return $this->getAutoMark($product_id, $program_id,  $request);
        }

        $category = VehicleCategories::findOrFail($request->category_id);

        $result = [];
        if ((int)$request->mark_id>0) {
            $result = VehicleModels::where('vehicle_models.category_id', $category->isn)
                ->leftJoin('kasko_baserate', function($leftJoin) use($product_id, $program_id, $request)
                {
                    $leftJoin->on('kasko_baserate.model_id', '=', 'vehicle_models.id')
                        ->where('kasko_baserate.mark_id', '=', (int)$request->mark_id )
                        ->where('kasko_baserate.product_id', '=', $product_id )
                        ->where('kasko_baserate.program_id', '=', $program_id )
                        ->whereNull('kasko_baserate.year');
                })
                ->where('vehicle_models.mark_id2', (int)$request->mark_id)
                ->orderBy('vehicle_models.title', 'asc')
                ->get(['vehicle_models.id', 'vehicle_models.title', 'kasko_baserate.payment_damage', 'kasko_baserate.total', 'kasko_baserate.theft'])
                ->toArray();
        }

        return response()->json($result);
    }


    public function getBaserate($product_id, $program_id, Request $request)
    {

        $product = Products::findOrFail($product_id);
        $program = ProductsPrograms::findOrFail($program_id);

        $mark_id = $request->mark_id;
        $model_id = $request->model_id;

        //BaseRateKasko

        return view("directories.products.special_settings.program.kasko.form.baserate_form", [
            "product" => $product,
            'program' => $program,
            'mark_id' => $mark_id,
            'model_id' => $model_id,
        ]);

    }


    public function saveDefBaserate($product_id, $program_id, Request $request)
    {
        $mark_id = (int)$request->mark_id;
        $model_id = (int)$request->isn;

        if($mark_id == 0){
            $mark_id = (int)$request->isn;
            $model_id = null;
            Products\Data\Kasko\BaseRateKasko::where('mark_id', $mark_id)
                ->whereNull('model_id')
                ->where('product_id', $product_id)
                ->where('program_id', $program_id)
                ->delete();
        }else{
            Products\Data\Kasko\BaseRateKasko::where('mark_id', $mark_id)
                ->where('model_id', $model_id)
                ->whereNull('year')
                ->where('product_id', $product_id)
                ->where('program_id', $program_id)
                ->delete();
        }

        Products\Data\Kasko\BaseRateKasko::create([
            'program_id' => $program_id,
            'product_id' => $product_id,
            'mark_id' => $mark_id,
            'model_id' => $model_id,
            'year' => null,
            'payment_damage' => (strlen($request->payment_damage))?getFloatFormat($request->payment_damage):null,
            'total' => (strlen($request->total))?getFloatFormat($request->total):null,
            'theft' => (strlen($request->theft))?getFloatFormat($request->theft):null,
        ]);

        return response()->json(['state' => true]);


    }

    public function saveBaserate($product_id, $program_id, Request $request)
    {
        $mark_id = $request->mark_id;
        $model_id = $request->model_id;

        Products\Data\Kasko\BaseRateKasko::where('mark_id', $mark_id)
            ->where('model_id', $model_id)
            ->whereNotNull('year')
            ->where('product_id', $product_id)
            ->where('program_id', $program_id)
            ->delete();

        foreach ($request->baserate as $baserate){

            Products\Data\Kasko\BaseRateKasko::create([
                'program_id' => $program_id,
                'product_id' => $product_id,
                'mark_id' => $mark_id,
                'model_id' => $model_id,
                'year' => $baserate['year'],
                'payment_damage' => (strlen($baserate['payment_damage']))?getFloatFormat($baserate['payment_damage']):null,
                'total' => (strlen($baserate['total']))?getFloatFormat($baserate['total']):null,
                'theft' => (strlen($baserate['theft']))?getFloatFormat($baserate['theft']):null,
            ]);


        }
        return response()->json(['state' => true]);
    }

    public function getEquipment($product_id, $program_id, $equipment_id, Request $request)
    {
        if((int)$equipment_id > 0){
            $equipment = Products\Data\Kasko\KaskoEquipment::findOrFail($equipment_id);
        }else{
            $equipment = new Products\Data\Kasko\KaskoEquipment();
        }


        return view("directories.products.special_settings.program.kasko.form.equipment_form", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'equipment_id' => (int)$equipment_id,
            'equipment' => $equipment,
        ]);

    }

    public function saveEquipment($product_id, $program_id, $equipment_id, Request $request)
    {
        if((int)$equipment_id > 0){
            $equipment = Products\Data\Kasko\KaskoEquipment::findOrFail($equipment_id);
        }else{
            $equipment = new Products\Data\Kasko\KaskoEquipment();
            $equipment->product_id = $product_id;
            $equipment->program_id = $program_id;
        }


        $equipment->amount_to = getFloatFormat($request->amount_to);
        $equipment->amount_from = getFloatFormat($request->amount_from);
        $equipment->payment_tarife = getFloatFormat($request->payment_tarife);
        $equipment->save();

        return parentReloadTab();

    }


    public function deleteEquipment($product_id, $program_id, $equipment_id, Request $request)
    {
        Products\Data\Kasko\KaskoEquipment::findOrFail($equipment_id)->delete();
        return parentReloadTab();
    }


    public function getService($product_id, $program_id, $service_id, Request $request)
    {
        if((int)$service_id > 0){
            $service = Products\Data\Kasko\KaskoService::findOrFail($service_id);
        }else{
            $service = new Products\Data\Kasko\KaskoService();
        }


        return view("directories.products.special_settings.program.kasko.form.service_form", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'service_id' => (int)$service_id,
            'service' => $service,
        ]);

    }

    public function saveService($product_id, $program_id, $service_id, Request $request)
    {
        if((int)$service_id > 0){
            $service = Products\Data\Kasko\KaskoService::findOrFail($service_id);
        }else{
            $service = new Products\Data\Kasko\KaskoService();
            $service->product_id = $product_id;
            $service->program_id = $program_id;
        }

        $service->service_name = $request->service_name;
        $service->payment_total = getFloatFormat($request->payment_total);
        $service->save();

        return parentReloadTab();


    }


    public function deleteService($product_id, $program_id, $service_id, Request $request)
    {
        Products\Data\Kasko\KaskoService::findOrFail($service_id)->delete();
        return parentReloadTab();
    }


    public function getKProduct($product_id, $program_id, $k_product_id, Request $request)
    {
        if((int)$k_product_id > 0){
            $k_product = Products\Data\Kasko\KaskoProduct::findOrFail($k_product_id);
        }else{
            $k_product = new Products\Data\Kasko\KaskoProduct();
        }


        return view("directories.products.special_settings.program.kasko.form.product_form", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'k_product_id' => (int)$k_product_id,
            'k_product' => $k_product,
        ]);

    }


    public function saveKProduct($product_id, $program_id, $k_product_id, Request $request)
    {
        if((int)$k_product_id > 0){
            $k_product = Products\Data\Kasko\KaskoProduct::findOrFail($k_product_id);
        }else{
            $k_product = new Products\Data\Kasko\KaskoProduct();
            $k_product->product_id = $product_id;
            $k_product->program_id = $program_id;
        }

        $k_product->kasko_product_id = $request->kasko_product_id;

        if($k_product->kasko_product_id == 4){
            $request->amount = $request->civil_responsibility_amount;
        }

        $k_product->amount = getFloatFormat($request->amount);
        $k_product->amount_text = titleFloatFormat(getFloatFormat($request->amount));
        $k_product->payment_tarife = getFloatFormat($request->payment_tarife);
        $k_product->save();

        return parentReloadTab();


    }


    public function deleteKProduct($product_id, $program_id, $k_product_id, Request $request)
    {
        Products\Data\Kasko\KaskoProduct::findOrFail($k_product_id)->delete();
        return parentReloadTab();
    }


    public function saveDefault($product_id, $program_id, Request $request)
    {
        $kasko = $request->kasko;

        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
        if(!$spec){
            $spec = Products\ProductsSpecialSsettings::create(['product_id'=>$product_id, 'program_id'=>$program_id]);
        }

        $spec->json = \GuzzleHttp\json_encode($kasko);
        $spec->save();

        return response('',200);

    }

    public function saveDocuments($product_id, $program_id, Request $request)
    {
        $kasko = $request->kasko;
        $type_name = 'contract';

        $spec = Products\ProductsSpecialSsettings::where('product_id', $product_id)->where('program_id', $program_id)->get()->first();
        if (!$spec) {
            $spec = Products\ProductsSpecialSsettings::create(['product_id' => $product_id, 'program_id' => $program_id]);
        }

        if ($request->hasFile($type_name)) {


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

            $saved_file = $this->filesRepository->makeFile($request->file($type_name), Products\ProductsSpecialSsettings::FILES_DOC . "/$product_id/$program_id");
            $file->file_id = $saved_file->id;
            $file->save();
        }
        $type_name = 'agreement';
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


        if(isset($request->template_print) && strlen($request->template_print) > 0){
            $file_c = Products\ProductsSpecialSsettingsFiles::where('special_settings_id', $spec->id)
                ->where('type_name', 'contract')
                ->get()->first();

            if($file_c){
                $file_c->template_print = $request->template_print;
                $file_c->template_print_page = $request->template_print_page > 0 ? $request->template_print_page : null;
                $file_c->template_print_x = $request->template_print_x;
                $file_c->template_print_y = $request->template_print_y;
                $file_c->save();
            }
        }




        return redirect(url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko?view=documents"))->with('success', 'Данные успешно сохранены!');


    }

    public function getCoefficientsList($product_id, $program_id, $category, Request $request)
    {

        $coefficients = Products\Data\Kasko\KaskoCoefficient::where('product_id', $product_id)
            ->where('program_id', $program_id)
            ->where('category', $category)
            ->get();

        return view("directories.products.special_settings.program.kasko.form.coefficients.list", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'coefficients' => $coefficients,
        ]);

    }


    public function getCoefficient($product_id, $program_id, $category, $coefficient_id, Request $request)
    {
        $coefficients = Coefficients::getCoefficientsAllToCategoryDefault($category);
        if(count($coefficients) == 0){
            abort(303);
        }

        if($coefficient_id > 0){
            $coefficient = Products\Data\Kasko\KaskoCoefficient::findOrFail($coefficient_id);
        }else{
            $coefficient = new Products\Data\Kasko\KaskoCoefficient();
        }


        return view("directories.products.special_settings.program.kasko.form.coefficients.coefficient", [
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
        $coefficients = Coefficients::getCoefficientsAllToCategoryDefault($category);
        $group = Coefficients::getCoefficientsGroupDefault($category, $request->group);

        if($coefficient_id > 0){
            $coefficient = Products\Data\Kasko\KaskoCoefficient::findOrFail($coefficient_id);
        }else{
            $coefficient = new Products\Data\Kasko\KaskoCoefficient();
        }

        return view("directories.products.special_settings.program.kasko.form.coefficients.element", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'coefficient_id' => (int)$coefficient_id,
            'group' => $group,
            'coefficient' => $coefficient,
            'coefficients' => $coefficients,
        ]);

    }

    public function saveCoefficient($product_id, $program_id, $category, $coefficient_id, Request $request)
    {
        $group = Coefficients::getCoefficientsGroupDefault($category, $request->group);

        if($coefficient_id > 0){
            $coefficient = Products\Data\Kasko\KaskoCoefficient::findOrFail($coefficient_id);
        }else{
            $coefficient = new Products\Data\Kasko\KaskoCoefficient();
            $coefficient->product_id = $product_id;
            $coefficient->program_id = $program_id;
            $coefficient->category = $category;
        }

        $coefficient->group = $request->group;
        $coefficient->tarrif_name = 'К'.$group['tarrif_name'];
        $coefficient->field = $group['field'];


        if(isset($group['is_adjacent']) && (int)$group['is_adjacent'] == 1){
            $coefficient->is_adjacent = 1;
            $json = $request->coefficient;
            $coefficient->json = \GuzzleHttp\json_encode($json);

        }else{

            $coefficient->type = $group['control']['type'];
            $coefficient->is_adjacent = 0;

            if($group['control']['type'] == 'select'){
                $coefficient->value = $request->value;
            }

            if($group['control']['type'] == 'range'){
                $coefficient->value_to = $request->value_to;
                $coefficient->value_from = $request->value_from;
            }
        }



        $coefficient->tarife = getFloatFormat($request->tarife);
        $coefficient->save();

        return parentReloadSelect();
    }

    public function deleteCoefficient($product_id, $program_id, $category, $coefficient_id, Request $request)
    {
        Products\Data\Kasko\KaskoCoefficient::findOrFail($coefficient_id)->delete();
        return parentReloadSelect();
    }



    public function getDopwhereList($product_id, $program_id, $category, Request $request)
    {

        $dopwheres = Products\Data\Kasko\KaskoDopwhere::where('product_id', $product_id)
            ->where('program_id', $program_id)
            ->where('category', $category)
            ->get();

        return view("directories.products.special_settings.program.kasko.form.dopwhere.list", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'dopwheres' => $dopwheres,
        ]);

    }


    public function getDopwhere($product_id, $program_id, $category, $dopwhere_id, Request $request)
    {
        $dopwheres = Dopwhere::getDopwhereAllToCategoryDefault($category);
        if(count($dopwheres) == 0){
            abort(303);
        }

        if($dopwhere_id > 0){
            $dopwhere = Products\Data\Kasko\KaskoDopwhere::findOrFail($dopwhere_id);
        }else{
            $dopwhere = new Products\Data\Kasko\KaskoDopwhere();
        }


        return view("directories.products.special_settings.program.kasko.form.dopwhere.edit", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'dopwhere_id' => (int)$dopwhere_id,
            'dopwheres' => $dopwheres,
            'dopwhere' => $dopwhere,
        ]);


    }


    public function getDopwhereElement($product_id, $program_id, $category, $dopwhere_id, Request $request)
    {
        $dopwheres = Dopwhere::getDopwhereAllToCategoryDefault($category);
        $group = Dopwhere::getDopwheresGroupDefault($category, $request->group);

        if($dopwhere_id > 0){
            $dopwhere = Products\Data\Kasko\KaskoDopwhere::findOrFail($dopwhere_id);
        }else{
            $dopwhere = new Products\Data\Kasko\KaskoDopwhere();
        }

        return view("directories.products.special_settings.program.kasko.form.dopwhere.element", [
            "product_id" => (int)$product_id,
            'program_id' => (int)$program_id,
            'category' => $category,
            'coefficient_id' => (int)$dopwhere_id,
            'group' => $group,
            'coefficient' => $dopwhere,
            'coefficients' => $dopwheres,
        ]);

    }


    public function saveDopwhere($product_id, $program_id, $category, $dopwhere_id, Request $request)
    {
        $group = Dopwhere::getDopwheresGroupDefault($category, $request->group);

        if($dopwhere_id > 0){
            $dopwhere = Products\Data\Kasko\KaskoDopwhere::findOrFail($dopwhere_id);
        }else{
            $dopwhere = new Products\Data\Kasko\KaskoDopwhere();
            $dopwhere->product_id = $product_id;
            $dopwhere->program_id = $program_id;
            $dopwhere->category = $category;
        }

        $dopwhere->group = $request->group;
        $dopwhere->tarrif_name = 'Д'.$group['tarrif_name'];
        $dopwhere->field = $group['field'];


        $dopwhere->type = $group['control']['type'];

        if($group['control']['type'] == 'select'){
            $dopwhere->value = $request->value;
        }

        if($group['control']['type'] == 'range'){
            $dopwhere->value_to = $request->value_to;
            $dopwhere->value_from = $request->value_from;
        }


        $dopwhere->tarife = getFloatFormat($request->tarife);
        $dopwhere->save();

        return parentReloadSelect();
    }

    public function deleteDopwhere($product_id, $program_id, $category, $dopwhere_id, Request $request)
    {
        Products\Data\Kasko\KaskoDopwhere::findOrFail($dopwhere_id)->delete();
        return parentReloadSelect();
    }

}
