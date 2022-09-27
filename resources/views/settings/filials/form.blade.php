
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/departments.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">АИС ID</label>
    <div class="col-sm-8">
        {{ Form::text('ais_id', old('ais_id'), ['class' => 'form-control', 'required']) }}
    </div>
</div>