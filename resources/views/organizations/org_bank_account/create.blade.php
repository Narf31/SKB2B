@extends('layouts.frame')

@section('title')

    Банковские реквизиты

@stop

@section('content')


            {{ Form::open(['url' => url('/directories/organizations/org_bank_account'), 'method' => 'post', 'class' => 'form-horizontal']) }}

            @include('organizations.org_bank_account.form')

            {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop