<!DOCTYPE html>
<html lang="RU">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="/favicon.ico">

    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/new/css/font.css">
    <link rel="stylesheet" type="text/css" href="/assets/new/css/main.css">
    <link rel="stylesheet" type="text/css" href="/assets/new/css/mainpage.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('head')

</head>

<body>

    <div id="login">
        <!-- BEGIN CONTENT -->
        <div class="wrapper" style="padding-bottom: 0px;">
            <div class="cont-in">
                <div class="login-block">
                    <div class="login-top">
                        <a href="/" class="logo"></a>
                    </div>
                    <div class="az">
                        @include('layouts.messages')

                        @yield('content')


                    </div>
                </div>
            </div>
        </div>
        <!-- END CONTENT -->
    </div>






<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="/assets/js/bootstrap.js"></script>
<script src="/assets/js/main.js"></script>
<script src="/assets/js/toastr.js"></script>
<script src="/assets/js/jquery.dataTables.js"></script>
<script src="/assets/js/dataTables.bootstrap.js"></script>

{{ Html::script('/js/custom.js') }}

@yield('js')

</body>

</html>