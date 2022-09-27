@extends('layouts.app')


@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2>Таблица тарифов GAP</h2>

        <div class="row col-xs-12 col-sm-4 col-md-3 col-lg-3" >
            {{ Form::select("insurance_option", \App\Models\Directories\Products\Data\GAP\Gap::OPTION, 1, ['id' => 'insurance_option','class' => 'form-control select2-ws', "onchange"=>"getTableTariff()"]) }}
        </div>

        <br/>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container"></div>

        <br/>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <span class="btn btn-left btn-success" onclick="saveTableTariff()">Сохранить</span>

        </div>

    </div>


@endsection






@section('js')

    <script>


        $(function () {
            getTableTariff();

        });


        function getTableTariff() {

            loaderShow();

            $.get("/directories/products/{{$product->id}}/edit/special-settings/"+$("#insurance_option").val()+"/table-tariff", {}, function (response) {
                loaderHide();
                $("#main_container").html(response);
                startMainFunctions();


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

        function saveTableTariff()
        {

            loaderShow();

            $.post("/directories/products/{{$product->id}}/edit/special-settings/"+$("#insurance_option").val()+"/table-tariff", $('#main_container :input').serialize(), function (response) {
                loaderHide();

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


    </script>


@endsection