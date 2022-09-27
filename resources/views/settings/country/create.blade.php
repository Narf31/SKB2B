@extends('layouts.frame')


@section('title')

    {{ trans('menu.country') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/country'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.country.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
