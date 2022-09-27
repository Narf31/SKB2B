<?php

namespace App\Models\Organizations;

use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class OrganizationScan extends Model {

    const FILES_DOC = 'users/scans';

    protected $table = 'org_scans';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function file() {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

}
