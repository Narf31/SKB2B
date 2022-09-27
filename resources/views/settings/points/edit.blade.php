@extends('layouts.frame')

@section('title')

    {{ trans('menu.points_sale') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$point->id}}', 3, 0)"><i class="fa fa-history"></i> </span>

@stop

@section('content')

    {{ Form::model($point, ['url' => url("/settings/points_sale/$point->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.points.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/points_sale/', '{{ $point->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

