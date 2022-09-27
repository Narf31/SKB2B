@extends('layouts.frame')

@section('title')

    {{ trans('menu.departments') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$department->id}}', 4, 0)"><i class="fa fa-history"></i> </span>

@stop

@section('content')

    {{ Form::model($department, ['url' => url("/settings/departments/$department->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.departments.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/departments/', '{{ $department->id  }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
