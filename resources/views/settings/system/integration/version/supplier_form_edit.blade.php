@extends('layouts.frame')

@section('title')

Данные формы

@stop

@section('content')

{{ Form::model($formValues ?? '', ['url' => url("/directories/insurance_companies/".$hold_kv->insurance_companies_id."/bso_suppliers/".$hold_kv->bso_supplier_id."/hold_kv/".$hold_kv->id."/supplier_form/".$version_id), 'method' => 'post', 'class' => 'form-horizontal']) }}

@php
foreach($versions as $i => $version){
$versions[$i]['text'] = $version->integration->title. '  '.$version->title;
}

@endphp


<div class="form-group">
    <label class="col-sm-4 control-label">Выберите интеграцию</label>
    <div class="col-sm-8">
        {{ Form::select('version_id', $versions->prepend(['text' => 'Не выбрано'])->pluck('text','id'), $version_id, ['id' => 'version_id', 'class' => 'form-control', 'required', 'onchange' => 'getForm();']) }}
    </div>
</div>
<div class="form-group">
    <div id="form-part" style="height:300px;"></div>
</div>



{{Form::close()}}

@stop

@section('footer')

<button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

<script>



    document.addEventListener("DOMContentLoaded", function (event) {
        $('#version_id').change();
    });

    function getForm() {
        $.post('{{url("/directories/insurance_companies/".$hold_kv->insurance_companies_id."/bso_suppliers/".$hold_kv->bso_supplier_id."/hold_kv/".$hold_kv->id."/supplier_select_form")}}', {'id': $('#version_id').val(), _token: '{{csrf_token()}}', 'formValues': '{{base64_encode(serialize($formValues))}}'}, function (res) {
            $('#form-part').html(res);
        }).done(function () {

        }).fail(function () {
            $('#form-part').html('');
        }).always(function () {

        });
    }

</script>


@stop
