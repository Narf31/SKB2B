<div class="form-group">
    <label class="col-sm-4 control-label">Название</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control '.(isset($messages) && isset($messages['title']) ? 'form-error' : ''), 'required']) }}
    </div>

    <label class="col-sm-4 control-label">Описание</label>
    <div class="col-sm-8">
        {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'required']) }}
    </div>

    <label class="col-sm-4 control-label">Активно</label>
    <div class="col-sm-8">
        {{ Form::checkbox('active', true, old('active')) }}
    </div>
</div>


