@extends('layouts.app')

@section('head')

@append

@section('content')


                <h1>Добро пожаловать!</h1>
                <ul class="breadcrumb"><li><a href="/">Главная</a></li>
                    <li><a href="/">Добро пожаловать!</a></li>
                </ul>
                <p>
                    Добро пожаловать на стартовую страницу системы B2B! <br><br><br>В верхнем и боковом меню (значок &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button style="display: inline-block;float: none;position: absolute;margin-left:-54px;margin-top: -15px;" type="button" class="navbar-toggle">
                        <span style="height: 5px;width: 38px;background-color: #444;" class="icon-bar"></span>
                        <span style="height: 5px;width: 38px;background-color: #444;" class="icon-bar"></span>
                        <span style="height: 5px;width: 38px;background-color: #444;" class="icon-bar"></span>
                    </button>
                    в левом верхнем углу) вы можете выбрать любой из доступных разделов системы.
                </p>



    @if (session('login-success') && !count($errors))
        @include('partials.success-login')
    @endif

@endsection


