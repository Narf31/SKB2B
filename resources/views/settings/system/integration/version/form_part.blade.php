
<label class="col-sm-4 control-label">{{$field['label'] ?? 'None'}}</label>
<div class="col-sm-8">

    @if(isset($field['type']))
    @switch($field['type'])

    @case('text')
    {{ Form::text($field['name'] ?? '', $formValues[$field['name']]['value'] ?? '', ['class' => 'form-control '.(isset($messages) && isset($messages[$field['name'] ?? '']) ? 'form-error' : ''), $field['required'] ?? '', 'placeholde' => $field['placeholder'] ?? '']) }}
    @break

    @default
    form type unknown
    @endswitch
    @else
    <p>Не заполнен тип формы</p>
    @endif

</div>

<div class="col-sm-12">
    <p style="padding-top:10px;">{{$field['description'] ?? ''}}</p>
</div>