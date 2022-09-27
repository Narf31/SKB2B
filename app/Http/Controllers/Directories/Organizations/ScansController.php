<?php

namespace App\Http\Controllers\Directories\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organizations\Organization;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use App\Models\Organizations\OrganizationScan;

class ScansController extends Controller {

    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository) {
        $this->middleware('permissions:directories,organizations');

        $this->filesRepository = $filesRepository;
    }

    public function store($orgId, Request $request) {


        Organization::findOrFail($orgId)->scans()->save($this->filesRepository->makeFile($request->scan, Organization::FILES_DOC . "/$orgId/"));

        return response('', 200);
    }

    public function deleteScans($id, $file_id) {

        $scans = OrganizationScan::where("org_id", $id)->whereHas('file', function ($query) use ($file_id) {
            return $query->where('name', '=', $file_id);
        });


        if ($scans) {
            if ($scans->delete() && app()->make('\App\Http\Controllers\FilesController')->callAction('destroy', [$file_id])) {
                return 1;
            }
        }
    }

}
