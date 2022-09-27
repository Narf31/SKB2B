<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>


        @yield('head')



        @include('client.layouts.css')

    </head>

    <body>
    <div class="wrapper">
        <div class="header">
            <div class="container d-flex justify-content-between align-items-center">
                <a href="{{urlClient("/")}}" class="logo__title">
                    {{ config('app.name') }}
                </a>
                <div class="main__nav">
                    <ul>

                        @if(auth()->guard('client')->check())
                            <li>
                                <a href="{{urlClient("/profile")}}"><i class="fa fa-user-circle-o"></i> Мой профиль</a>
                            </li>

                        @endif
                        <li>
                            <a href="{{urlClient("/contracts")}}"><i class="fa fa-cube"></i> Оформить договор</a>
                        </li>
                        <li>
                            <a href="{{urlClient("/damages")}}"><i class="fa fa-briefcase"></i> Убытки</a>
                        </li>

                        @if(auth()->guard('client')->check())
                            <li>
                                <a href="{{urlClient("/logout")}}">Выход</a>
                            </li>
                        @else
                            <li>
                                <a href="{{urlClient("/login")}}"><i class="fa fa-sign-out"></i> Войти</a>
                            </li>
                        @endif

                    </ul>
                </div>
                <div class="nav__bars hidden-sm-up"><i></i></div>
            </div>
        </div>
        <div class="content">
            <div class="section sc__content">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="footer__box">
            <div class="container">
                <div class="contacts__list">
                    <div class="row row__custom">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 col__custom contacts__item">
                            <div class="contacts__item-city">
                                Москва
                            </div>
                            <div class="contacts__item-text">
                                <p>
                                    Россия, 115093, г. Москва, ул. Люсиновская, д. 27 стр. 3<br>

                                </p>
                                <p>
                                    <a href="tel:+74959747707">+7 (495) 123-36-80</a>
                                </p>


                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 col__custom contacts__item">
                            <div class="contacts__item-city">
                                АО «СГ «Страховая компания» создано в июле 2003 г.
                            </div>
                            <div class="contacts__item-text">
                                <p>
                                    Уставный капитал АО «СГ «Страховая компания» составляет в настоящее время 200 250 700 рублей.
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__box">
            <div class="container">
                <div class="rights">
                    © 2019
                </div>
                <div class="site">
                    <a href="#">{{ config('app.name') }}</a>
                </div>
            </div>
        </div>
    </div>


    @include('client.layouts.js')
    @yield('js')

    </body>
</html>
