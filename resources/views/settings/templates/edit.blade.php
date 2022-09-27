@extends('layouts.frame')

@section('title')

    {{ trans('menu.templates') }}

@stop

@section('content')

    {{ Form::model($template, ['url' => url("/settings/templates/$template->id"), 'method' => 'put', 'class' => 'form-horizontal', 'files' => true]) }}

    @include('settings.templates.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/templates/', '{{ $template->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop