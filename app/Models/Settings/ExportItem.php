<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;


class ExportItem extends Model
{
    protected $table = 'export_items';

    protected $guarded = [];

    public $timestamps = false;

    const TYPE = [
        1 => 'Акт',
        2 => 'Отчёт',
    ];


    public function templates()
    {
        return $this->hasMany(Template::class, 'export_item_id', 'id');
    }


}
