@extends('layouts.frame')


@section('title')

    {{ trans('menu.salaries_states') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/salaries_states'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.salaries_states.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop