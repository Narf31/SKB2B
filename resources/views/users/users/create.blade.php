@extends('layouts.app')

@section('content')

    <div class="row">

        {{ Form::open(['url' => url('/users/users'), 'method' => 'post', "autocomplete" =>"off", 'files' => true]) }}

        @include('users.users.form')

        {{ Form::close() }}

    </div>


@stop
