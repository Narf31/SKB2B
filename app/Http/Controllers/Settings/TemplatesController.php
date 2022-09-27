<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Directories\InsuranceCompanies;
use App\Models\Settings\ExportItem;
use App\Models\Settings\Template;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;

class TemplatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permissions:settings,templates');
    }

    public function index()
    {
        return view('settings.templates.index', [
            'templates' => Template::all(),
        ]);
    }

    public function get_table(){

        $this->validate(request(), [
            'category_id' => 'integer',
            'supplier_id' => 'integer',
        ]);

        $category_id = (int)request('category_id', 0);
        $supplier_id = (int)request('supplier_id', 0);

        $templates = Template::query();

        if($category_id > 0){
            $templates->where('category_id', $category_id);
        }

        if($supplier_id > 0){
            $templates->whereIn('supplier_id', [request('supplier_id')]);
        }


        $templates = $templates->get();

        return view('settings.templates.table',[
            'templates' => $templates
        ]);
    }


    public function create()
    {
        return view('settings.templates.create');
    }

    public function edit($id)
    {
        return view('settings.templates.edit', [
            'template' => Template::findOrFail($id),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save(new Template, $request);
    }

    public function update($id, Request $request)
    {
        return $this->save(Template::findOrFail($id), $request);
    }

    private function save(Template $template, Request $request)
    {
        $this->validate($request, [
             'title' => 'required',
             'category_id' => 'required|int',
        ]);

        $template->title = $request->get('title');
        $template->category_id = $request->get('category_id');
        $template->supplier_id = $request->get('supplier_id');

        if ($request->hasFile('file')) {
            $ext = $request->file('file')->getClientOriginalExtension();
            if(in_array($ext, ['xls', 'xlsx', 'docx'])){

                if($template->file){
                    $template_path = storage_path() . '/app/' . $template->file->getPathAttribute();
                    $template_path_host = storage_path() . '/app/' . $template->file->getPathWithHostAttribute();


                    $pathes = [
                        1 => is_file($template_path) ? $template_path : false,
                        2 => is_file($template_path_host) ? $template_path_host : false
                    ];

                    if($pathkey = array_search(true, $pathes)){
                        unlink($pathes[$pathkey]);
                        $template->file->delete();
                    }
                }


                $file = (new FilesRepository)->makeFile($request->file, InsuranceCompanies::TEMPLATES_FOLDER);
                $template->file_id = $file->id;
                $template->save();
            }else{
                // "Шаблон не загружен. Некорректный формат. Поддерживаемые форматы: .xls, .xlsx, .docx";

            }

        }

        $template->save();

        return parentReload();
    }

    public function destroy($id)
    {
        Template::findOrFail($id)->delete();

        return response('', 200);
    }





}
