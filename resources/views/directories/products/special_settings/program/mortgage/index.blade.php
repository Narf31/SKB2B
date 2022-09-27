@extends('layouts.app')


@section('content')


    <div class="tab">
        <button class="tablinks" id="baserate-life" onclick="openForm('baserate-life')">Базовая Жизнь</button>
        <button class="tablinks" id="baserate-property" onclick="openForm('baserate-property')">Базовая Имущество</button>
        <button class="tablinks" id="baserate-title" onclick="openForm('baserate-title')">Базовая Титул</button>
        {{-- <button class="tablinks" id="coefficient" onclick="openForm('coefficient')">Коэффициенты</button> --}}
    </div>

    <div id="main_container" class="tabcontent">


    </div>



@endsection



@section('js')


    <style>

        /* Style the tab */
        .tab {
            float: left;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            width: 20%;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            display: block;
            background-color: inherit;
            color: black;
            padding: 22px 16px;
            width: 100%;
            border: none;
            outline: none;
            text-align: left;
            cursor: pointer;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current "tab button" class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            float: left;
            padding: 0px 12px;
            width: 80%;
            border-left: none;
        }
    </style>



    <script>


        $(function () {
            @if(request('view'))
            openForm('{{request('view')}}');
            @else
            openForm('baserate-life');
            @endif

        });


        function openForm(viewForm) {
            // Declare all variables
            var i, tablinks;

            $('.tablinks').removeClass('active');
            $('#'+viewForm).addClass('active');


            loaderShow();

            $.get("/directories/products/{{$product->id}}/edit/special-settings/mortgage/get-form-html", {view:viewForm}, function (response) {
                loaderHide();
                $("#main_container").html(response);
                startMainFunctions();
                initViewForm();

            }).done(function() {
                loaderShow();
            })
            .fail(function() {
                loaderHide();
            })
            .always(function() {
                loaderHide();
            });

        }


        function reloadTab()
        {
            closeFancyBoxFrame();
            openForm($(".active").attr('id'));
        }


    </script>



@endsection