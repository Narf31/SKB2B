<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Log\LogEvents;
use App\Models\Settings\IncomeExpenseCategory;
use Illuminate\Http\Request;

class IncomeExpenseCategoryController extends Controller{

    public function __construct(){
        $this->middleware('permissions:settings,incomes_expenses_categories');
    }

    public function index(){
        return view('settings.incomes_expenses_categories.index');
    }

    public function create(){
        return view('settings.incomes_expenses_categories.create');
    }

    public function edit($id){
        return view('settings.incomes_expenses_categories.edit', [
            'income_expense_category' => IncomeExpenseCategory::findOrFail($id),
        ]);
    }

    public function store(Request $request){
        $bank = new IncomeExpenseCategory;
        $bank->save();
        LogEvents::event($bank->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_CREATE], 20, 0, 0, $request->all());

        return $this->save($bank, $request);
    }

    public function update($id, Request $request){
        $bank = IncomeExpenseCategory::findOrFail($id);
        LogEvents::event($bank->id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_UPDATE], 20, 0, 0, $request->all());

        return $this->save($bank, $request);
    }

    private function save(IncomeExpenseCategory $bank, Request $request){

        $bank->title = $request->title;
        $bank->is_actual = (int)$request->is_actual;
        $bank->type = (int)$request->type;

        $bank->save();
        return parentReload();
    }

    public function destroy($id){
        LogEvents::event($id, LogEvents::EVENT_DEFAULT_NAME[LogEvents::LOG_DELETE], 20);

        IncomeExpenseCategory::findOrFail($id)->delete();

        return response('', 200);
    }


    public function get_list(){

        $incomes_expenses_categories = IncomeExpenseCategory::query()->orderBy('title');

        if(in_array((int)request()->get('type'), array_keys(IncomeExpenseCategory::TYPE))){
            $incomes_expenses_categories = $incomes_expenses_categories->where('type', '=', (int)request()->get('type'));
        }

        return [
            'incomes_expenses_categories' => $incomes_expenses_categories->get(),
        ];
    }

    public function get_table(){
        $data = $this->get_list();
        $data['html'] = view('settings.incomes_expenses_categories.table', $data)->render();
        return $data;


    }

}
