@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">Редактирование роли пользователя</h1>
        <span class="btn btn-info pull-right" onclick="openLogEvents('{{$role->id}}', 0, 0)"><i class="fa fa-history"></i> </span>

    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <div class="block-main">
                {{ Form::model($role, ['url' => "/users/roles/$role->id", 'method' => 'patch', 'class' => 'form-horizontal']) }}

                @include('users.roles.form')

                {{ Form::close() }}

            </div>

        </div>
    </div>

@stop
