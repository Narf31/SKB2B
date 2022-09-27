@extends('layouts.frame')


@section('title')

    {{ trans('menu.incomes_expenses_categories') }}

@stop

@section('content')


    {{ Form::open(['url' => url('/settings/incomes_expenses_categories'), 'method' => 'post', 'class' => 'form-horizontal']) }}

    @include('settings.incomes_expenses_categories.form')

    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
