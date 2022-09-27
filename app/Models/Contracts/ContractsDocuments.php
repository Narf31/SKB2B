<?php

namespace App\Models\Contracts;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;


class ContractsDocuments extends Model
{

    const FILES_DOC = 'contracts/docs';

    protected $table = 'contracts_documents';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    public static function getPath($contract_id)
    {
        $path = '/app/' . ContractsDocuments::FILES_DOC .'/'.(int)($contract_id/1000)."/{$contract_id}/";

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
