@extends('layouts.frame')


@section('title')

    {{ trans('menu.type_org') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/type_org'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.type_org.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
