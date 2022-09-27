@extends('layouts.frame')


@section('title')

    {{ trans('menu.departments') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/departments'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.departments.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop


