<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <meta name="description" content="Система для страховых компаний"/>
        @yield('head')

        <link rel="shortcut icon" href="/favicon.ico">
        {{-- без этого не работает нормально навбар --}}
        <link rel="stylesheet" type="text/css" href="/assets/new/lib/bootstrap/css/bootstrap.css">

        {{-- а это ток который был на старом дизайне. он лучше и ведёт себе адекватнее.
        надо либо переносить точечно необходимые стили из первого или оставлять так,
        чтоб он перекрывал неадекват от первого --}}
        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">

        <link rel="stylesheet" type="text/css" href="/assets/new/css/font.css">
        <link rel="stylesheet" type="text/css" href="/assets/new/css/main.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        @include('layouts.css')

    </head>
    <body class="introduction-farm">
        <!-- SIDEBAR -->
        <nav id="sidebar">
            <div id="dismiss">
                <i class="glyphicon glyphicon-arrow-left"></i>
            </div>
            <div class="sidebar-header">
                <h3>B2B Демо версия</h3>
            </div>
            @include('partials.menu')

        </nav>

        <div class="overlay"></div>
        <div id="header" class="container-fluid">
            <div class="nav-container">
                <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
                    <div class="navbar-header">
                        <button id="sidebarCollapse" type="button" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a href="/" class="logo"></a>


                        <div id="search-box">
                            <div class="hidden-xs hidden-sm ">
                                <div class="input-group">
                                    <form action="/search" id="my_search" method="get">
                                        <input type="text" name="find" class="form-control" value="{{session()->get('search.find')?:''}}" />
                                    </form>
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" onclick="$('#my_search').submit()">
                                            Найти
                                        </button>
                                    </div>
                                </div>
                                <small>
                                    <span class="search-note">Договор,
                                        <a class="link link-grey" alt="Найти">XXX 0000</a> или <a class="link link-grey" alt="Найти">ФИО страхователя</a>
                                    </span>
                                </small>
                            </div>
                        </div>

                    </div>
                </nav>
                <div id="messages">
                    @include('layouts.messages')

                </div>

                <div class="header-top-right">
                    @include('layouts.notifications')
                    <div id="userData" class="user-data">
                        <a href="#" class="user-name"> {{ auth()->user()->name }} </a>
                        <div class="user-company">{{ auth()->user()->organization()->exists() ? auth()->user()->organization->cutTitle() : ''}}</div>
                        <div class="user-data-container">
                            <div class="user-description">
                                <span class="role-name">Размер шрифта</span>
                                <hr>
                                <div class="role-description">
                                    <input onchange="setUserTextSize(this)" type="range" class="custom-range" value="{{auth()->user()->text_size}}" min="15" max="24"
                                            id="customRange2">

                                </div>
                            </div>
                            <ul>
                                <li>
                                    <a href="#" onclick="introJs('.introduction-farm').start();">
                                        <span class="glyphicon glyphicon-book"></span>Инструкция
                                    </a>
                                </li>
                                <li>
                                    <a class="fancybox fancybox.iframe" href="{{url("/account/password")}}">
                                        <span class="glyphicon glyphicon-cog"></span>Сменить пароль
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div title="выйти из системы" class="user-logout">
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">&nbsp;</a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="container" class="container-fluid">
            <div class="wrapper">
                <div class="cont-in cont-in-mcontent">

                    <div class="content">

                        @if(isset($breadcrumbs))
                        <div class="page-heading">
                            {!! breadcrumb($breadcrumbs) !!}
                        </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        <div class='loader hidden' ></div>
        @if( request()->route()->uri() == "/")
        <div id="footer">
            <div class="footer-in">
                <div class="cont-in">
                    <div class="foot-right">
                        <div class="copyright">© 2019 - {{date("Y")}} Все права защищены.</div>
                        <div class="rights">
                            <a href="#" target="_blank">Условия использования</a>&nbsp;&nbsp;
                            <a href="#" target="_blank">Политика&nbsp;конфиденциальности</a>
                        </div>
                        <div class="mail"></div>
                    </div>
                    <div class="foot-des"><p>По всем вопросам обращайтесь </p>
                        <p>Для оперативности решения технических ошибок, при обращении указывайте номер полиса и/или Ф.И.О. клиента, описание проблемы, а так же PrintScr.</p>
                        <p><a href="https://solidsk.ru/" target="_blank">Страховая компания ***</a></p>
                    </div>
                </div>
            </div>
        </div>
        @endif


        @include('layouts.js')
        <script src="/js/jquery.easyui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">
        @yield('js')

        @yield('footer')





    </body>
</html>
