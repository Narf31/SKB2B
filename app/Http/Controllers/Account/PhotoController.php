<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\FilesRepository;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PhotoController extends Controller {

	protected $filesRepository;

	public function __construct(FilesRepository $filesRepository) {
		$this->filesRepository = $filesRepository;
	}

	public function store(Request $request) {

		if ( ! $request->hasFile('image') || ! $this->checkIsImage($request->image)) {
			return json_encode([
				'success' => false
			]);
		}

		auth()->user()->image()->associate($this->filesRepository->makeFile($request->image, User::FILES_FOLDER));

		auth()->user()->smallImage()->associate($this->filesRepository->makeResizedImage($request->image, User::FILES_FOLDER));

		auth()->user()->save();

		return json_encode([
			'success' => true
		]);
	}

	private function checkIsImage(UploadedFile $uploadedFile) {
		return substr($uploadedFile->getMimeType(), 0, 5) == 'image';
	}

}
