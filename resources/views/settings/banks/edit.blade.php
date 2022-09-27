@extends('layouts.frame')

@section('title')

    {{ trans('menu.banks') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$bank->id}}', 6, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($bank, ['url' => url("/settings/banks/$bank->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.banks.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/banks/', '{{ $bank->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

