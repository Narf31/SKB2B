@extends('layouts.frame')

@section('title')

Версия интеграции

@stop

@section('content')

{{ Form::model($version ?? '', ['url' => url("/settings/system/integration/".$integration->id."/add_version"), 'method' => 'post', 'class' => 'form-horizontal']) }}

@include('settings.system.integration.version.form')

{{Form::close()}}

@stop

@section('footer')


<button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.add') }}</button>

@stop

