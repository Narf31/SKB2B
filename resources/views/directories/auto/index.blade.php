@extends('layouts.app')


@section('content')

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
            padding: 0 12px;
            width: 80%;
            border-left: none;
        }
    </style>

    <div class="tab">
        <button class="tablinks active" data-type-directories="categories">Категории</button>
        <button class="tablinks" data-type-directories="marks">Марки</button>
        <button class="tablinks" data-type-directories="models">Модели</button>
        <button class="tablinks" data-type-directories="colors">Цвета</button>
        <button class="tablinks" data-type-directories="anti-theft-system">Противоугонные системы</button>
    </div>

    <div id="main_container" class="tabcontent">

    </div>

@endsection

@section('js')

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            let activeTypeDirectories = document.body.querySelector('button.active').dataset.typeDirectories;

            getDirectories(activeTypeDirectories);

            document.body.querySelectorAll('.tablinks').forEach((tab) => {
               tab.addEventListener('click', (event) => {
                   let tab = event.target;

                   document.body.querySelector('button.active').classList.remove('active');
                   tab.classList.add('active');

                   let activeTypeDirectories = tab.dataset.typeDirectories;

                   getDirectories(activeTypeDirectories);
               });
            });

        });

        function getDirectories(activeTypeDirectories) {
            loaderShow();
            $.get("/directories/auto/" + activeTypeDirectories, {}, function (response) {
                loaderHide();
                $("#main_container").html(response);
            }).always(function() {
                loaderHide();
            });
        }

    </script>

@endsection