@extends('layouts.frame')

@section('title')

    {{ trans('menu.payment_methods') }}
    <span class="btn btn-info pull-right" onclick="openLogEvents('{{$pay_method->id}}', 6, 0)"><i class="fa fa-history"></i> </span>


@endsection

@section('content')

    {{ Form::model($pay_method, ['url' => url("/settings/payment_methods/$pay_method->id"), 'method' => 'put', 'class' => 'form-horizontal', 'files' => true]) }}

    @include('settings.payment_methods.form')

    {{Form::close()}}

@endsection

@section('footer')

    <button class="btn btn-danger pull-left" onclick="deleteItem('/settings/payment_methods/', '{{ $pay_method->id }}')">{{ trans('form.buttons.delete') }}</button>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@endsection

