<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model {

    const URL = '/files';
    const PREVIEW_URL = '/thumbs';

    protected $table = 'files';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getPathAttribute() {
        return $this->folder . "/" . $this->name . "." . $this->ext;
    }

    public function getUrlAttribute() {
        return self::URL . "/" . $this->name;
    }

    public function getPreviewAttribute() {
        return self::PREVIEW_URL . "/" . $this->name;
    }

    public function getFolderWithHostAttribute() {
        return $this->host . '/' . $this->folder;
    }

    public function getPathWithHostAttribute() {
        return $this->folder_with_host . "/" . $this->name . "." . $this->ext;
    }

    public function getPrefix() {
        return [
            substr($this->name, 4, 2),
            substr($this->name, 6, 2),
        ];
    }

    public static function extView($ext)
    {
        $view_type = array('jpg'=>1,'jpeg'=>1,'png'=>1);
        $access_type = array('pdf'=>1,'doc'=>1,'docx'=>1,'xls'=>1,'xlsx'=>1);

        if(isset($view_type[$ext]) && (int)$view_type[$ext] == 1) return 'view';
        if(isset($access_type[$ext]) && (int)$access_type[$ext] == 1) return $ext;

        return 'unknown';
    }

}
