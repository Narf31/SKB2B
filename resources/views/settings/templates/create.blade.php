@extends('layouts.frame')


@section('title')

    {{ trans('menu.templates') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/templates'), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) }}

    @include('settings.templates.form')

    {{Form::close()}}


@stop

@section('footer')
    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>
@stop