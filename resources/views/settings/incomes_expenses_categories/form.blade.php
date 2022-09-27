<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/incomes_expenses_categories.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('incomes_expenses_categories'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/incomes_expenses_categories.type') }}</label>
    <div class="col-sm-8">
        {{ Form::select('type', collect(\App\Models\Settings\IncomeExpenseCategory::TYPE), old('is_actual'), ['class' => 'form-control select2-ws', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/incomes_expenses_categories.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
    </div>
</div>