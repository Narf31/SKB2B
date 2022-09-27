@extends('layouts.frame')


@section('title')

    {{ trans('menu.user_balance') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/user_balance'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.user_balance.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
