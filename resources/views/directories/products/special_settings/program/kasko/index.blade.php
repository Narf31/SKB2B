@extends('layouts.app')


@section('content')


    @if($program->slug != 'calculator')
    <div class="tab">
        <button class="tablinks" id="baserate" onclick="openForm('baserate')">Базовая ставка</button>
        <button class="tablinks" id="coefficient" onclick="openForm('coefficient')">Коэффициенты</button>
        <button class="tablinks" id="products" onclick="openForm('products')">Доп. Продукты</button>
        <button class="tablinks" id="services" onclick="openForm('services')">Доп. Услуги</button>
        <button class="tablinks" id="dopwhere" onclick="openForm('dopwhere')">Доп. Условия</button>
        <button class="tablinks" id="equipment" onclick="openForm('equipment')">Доп. Оборудование</button>
        <button class="tablinks" id="default" onclick="openForm('default')">По умолчанию</button>
        <button class="tablinks" id="documents" onclick="openForm('documents')">Документы</button>
    </div>

    <div id="main_container" class="tabcontent">


    </div>

    @endif


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


        @if($program->slug != 'calculator')
        $(function () {
            @if(request('view'))
            openForm('{{request('view')}}');
            @else
            openForm('baserate');
            @endif

        });


        function openForm(viewForm) {
            // Declare all variables
            var i, tablinks;


            $('.tablinks').removeClass('active');



            $('#'+viewForm).addClass('active');


            // Show the current tab, and add an "active" class to the link that opened the tab
            //evt.currentTarget.className += " active";


            loaderShow();

            $.get("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/kasko/get-form-html", {view:viewForm}, function (response) {
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

        @endif

    </script>



@endsection