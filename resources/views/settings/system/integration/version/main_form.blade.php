@extends('layouts.frame')

@section('title')

Данные формы {{$integration->title}} - {{$version->title}}

@stop

@section('content')

{{ Form::model($formValues ?? '', ['url' => url("/settings/system/integration/". $integration->id."/edit/".$version->id."/main_form"), 'method' => 'post', 'class' => 'form-horizontal']) }}

<input type="hidden" name="integration_id" value="{{$integration->id}}">
<input type="hidden" name="version_id" value="{{$version->id}}">
<div class="form-group">
    @foreach($form as $field)
    @include('settings.system.integration.version.form_part')
    @endforeach
</div>




{{Form::close()}}

@stop

@section('footer')

<button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>




@stop
