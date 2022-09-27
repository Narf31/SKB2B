@extends('layouts.frame')

@section('title')

    {{ trans('menu.country') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$country->id}}', 6, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($country, ['url' => url("/settings/country/$country->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.country.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/country/', '{{ $country->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

