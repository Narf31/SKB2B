@extends('layouts.app')

@section('content')

    <div class="row">

        {{ Form::model($user, ['url' => url("/users/users/$user->id"), 'method' => 'patch', 'files' => true]) }}

        @include('users.users.form')

        {{ Form::close() }}

    </div>

    <div class="row">


        @include('users.users.partials.scans')
    </div>

@stop