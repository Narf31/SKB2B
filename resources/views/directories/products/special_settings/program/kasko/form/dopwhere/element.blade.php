@if($group['control']['type'] == 'select')

    <div class="form-group">
        <label class="col-sm-4 control-label">Значения</label>
        <div class="col-sm-8">
            {{Form::select("value", collect($group['control']['value']), ($coefficient)?$coefficient->value:'', ['class' => 'form-control select2-all'])}}
        </div>
    </div>



@endif

@if($group['control']['type'] == 'range')

    <div class="form-group">
        <label class="col-sm-4 control-label">Больше или равно</label>
        <div class="col-sm-8">
            {{ Form::text('value_to', ($coefficient)?$coefficient->value_to:'', ['class' => 'form-control '.$group['control']['to']]) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Меньше или равно</label>
        <div class="col-sm-8">
            {{ Form::text('value_from', ($coefficient)?$coefficient->value_from:'', ['class' => 'form-control '.$group['control']['from']]) }}
        </div>
    </div>

@endif
