@extends('layouts.frame')


@section('title')

    {{ trans('menu.citys') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/citys'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.citys.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
