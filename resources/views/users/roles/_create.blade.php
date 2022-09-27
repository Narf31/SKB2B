@extends('layouts.app')

@section('content')



    <div class="col-md-12">

        <div class="col-md-6">

            {{ Form::open(['url' => '/users/roles', 'method' => 'post']) }}

            @include('users.roles.form')

            {{Form::close()}}

        </div>

    </div>

@stop
