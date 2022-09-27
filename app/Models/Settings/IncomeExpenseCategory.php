<?php

namespace App\Models\Settings;

use App\Traits\Models\ActiveConstTrait;
use Illuminate\Database\Eloquent\Model;

class IncomeExpenseCategory extends Model{

    use ActiveConstTrait;

    const TYPE = [
        1 => 'Доп доход',
        2 => 'Расход',
    ];


    protected $table = 'incomes_expenses_categories';

    protected $guarded = ['id'];

    public $timestamps = false;



}
