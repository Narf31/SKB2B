@extends('layouts.frame')

@section('title')

    {{ trans('menu.type_org') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$type_org->id}}', 7, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($type_org, ['url' => url("/settings/type_org/$type_org->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.type_org.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/type_org/', '{{ $type_org->id }}')">{{ trans('form.buttons.delete') }}</button>


    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

