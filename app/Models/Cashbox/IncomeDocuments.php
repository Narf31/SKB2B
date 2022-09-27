<?php

namespace App\Models\Cashbox;

use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class IncomeDocuments extends Model {

    const FILES_DOC = 'incomes/docs';

    protected $table = 'incomes_expenses_documents';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function file() {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

}
