
<div class="form-group">
    <label class="col-sm-4 control-label">Название</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('banks'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Код</label>
    <div class="col-sm-8">
        {{ Form::text('code', old('code'), ['class' => 'form-control', 'required']) }}
    </div>
</div>


