<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="/favicon.ico">

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="/plugins/select2/select2.css" rel="stylesheet">

    <link href="/plugins/bootstrap/select2.css" rel="stylesheet">

    <link href="/css/custom.css" rel="stylesheet">

    <link rel="stylesheet" href="/plugins/datepicker/jquery.datepicker.css">

    <link rel="stylesheet" href="/plugins/fancybox/jquery.fancybox.css">

    <link rel="stylesheet" href="/plugins/jquery-ui/jquery-ui.css">

    <link rel="stylesheet" href="/plugins/datetimepicker/bootstrap-material-datetimepicker.css">

    <link rel="stylesheet" href="/css/style.css">


    <script src="/plugins/jquery/jquery.min.js"></script>

    <script src="/plugins/jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript" src="/js/jscolor.min.js"></script>

    @yield('head')

</head>
<body class="yui-skin-sam">

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">


        <div class="hidden-xs col-md-4 col-md-offset-7 nav-user text-right">
            <div class="divide10"></div>
            <div style="display: inline;color: #fff; cursor: pointer">
                <i class="fa fa-user"></i>&nbsp;&nbsp;
                @if(auth()->check())
                    {{ auth()->user()->name }}
                @endif
            </div>

        </div>


        <div class="hidden-xs col-md-1 text-right">
            <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn border-theme btn-lg">
                Logout
            </a>

            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</nav>


<div class="offcanvas-container dark-version">
    <nav id="offcanvas" class="animated navmenu navmenu-default navmenu-fixed-left offcanvas offcanvas-left" role="navigation">

        <div class="logo-side-nav" style="margin-top: -17px;margin-bottom: -30px;margin-left: 70px">
            <a href="/">
				<span style=" font-size: 20px">
					{{ config('app.name') }}
				</span>
            </a>
        </div>
        <br/>

        @include('partials.menu')


    </nav>
    <button type="button" class="offcanvas-toggle-left navbar-toggle" data-toggle="offcanvas" data-target="#offcanvas"></button>
</div>

<div class="divide60"></div>

<!--container-->

<div class="divide40"></div>
<div class="container-fluid">
    <div class="col-md margin20 animated fadeInRight">

        @include('layouts.messages')

        @yield('content')
    </div>
</div>

<!--container-->


@include('layouts.js')

@yield('js')

</body>
</html>
