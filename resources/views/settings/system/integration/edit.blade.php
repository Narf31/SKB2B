@extends('layouts.frame')

@section('title')

Интеграция

@stop

@section('content')

{{ Form::model($integration ?? '', ['url' => url("/settings/system/integration/". $integration->id), 'method' => 'post', 'class' => 'form-horizontal']) }}

@include('settings.system.integration.form')

{{Form::close()}}

@stop

@section('footer')

<button class="btn btn-danger pull-left" onclick="removeIntegration('{{$integration->id}}')">{{ trans('form.buttons.delete') }}</button>


<button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>
<script>

    function removeIntegration(id) {

    if (!customConfirm()) {
    return false;
    }
    var url = '{{ url("/settings/system/integration/") }}';
    var deleteUrl = url + '/' + id;
    $.post(deleteUrl, {
    _method: 'DELETE'
    }, function () {
    parent_reload();
    });
    }

</script>
@stop

