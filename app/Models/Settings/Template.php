<?php

namespace App\Models\Settings;

use App\Models\Directories\BsoSuppliers;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\Template
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $data
 * @property mixed file_id
 * @property mixed org_id
 * @property mixed supplier_id
 * @property mixed category_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Template whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Template whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Template whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Settings\Template whereData($value)
 * @mixin \Eloquent
 */
class Template extends Model
{
    protected $table = 'templates';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function file(){
        return $this->hasOne(File::class, 'id', "file_id");
    }

    public function category(){
        return $this->hasOne(TemplateCategory::class, 'id', 'category_id');
    }

    public function supplier(){
        return $this->hasOne(BsoSuppliers::class, 'id', 'supplier_id');
    }

}
