@extends('client.layouts.app')

@section('head')

@append

@section('content')



    <div class="row row__custom justify-content-between">



        <div class="row col-xs-12 col-sm-12 col-md-4 col-xl-4 col-lg-4 col__custom">
            <div class="content__box-right" style="width: 100%;padding-bottom: 20px;">
                <div class="calc__menu">
                    <ul>

                        <li>
                            <a class="info-menu" style="cursor: pointer;" onclick="viewInfo('client.profile.info.contracts')">Договоры</a>
                        </li>

                        <li>
                            <a class="info-menu" style="cursor: pointer;" onclick="viewInfo('client.profile.info.damages')">Убытки</a>
                        </li>

                        <li>
                            <a class="info-menu" style="cursor: pointer;" onclick="viewInfo('client.profile.info.data')">Мой данные</a>
                        </li>

                        <li>
                            <a class="info-menu" style="cursor: pointer;" onclick="viewInfo('client.profile.info.documents')">Документы</a>
                        </li>

                        <li>
                            <a class="info-menu" style="cursor: pointer;" onclick="viewInfo('client.profile.info.password')">Сменить пароль</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-8 col-xl-8 col-lg-8 col__custom" >

            <div class="product_form row" style="padding-left: 15px;min-height: 500px;" id="info-data">

            </div>

        </div>

    </div>









@endsection

@section('js')

    <script>

        document.addEventListener("DOMContentLoaded", function (event) {

            $('.info-menu').first(':first').click();
        });

        function viewInfo(content) {

            $("#info-data").html('');

            loaderShow();

            $.get('{{urlClient("/profile/info")}}', {view_content:content}, function (response) {

                $("#info-data").html(response);

                activeInputForms();

            }).always(function () {
                loaderHide();
            });


        }


    </script>

@endsection