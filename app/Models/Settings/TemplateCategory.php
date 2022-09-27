<?php

namespace App\Models\Settings;


use App\Classes\Common\Tree;
use Illuminate\Database\Eloquent\Model;


/**
 * @property mixed has_choise
 * @property mixed title
 */
class TemplateCategory extends Model
{
    protected $table = 'template_categories';

    protected $guarded = ['id'];

    public $timestamps = false;

    const OUTPUT_EXTENSION = [
        0 => 'Исходное',
        1 => 'PDF'
    ];


    public function templates()
    {
        return $this->hasMany(Template::class, 'category_id');
    }

    public function parent(){
        return $this->hasOne(TemplateCategory::class, 'id', 'parent_id');
    }





    public function hierarchy(){
        $chain = collect([$this]);
        $cat = $this;
        while($cat->parent){
            $chain->push($cat->parent);
            $cat = $cat->parent;
        }
        return $chain->reverse();

    }


    public static function get_all_tree(){
        return (new Tree())
            ->set_array(self::query()->where('is_actual',1)->get()->toArray())
            ->set_tree_mode(false)
            ->set_links_mode(true)
            ->set_level_key('_level')
            ->build();
    }



    public static function get($code){
        return TemplateCategory::query()->where('code', $code)->where('is_actual',1)->first();
    }



}
