<?php

namespace App\Models\Cashbox;

use App\Classes\Export\TagModels\Cashbox\TagIncomeExpense;
use App\Models\Settings\IncomeExpenseCategory;
use App\Models\User;
use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class IncomeExpense extends Model {

    use ActiveConstTrait;

    protected $table = 'incomes_expenses';
    protected $guarded = ['id'];
    public $timestamps = false;

    const TAG_MODEL = TagIncomeExpense::class;

    const STATUS = [
        1 => 'Создан',
        2 => 'Оплачен'
    ];
    const PAYMENT_TYPE = [
        0 => 'Наличные',
        1 => 'Безналичные',
    ];

    public function category() {
        return $this->hasOne(IncomeExpenseCategory::class, 'id', 'category_id');
    }

    public function created_user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function documents() {
        return $this->belongsToMany(File::class, 'incomes_expenses_documents', 'income_id', 'file_id');
    }



}
