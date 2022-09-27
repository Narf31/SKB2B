@extends('layouts.frame')

@section('title')

    {{ trans('menu.user_balance') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$balance->id}}', 6, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($balance, ['url' => url("/settings/user_balance/$balance->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.user_balance.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/user_balance/', '{{ $balance->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

