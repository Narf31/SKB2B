@extends('layouts.frame')

@section('title')

    {{ trans('menu.financial_policy') }}

@stop

@section('content')

    {{ Form::model($financialPolicy, ['url' => url("/settings/financial_policy/$financialPolicy->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.financial_policy.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/financial_policy/', '{{ $financialPolicy->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
