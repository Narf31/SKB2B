<?php

namespace App\Models\Contracts\ObjectInsurer\LiabilityArbitrationManager;

use App\Models\BSO\BsoItem;
use App\Models\Directories\InsuranceCompanies;
use App\Models\File;
use App\Models\Security\Security;
use App\Models\User;
use App\Models\Vehicle\VehicleMarks;
use App\Models\Vehicle\VehicleModels;
use App\Models\Vehicle\VehicleCategories;
use App\Models\Vehicle\VehiclePurpose;
use App\Traits\Models\GetRelatedTrait;
use Illuminate\Database\Eloquent\Model;


class LADocuments extends Model
{

    const STATUS = [
        0 => 'Новый',
        1 => 'Забракован',
        2 => 'На доработку',
        3 => 'Утвержден',
    ];

    const FILES_DOC = 'contracts/docs/';

    protected $table = 'la_documents';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public static function getPath($contract_id)
    {
        $path = '/app/' . LADocuments::FILES_DOC .'/'.(int)($contract_id/1000)."/{$contract_id}/";

        $_temp_path = explode('/', $path);
        $__path_dir = storage_path().'/';
        for ($i=1;$i<count($_temp_path)-1;$i++){
            $__path_dir .= $_temp_path[$i].'/';
            if(!is_dir($__path_dir)){
                mkdir($__path_dir, 0777, true);
            }

        }
        return $path;
    }


}
