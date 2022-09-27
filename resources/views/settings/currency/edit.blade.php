@extends('layouts.frame')

@section('title')

    {{ trans('menu.currency') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$currency->id}}', 6, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($currency, ['url' => url("/settings/currency/$currency->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.currency.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/currency/', '{{ $currency->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

