<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Integrations;

class IntegrationsVersions extends Model {

    protected $table = 'integrations_versions';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function integration() {
        return $this->belongsTo(Integrations::class, 'integration_id');
    }

}
