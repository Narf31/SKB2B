<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Image;

class FilesRepository {

    private $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'tiff', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    protected $maxImageHeight = 90;

    /**
     * @param UploadedFile $uploadedFile
     * @param              $folder
     *
     * @return File
     */
    public function makeFile(UploadedFile $uploadedFile, $folder) {
        if (in_array($uploadedFile->getClientOriginalExtension(), $this->allowedExtensions)) {
            $file = $this->createFileInDB($uploadedFile, $folder);
            $uploadedFile->storeAs(str_replace('127.0.0.1', '', $file->folder_with_host), $file->name . '.' . $file->ext);
            return $file;
        } else {
            abort(403, 'Ошибка. Разрешенные расшерения: ' . implode(', ', $this->allowedExtensions) . '.');
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param              $folder
     *
     * @return File
     */
    public function makeResizedImage(UploadedFile $uploadedFile, $folder) {

        $file = $this->createFileInDB($uploadedFile, $folder);

        $img = Image::make($uploadedFile->getRealPath());

        $imageSizes = $this->getImageSize($img);

        $img->resize($imageSizes['width'], $imageSizes['height']);

        $img->save(storage_path('/app/' . $file->path_with_host));

        return $file;
    }

    private function createFileInDB(UploadedFile $uploadedFile, $folder) {
        return File::create([
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'ext' => $uploadedFile->getClientOriginalExtension(),
                    'folder' => $folder,
                    'name' => uniqid(),
                    'user_id' => auth()->guard('web')->check() ? auth()->id() : null,
                    'host' => request()->getHost()
        ]);
    }

    private function getImageSize(\Intervention\Image\Image $image) {

        $widthOriginal = $image->width();

        $heightOriginal = $image->height();

        $proportion = $widthOriginal / $heightOriginal;

        if ($heightOriginal > $this->maxImageHeight) {
            $widthOriginal = $proportion * $this->maxImageHeight;
            $heightOriginal = $this->maxImageHeight;
        }

        $sizes_array = [
            'width' => $widthOriginal,
            'height' => $heightOriginal
        ];

        return $sizes_array;
    }

}
