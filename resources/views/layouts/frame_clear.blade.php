<!DOCTYPE html>
<html lang="en">
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
<body style="min-height:100%;">




    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


            <div class="block-view">
                <h3>@yield('title')</h3>
                <div class="row">

                    @include('layouts.messages')

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @yield('content')

                        @yield('footer')

                    </div>


                </div>
            </div>





    </div>
</div>

@include('layouts.frame_js')

@yield('js')


</body>

</html>