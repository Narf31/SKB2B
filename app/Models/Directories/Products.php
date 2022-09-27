<?php

namespace App\Models\Directories;

use App\Models\Directories\Products\ProductsAssistanceInfo;
use App\Models\Directories\Products\ProductsFlatsRisks;
use App\Models\Directories\Products\ProductsOfficialDiscount;
use App\Models\Directories\Products\ProductsSpecialSsettings;
use App\Models\File;
use App\Models\Settings\ProgramsOnlineList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function GuzzleHttp\Promise\all;


class Products extends Model{

    protected $table = 'products';

    protected $guarded = ['id'];

    public $timestamps = false;

    const TEMPLATES_FOLDER = "products/template";


    const FIN_TYPE = [0=>'Процентная', 1=>'Фиксированная'];
    const INSPECTION_TEMPLE_ACT = [ '' =>'Нет'];
    const SLUG = [
        ''=>'нет',
        'nsPrisoners'=>'НС Заключенных',
        'mortgage' => 'Ипотека',
        'kasko'=>'КАСКО',
        'gap'=>'GAP',
        'dgo'=>'ДГО',
        'osago'=>'ОСАГО',
        'vzr'=>'ВЗР',
        'prf'=>'ПРФ',
        'migrants'=>'Мигранты',
        'arbitration'=>'Ответственность арбитражных управляющих',
    ];

    public function category() {
        return $this->hasOne(ProductsCategory::class, 'id', 'category_id');
    }

    public function programs()
    {
        return $this->hasMany(ProductsPrograms::class,  'product_id');
    }

    public function flats_risks()
    {
        return $this->hasMany(ProductsFlatsRisks::class,  'product_id')->orderBy('sort', 'asc');
    }

    public function special_settings_discount()
    {
        return $this->hasMany(ProductsOfficialDiscount::class,  'product_id');
    }

    public function optional_programs()
    {
        return $this->hasMany(ProductsPrograms::class,  'product_id')->get();
    }


    public function assistance_info()
    {
        return $this->hasMany(ProductsAssistanceInfo::class,  'product_id');
    }

    public function get_online_validator($data){

        $class_name = Str::ucfirst($this->slug);

        if($validator_class = "App\\Processes\\Validators\\Contracts\\Validate\\{$class_name}"){
            if(class_exists($validator_class)){
                return new $validator_class($data);
            }
        }

        return false;

    }

    public function get_control_view(){

        $class_name = Str::ucfirst($this->slug);
        if($view_class = "App\\Processes\\Validators\\Contracts\\View\\{$class_name}"){
            if(class_exists($view_class)){
                return new $view_class();
            }
        }

        return false;

    }

    public function template(){
        return $this->hasOne(File::class, 'id', "template_id");
    }

    public function template_contract(){
        return $this->hasOne(File::class, 'id', "template_contract_id");
    }

    public function template_statement(){
        return $this->hasOne(File::class, 'id', "template_statement_id");
    }



    public function special_settings()
    {
        return $this->hasOne(ProductsSpecialSsettings::class,  'product_id');
    }

    public function get_products_info($type)
    {
        return $this->hasMany(ProductsInfo::class,  'product_id')->where('type_id', $type)->orderBy('sort')->get();
    }




}
