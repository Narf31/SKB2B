@extends('layouts.frame')


@section('title')

    {{ trans('menu.filials') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/filials'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.filials.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop


