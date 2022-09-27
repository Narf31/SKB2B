<?php

namespace App\Http\Controllers;

use App\Models\File;
use Storage;
use Image;

class FilesController extends Controller {

    public function show($fileName) {
        $file = File::whereName($fileName)->firstOrFail();

        /* Хук для локальной версии */
        $url = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], $file->path_with_host), '/');

        $original_name = $file->original_name;
        //$original_name = str_replace(' ', '-', $original_name);

        $original_name = htmlspecialchars($original_name);


        if (Storage::exists($url)) {
            return response(Storage::get($url), 200, [
                "Content-Type" => Storage::mimeType($url),
                'Content-Disposition' => "inline; filename={$original_name}"
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Для превью
     * @param type $fileName
     * @return boolean
     */
    public function thumb($fileName) {
        $file = File::whereName($fileName)->firstOrFail();

        /* Хук для локальной версии */
        $url = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], $file->path_with_host), '/');

        if (in_array($file->ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $prefix = $file->getPrefix();
            $thumb = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], 'thumb/' . implode('/', $prefix) . '/' . $file->name . '.' . $file->ext), '/');
            if (!Storage::exists($thumb)) {
                $img = Image::make(storage_path() . '/app/' . $url);
                $img->resize(null, 100, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                if (!is_dir(storage_path() . '/app/thumb/' . $prefix[0])) {
                    mkdir(storage_path() . '/app/thumb/' . $prefix[0], 0755, true);
                }
                if (!is_dir(storage_path() . '/app/thumb/' . $prefix[0] . '/' . $prefix[1])) {
                    mkdir(storage_path() . '/app/thumb/' . $prefix[0] . '/' . $prefix[1], 0755, true);
                }
                if (!$img->save(storage_path() . '/app/' . $thumb)) {
                    abort(404);
                }
            }
            return response(Storage::get($thumb), 200, [
                "Content-Type" => Storage::mimeType($thumb),
                'Content-Disposition' => "inline; filename={$file->original_name}"
            ]);
        } else {
            abort(404);
        }
    }

    public function destroy($fileName) {

        $file = File::whereName($fileName)->firstOrFail();

        /* Хук для локальной версии */
        $url = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], $file->path_with_host), '/');
        $prefix = $file->getPrefix();
        $thumb = ltrim(str_replace(['//', '127.0.0.1'], ['/', ''], 'thumb/' . implode('/', $prefix) . '/' . $file->name . '.' . $file->ext), '/');

        if (Storage::exists($url)) {
            Storage::delete($url);
            $file->delete();
        }

        if (Storage::exists($thumb)) {
            Storage::delete($thumb);
        }
        return response('', 200);
    }

}
