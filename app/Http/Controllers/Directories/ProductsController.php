<?php

namespace App\Http\Controllers\Directories;

use App\Http\Controllers\Controller;
use App\Models\Directories\Products;
use App\Models\Log\LogEvents;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use Auth;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permissions:directories,products');

        $this->breadcrumbs[] = [
            'label' => 'Продукты',
            'url' => 'directories/products/'
        ];
    }

    public function index()
    {
        return view('directories.products.index', [
            'products' => Products::orderBy('title')->get(),
            "breadcrumbs" => $this->breadcrumbs,

        ]);
    }

    public function create()
    {
        $this->breadcrumbs[] = [
            'label' => 'Новый продукт',
        ];

        return view('directories.products.create', [
            "breadcrumbs" => $this->breadcrumbs,
        ]);
    }

    public function edit($id)
    {

        $product = Products::findOrFail($id);

        $this->breadcrumbs[] = [
            'label' => $product->title,
        ];

        return view('directories.products.edit', [
            'product' => $product,
            "breadcrumbs" => $this->breadcrumbs,
        ]);
    }

    public function store(Request $request)
    {
        $product = new Products;
        $product->save();
        LogEvents::event($product->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 8, 0,0, $request->all());


        return $this->save($product, $request);
    }

    public function update($id, Request $request)
    {
        $product = Products::findOrFail($id);
        LogEvents::event($product->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 8, 0,0, $request->all());

        return $this->save($product, $request);
    }

    private function save(Products $product, Request $request)
    {

        $product->title = $request->title;
        $product->code_api = $request->code_api;
        $product->is_actual = (int)$request->is_actual;
        $product->is_dvou = (int)$request->is_dvou;


        $product->for_inspections = (int)$request->for_inspections;
        $product->financial_policy_type_id = (int)$request->financial_policy_type_id;
        $product->category_id = (int)$request->category_id;
        $product->kv_official_available = $request->kv_official_available;
        $product->kv_informal_available = $request->kv_informal_available;
        $product->kv_bank_available = $request->kv_bank_available;
        $product->inspection_temple_act = $request->inspection_temple_act;


        if ($request->hasFile('file')) {
            $ext = $request->file('file')->getClientOriginalExtension();
            if(in_array($ext, ['xls', 'xlsx', 'docx'])){
                $template = $product->template;
                if($template){

                    $template_path = storage_path() . '/app/' . $template->getPathAttribute();
                    $template_path_host = storage_path() . '/app/' . $template->getPathWithHostAttribute();


                    $pathes = [
                        1 => is_file($template_path) ? $template_path : false,
                        2 => is_file($template_path_host) ? $template_path_host : false
                    ];

                    if($pathkey = array_search(true, $pathes)){
                        unlink($pathes[$pathkey]);
                        $template->delete();
                    }
                }


                $file = (new FilesRepository)->makeFile($request->file, Products::TEMPLATES_FOLDER);
                $product->template_id = $file->id;

            }

        }

        if ($request->hasFile('file_contract')) {
            $ext = $request->file('file_contract')->getClientOriginalExtension();
            if(in_array($ext, ['xls', 'xlsx', 'docx'])){
                $template = $product->template_contract;
                if($template){

                    $template_path = storage_path() . '/app/' . $template->getPathAttribute();
                    $template_path_host = storage_path() . '/app/' . $template->getPathWithHostAttribute();


                    $pathes = [
                        1 => is_file($template_path) ? $template_path : false,
                        2 => is_file($template_path_host) ? $template_path_host : false
                    ];

                    if($pathkey = array_search(true, $pathes)){
                        unlink($pathes[$pathkey]);
                        $template->delete();
                    }
                }


                $file = (new FilesRepository)->makeFile($request->file_contract, Products::TEMPLATES_FOLDER);
                $product->template_contract_id = $file->id;

            }

        }

        if ($request->hasFile('file_statement')) {
            $ext = $request->file('file_statement')->getClientOriginalExtension();
            if(in_array($ext, ['xls', 'xlsx', 'docx'])){
                $template = $product->template_statement;
                if($template){

                    $template_path = storage_path() . '/app/' . $template->getPathAttribute();
                    $template_path_host = storage_path() . '/app/' . $template->getPathWithHostAttribute();


                    $pathes = [
                        1 => is_file($template_path) ? $template_path : false,
                        2 => is_file($template_path_host) ? $template_path_host : false
                    ];

                    if($pathkey = array_search(true, $pathes)){
                        unlink($pathes[$pathkey]);
                        $template->delete();
                    }
                }


                $file = (new FilesRepository)->makeFile($request->file_statement, Products::TEMPLATES_FOLDER);
                $product->template_statement_id = $file->id;

            }

        }

        $product->is_online = (int)$request->is_online;
        $product->is_common_calculation = (int)$request->is_common_calculation;
        $product->slug = $request->slug;
        $product->description = $request->description;

        $product->template_print = $request->template_print;
        $product->template_print_x = $request->template_print_x;
        $product->template_print_y = $request->template_print_y;
        $product->template_signature = $request->template_signature;
        $product->template_signature_x = $request->template_signature_x;
        $product->template_signature_y = $request->template_signature_y;


        $product->save();

        return redirect("/directories/products/{$product->id}/edit");
    }

    public function destroy($id)
    {

        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 8);

        Products::findOrFail($id)->delete();

        return response('', 200);
    }

}
