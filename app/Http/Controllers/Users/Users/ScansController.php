<?php

namespace App\Http\Controllers\Users\Users;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\User;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;

class ScansController extends Controller {

	protected $filesFolder = 'users/scans';

	protected $filesRepository;

	public function __construct(FilesRepository $filesRepository) {
		$this->middleware('permissions:users,users');

		$this->filesRepository = $filesRepository;
	}

	public function store($userId, Request $request) {

	    $files = $this->filesRepository->makeFile($request->file, $this->filesFolder."/$userId/");

		User::findOrFail($userId)->scans()->save($files);

        LogEvents::event($userId, "Загрузка документа {$files->original_name}", 1);

        return response('', 200);
	}


}
