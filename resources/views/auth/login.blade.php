@extends('layouts.auth')

@section('content')

    <form method="POST" action="{{ url('/login') }}" id="authorizationForm">
        <h1>Авторизация</h1>
        {{ csrf_field() }}
        <div class="form-row form-group">
            <div class="label-container">
                <label class="hidden">{{ trans('auth.login.login') }}</label>
            </div>
            <div>
                <input type="text" class="t-inp form-control" name="email" placeholder="{{ trans('auth.login.login') }}">
            </div>
        </div>
        <div class="form-row form-group">
            <div class="label-container">
                <label class="hidden">{{ trans('auth.login.password') }}</label>
            </div>
            <div>
                <input type="password" class="t-inp form-control" name="password" placeholder="{{ trans('auth.login.password') }}">
                <span onclick="toggleHidePassword(this);" title="Показать/скрыть пароль" class="glyphicon glyphicon-eye-open show-password"></span>
            </div>
        </div>
        <div class="az-bottom">
            <input type="submit" class="btn btn-primary" value="{{ trans('form.buttons.enter') }}">
        </div>
    </form>

@endsection
