@extends('layouts.frame')

@section('title')

Версия интеграции

@stop

@section('content')

{{ Form::model($version ?? '', ['url' => url("/settings/system/integration/". $integration->id."/edit/".$version->id), 'method' => 'post', 'class' => 'form-horizontal']) }}

@include('settings.system.integration.version.form')

{{Form::close()}}

@stop

@section('footer')

<button class="btn btn-danger pull-left" onclick="removeVersion('{{$version->id}}')">{{ trans('form.buttons.delete') }}</button>


<button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>


<script>

    function removeVersion(id) {

        if (!customConfirm()) {
            return false;
        }
        var url = '{{ url("/settings/system/integration/". $integration->id."/delete/") }}';
        var deleteUrl = url + '/' + id;
        $.post(deleteUrl, {
            _method: 'DELETE'
        }, function () {
            parent_reload();
        });
    }

</script>




@stop
