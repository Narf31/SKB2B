
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('banks'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
    </div>
</div>


<div class="form-group">
    <label class="col-sm-4 control-label">Тип</label>
    <div class="col-sm-8">
        {{ Form::select('type_id', \App\Models\Settings\UserBalanceSettings::TYPE, old('type_id'), ['class' => 'form-control select2-ws', 'required']) }}
    </div>
</div>