@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1>Создание роли пользователя</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="block-main">

                {{ Form::open(['url' => '/users/roles', 'method' => 'post', 'class' => 'form-horizontal']) }}

                @include('users.roles.form')

                {{Form::close()}}

            </div>

        </div>
    </div>

@stop
