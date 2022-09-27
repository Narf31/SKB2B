
<link href="/plugins/select2/select2.css" rel="stylesheet">

<link href="/plugins/bootstrap/select2.css" rel="stylesheet">

<link rel="stylesheet" href="/css/logch.css">

<link rel="stylesheet" href="/plugins/datepicker/jquery.datepicker.css">

<link rel="stylesheet" href="/plugins/fancybox/jquery.fancybox.css">

<link rel="stylesheet" href="/plugins/jquery-ui/jquery-ui.css">

<link rel="stylesheet" href="/plugins/datetimepicker/bootstrap-material-datetimepicker.css">

<link href="/assets/css/suggestions.css" type="text/css" rel="stylesheet" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css" type="text/css" rel="stylesheet" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="/css/online.css?v=2">

<link href="/js/intro/introjs.css" type="text/css" rel="stylesheet" />

<link rel="stylesheet" href="/assets/css/hint.css">


@if( isset(auth()->user()->text_size))
    <style type="text/css" data-html="user">
        body > *, body {
            font-size: {{ auth()->user()->text_size }}px !important;
        }

    </style>
@else
    <style type="text/css" data-html="user">body {}</style>
@endif


