<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="/favicon.ico">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title Page</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @include('layouts.css')

    @yield('head')

    <style>
        .modal-wrapper{
            overflow: hidden;
        }
    </style>

</head>
<body style="min-height:100%;">

<div class="modal-wrapper">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="modal">

                @include('layouts.messages')


                <div class="modal-header">
                    <div class="modal-heading">@yield('title')</div>
                </div>

                <div class="modal-body">
                    @yield('content')
                </div>


                @yield('footer')



            </div>
        </div>
    </div>
</div>

@include('layouts.js')

@yield('js')


</body>

</html>