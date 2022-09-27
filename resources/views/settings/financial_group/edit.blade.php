@extends('layouts.frame')

@section('title')

    {{ trans('menu.financial_policy') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$financial_group->id}}', 5, 0)"><i class="fa fa-history"></i> </span>


@stop

@section('content')

    {{ Form::model($financial_group, ['url' => url("/settings/financial_policy/$financial_group->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

    @include('settings.financial_group.form')

    {{Form::close()}}

@stop

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/financial_policy/', '{{ $financial_group->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

