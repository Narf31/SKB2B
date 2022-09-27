@extends('layouts.frame')


@section('title')

    {{ trans('menu.payment_methods') }}

@endsection

@section('content')


    {{ Form::open(['url' => url('/settings/payment_methods'), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) }}

    @include('settings.payment_methods.form')

    {{Form::close()}}


@endsection

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@endsection
