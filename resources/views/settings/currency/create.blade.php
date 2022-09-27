@extends('layouts.frame')


@section('title')

    {{ trans('menu.currency') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/currency'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.currency.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
