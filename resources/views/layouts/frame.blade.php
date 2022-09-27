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


    <link rel="stylesheet" type="text/css" href="/assets/new/css/main.css">
    <link rel="stylesheet" type="text/css" href="/assets/new/css/font.css">
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
<body style="min-height:100%;     overflow: hidden;">

<div class="modal-wrapper">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="modal">

                <div class="flash-message alert-danger"></div>

                @include('layouts.messages')

                <div class="modal-header">
                    <div class="modal-heading">@yield('title')</div>
                </div>

                <div class="modal-body">
                    <div class="block-main">
                        <div class="block-sub">

                            <center>

                                @if(isset($errors) && is_array($errors->getMessages()))

                                    @foreach($errors->getMessages() as $error)
                                        <div>
                                            <span style="color: red;"> {{ $error[0] }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </center>

                            @yield('content')

                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    @yield('footer')

                </div>

            </div>
        </div>
    </div>
</div>
<div class='loader hidden' ></div>
@include('layouts.frame_js')

@yield('js')

<script>

    var form = $('.form-horizontal');

    $(function(){
        form.find('input[required=required], select[required=required]').change(function(){
            $(this).toggleClass('has-error',  $(this).val() == '');
        });
    });

    function deleteItem(url, id) {
        if (!customConfirm()) return false;

        $.post('{{url('/')}}' + url + id, {
            _method: 'delete'
        }, function () {
            parent_reload();
        });
    }

    function submitForm() {

        var success = true;

        form.find('input[required=required], select[required=required]').each(function () {
            var valid = $(this).val() != '';
            $(this).toggleClass('has-error', !valid);
            if (!valid) {
                success = false;
            }
        });

        if (success) {
            form.submit();
        }

    }

</script>

</body>

</html>