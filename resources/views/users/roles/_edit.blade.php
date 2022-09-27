@extends('layouts.app')

@section('content')



    <div class="col-md-12">

        <div class="col-md-6">

            {{ Form::model($role, ['url' => "/users/roles/$role->id", 'method' => 'patch']) }}

            @include('users.roles.form')

            {{Form::close()}}

        </div>

    </div>

@stop
