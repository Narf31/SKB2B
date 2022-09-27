<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\IntegrationsVersions;

class IntegrationsVersionsSupplierFormValues extends Model {

    protected $table = 'integrations_versions_supplier_form_values';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function version() {
        return $this->belongsTo(IntegrationsVersions::class, 'version_id');
    }

}
