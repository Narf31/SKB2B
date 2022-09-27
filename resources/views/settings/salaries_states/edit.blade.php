@extends('layouts.frame')

@section('title')

    {{ trans('menu.salaries_states') }}

@stop

@section('content')

    {{ Form::model($state, ['url' => url("/settings/salaries_states/$state->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.salaries_states.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/salaries_states/', '{{ $state->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop