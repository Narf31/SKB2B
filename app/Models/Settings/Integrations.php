<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\IntegrationsVersions;

class Integrations extends Model {

    protected $table = 'integrations';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function versions() {
        return $this->hasMany(IntegrationsVersions::class, 'integration_id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($item) {
            $item->versions()->delete();
        });
    }

}
