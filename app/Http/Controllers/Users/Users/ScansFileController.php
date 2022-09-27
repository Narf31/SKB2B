<?php

namespace App\Http\Controllers\Users\Users;

use App\Http\Controllers\Controller;
use App\Repositories\FilesRepository;
use App\Models\Users\Scan;

class ScansFileController extends Controller {

    public function __construct(FilesRepository $filesRepository) {
        $this->filesRepository = $filesRepository;
        $this->middleware('permissions:users,users');
    }

    public function deleteScans($id, $file_id) {
        $scans = Scan::where("user_id", $id)->whereHas('file', function ($query) use ($file_id) {
            return $query->where('name', '=', $file_id);
        });


        if ($scans) {
            if ($scans->delete() && app()->make('\App\Http\Controllers\FilesController')->callAction('destroy', [$file_id])) {
                return 1;
            }
        }
    }

}
