@extends('layouts.app')


@section('content')

    <div class="product_form">


        <div class="header_bab" >
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

                    <div title="Основная информация" data-view="orders.pso.partials.info"></div>
                    <div title="Документы/Фото/Видео" data-view="orders.default.setfile"></div>
                    <div title="История" data-view="orders.default.history"></div>

                </div>
            </div>
        </div>

        <div class="block-main" style="margin-top: -5px;">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" >
                    </div>

                </div>
            </div>
        </div>


    </div>



@stop




@section('js')



    <script>

        var TAB_INDEX = 0;

        $(function () {


            $('#tt').tabs({
                border:false,
                pill: false,
                plain: true,
                onSelect: function(title, index){
                    return selectTab(index);
                }
            });

            selectTab(0);




        });

        function selectTab(id) {
            var tab = $('#tt').tabs('getSelected');
            load = tab.data('view');//$("#tab-"+id).data('view');
            TAB_INDEX = id;
            loaderShow();

            $.get("/orders/pso/{{$order->id}}/get-html-block", {view:load}, function (response) {
                loaderHide();
                $("#main_container").html(response);
                initTab();

            })  .done(function() {
                loaderShow();
            })
                .fail(function() {
                    loaderHide();
                })
                .always(function() {
                    loaderHide();
                });

        }

        function initTab() {
            startMainFunctions();
        }




    </script>


@stop