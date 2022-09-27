@extends('layouts.frame')


@section('title')

    {{ trans('menu.banks') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/banks'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.banks.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
