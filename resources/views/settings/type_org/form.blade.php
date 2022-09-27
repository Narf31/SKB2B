
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Поставщик БСО</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_provider', 1, old('is_provider')) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Участник договора</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_contract', 1, old('is_contract')) }}
    </div>
</div>