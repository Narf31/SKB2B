@extends('layouts.frame')

@section('title')

Интеграция

@stop

@section('content')

{{ Form::model($integration ?? '', ['url' => url("/settings/system/integration"), 'method' => 'post', 'class' => 'form-horizontal']) }}

@include('settings.system.integration.form')

{{Form::close()}}

@stop

@section('footer')


<button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.add') }}</button>

@stop

