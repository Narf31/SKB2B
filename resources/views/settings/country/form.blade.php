
<div class="form-group">
    <label class="col-sm-4 control-label">Название RU</label>
    <div class="col-sm-8">
        {{ Form::text('title_ru', old('title_ru'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Название EN</label>
    <div class="col-sm-8">
        {{ Form::text('title_en', old('title_en'), ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Шенген</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_schengen', 1, old('is_schengen')) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">ISO-код страны</label>
    <div class="col-sm-8">
        {{ Form::text('code', old('code'), ['class' => 'form-control', 'required']) }}
    </div>
</div>