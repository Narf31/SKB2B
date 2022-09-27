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


        <div class="overlay"></div>
        <div id="header" class="container-fluid">
            <div class="nav-container">
                <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
                    <div class="navbar-header">
                        <a href="/" class="logo"></a>
                        <div id="search-box">
                            <div class="hidden-xs hidden-sm ">

                            </div>
                        </div>

                    </div>
                </nav>
                <div id="messages">
                    @include('layouts.messages')

                </div>

                <div class="header-top-right">

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

        <div id="footer">
            <div class="footer-in">
                <div class="cont-in">
                    <div class="foot-right">
                        <div class="copyright">© 1996 - {{date("Y")}} Все права защищены.</div>
                        <div class="rights">
                            <a href="#" target="_blank">Условия использования</a>&nbsp;&nbsp;
                            <a href="https://sst.cat/policy/" target="_blank">Политика конфиденциальности</a>
                        </div>
                        <div class="mail">e-mail: <a href="mailto:info@riks-ins.ru">info@riks-ins.ru</a></div>
                    </div>
                    <div class="foot-des"><p>По всем вопросам обращайтесь <a href="mailto:support@riks-ins.ru">support@riks-ins.ru</a></p>
                        <p>Для оперативности решения технических ошибок, при обращении указывайте номер полиса и/или Ф.И.О. клиента, описание проблемы, а так же PrintScr (<a href="https://yandex.ru/support/common/support/screenshot.xml" target="_blank">инструкция</a>).</p>
                        <p><a href="https://riks-ins.ru/" target="_blank">ООО «РИКС»</a></p>
                    </div>
                </div>
            </div>
        </div>


        @include('layouts.js')
        <script src="/js/jquery.easyui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">
        @yield('js')

        @yield('footer')





    </body>
</html>
