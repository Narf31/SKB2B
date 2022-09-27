@extends('layouts.app')

@section('content')


    <div class="row">

            {{ Form::open(['url' => url('/directories/organizations/organizations'), 'method' => 'post',  'class' => 'form-horizontal']) }}

            @include('organizations.organizations.form')

            {{Form::close()}}
    </div>


@stop
