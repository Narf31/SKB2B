@extends('layouts.frame')

@section('title')

    Банковские реквизиты

@stop

@section('content')



{{ Form::model($org_bank_account, ['url' => url("/directories/organizations/org_bank_account/$org_bank_account->id"), 'method' => 'put', 'class' => 'form-horizontal']) }}

@include('organizations.org_bank_account.form')

{{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop