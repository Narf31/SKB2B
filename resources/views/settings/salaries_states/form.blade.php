
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/salaries_states.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/salaries_states.prefix') }}</label>
    <div class="col-sm-8">
        {{ Form::text('prefix', old('prefix'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
