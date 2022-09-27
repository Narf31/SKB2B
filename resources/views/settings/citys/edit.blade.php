@extends('layouts.frame')

@section('title')

    {{ trans('menu.citys') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$city->id}}', 2, 0)"><i class="fa fa-history"></i> </span>

@stop

@section('content')

    {{ Form::model($city, ['url' => url("/settings/citys/$city->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.citys.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/citys/', '{{ $city->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

