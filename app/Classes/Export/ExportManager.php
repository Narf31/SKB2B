<?php

namespace App\Classes\Export;


use App\Classes\Export\Replacers\ExcelReplacer;
use App\Classes\Export\Replacers\WordReplacer;
use App\Models\Directories\BsoSuppliers;
use App\Models\Settings\TemplateCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use PHPExcel_IOFactory;

class ExportManager{


    protected $builder;
    protected $category;
    protected $supplier_id;

    public function __construct(TemplateCategory $category, Builder $builder, $supplier_id = false){
        $this->builder = $builder;
        $this->category = $category;
        $this->supplier_id = $supplier_id;
        ExportProcess::getProcess()->set('category', $category->code);
        $this->set_ini();
    }

    public function handle(){

        //по билдеру определяем класс тегов для модели, если нет, то ошибка и возврат
        if(!$tag_model = $this->get_tag_model()){
            return $this->not_provided();
        }

        //если пришли через shift, то показываем доку
        if(request('documentation') == 1){
            return $this->documentation();
        }

        //запрашиваем шаблоны
        if(!$template = $this->get_template()){
            //возвращаем ошибку если нет шаблона
            return $this->no_template();
        }

        //если категория позволяет выбрать и шаблонов доступно более 1
        if($this->category->has_choise == 1 && $template->count() > 1){
            return $this->dialog(); // коллбэк на модалку
        }

        //если нельзя выбрать или пришёл конкретный шаблон, берём его, или последний из списка
        $template = $template instanceof Collection ? $template->last() : $template;


        //в зависимости от хоста файлы хранятся в разных папках, поэтому предполагаем - или там или там
        $template_path = storage_path() . '/app/' . $template->file->getPathAttribute();
        $template_path_host = storage_path() . '/app/' . $template->file->getPathWithHostAttribute();

        //проверяем где по факту файл
        $pathes = [
            is_file($template_path) ? $template_path : false,
            is_file($template_path_host) ? $template_path_host : false
        ];

        //ни в одном месте - ошибку
        if(($pathkey = array_search(true, $pathes)) === false){
            return $this->broken_template();
        }else{
            $path = $pathes[$pathkey];
        }

        //если есть шаблон, забираем реплейс массив
        $replace_arr = (new $tag_model($this->builder))->apply();

        //реплейсим и отдаем
        if(in_array($template->file->ext, ['xlsx', 'xls'])){
            $excel = ExcelReplacer::replace($path, $replace_arr);
            $excel->export($template->file->ext);

        }elseif(in_array($template->file->ext, ['docx'])){

            $word = WordReplacer::replace($path, $replace_arr);

            if($this->category->output_extension == 1){
                WordReplacer::outputPDF($word);
            }else{
                WordReplacer::output($word);
            }
        }

    }


    protected function dialog(){

        $template = $this->get_template();
        $data = [
            'templates' => $template->pluck('title', 'id')->toArray(),
            'title' => $this->category->title,
            'url' =>  request()->url(),
        ];

        if(!request('url', false)){ //если стучатся по нажатию кнопки
            return back()->with('callback', 'openFancyBoxFrame("'.url( $data['url']."?".http_build_query($data)).'")');

        }else{ // если зашли во фрейм для выбора шаблона
            return view('export.export_window', $data);
        }

    }


    protected function get_tag_model(){
        $model_class = get_class($this->builder->getModel());

        $tag_model = defined("{$model_class}::TAG_MODEL") ? $model_class::TAG_MODEL : false;

        return $tag_model;
    }


    protected function get_template(){

        //Все шаблоны этой категории
        $templates = $this->category->templates();

        // если для категории грузят шаблоны с указанием поставщика
        if($this->category->has_supplier == 1 && $this->supplier_id){
            $templates->whereIn('supplier_id', [$this->supplier_id,0]);
        }

        // если в категории доступен выбор шаблона
        if($this->category->has_choise == 1){

            // если уже пришли с указанием конкретного шаблона(выбрали в модалке)
            if(request()->has('_template_id')){
                $templates->where('id', (int)request('_template_id'));
            }

            $template = $templates->get();

            // если шаблоны нашлись
            if($template->count()){
                return $template;
            }

        }else{
            //если выбор не доступен - берём последний
            if($template = $templates->get()->last()){
                return $template;
            }
        }
        return false;
    }


    protected function no_template(){

        $titles = $this->category->hierarchy()->pluck('title')->toArray();
        $msg = "Необходимо добавить шаблон в категорию " . implode(" > ", $titles);
        if($this->supplier_id){
            $supplier = BsoSuppliers::query()->where('id', $this->supplier_id)->first();
            $msg .= ' для поставщика "'.$supplier->title.'", либо "Универсальный"';
        }
        return back()->with('error', $msg);
    }

    protected function broken_template(){
        return back()->with('error', "Шаблон не пригоден для выгрузки. Попробуйте загрузить этот же или новый шаблон в настройках для данной категории.");
    }


    protected function not_provided(){
        $msg = "На данный момент действие не предусмотрено. Обратитесь к разработчикам";
        return back()->with('error', $msg);
    }

    protected function documentation(){

        $model = $this->get_tag_model();
        return view('export.documentation', [
            'category' => $this->category,
            'doc' => $model::doc()
        ]);
    }

    protected function set_ini(){
        ini_set('memory_limit', '512M');

    }

}