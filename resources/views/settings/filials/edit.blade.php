@extends('layouts.frame')

@section('title')

    {{ trans('menu.filials') }}

@stop

@section('content')

    {{ Form::model($filial, ['url' => url("/settings/filials/$filial->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.filials.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/filials/', '{{ $filial->id  }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
