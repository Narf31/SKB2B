@extends('layouts.frame')


@section('title')

    {{ trans('menu.points_sale') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/points_sale'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.points.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
