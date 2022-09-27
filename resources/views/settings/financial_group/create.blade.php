@extends('layouts.frame')


@section('title')

    {{ trans('menu.financial_policy') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/financial_policy'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.financial_group.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
