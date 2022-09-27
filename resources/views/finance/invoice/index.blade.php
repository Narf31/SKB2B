@extends('layouts.app')

@section('head')
    <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

    <style>
        tr.green{background-color: #ebfaeb;}
    </style>

@append

@section('content')


    <div class="page-heading">
        <h2>Счета</h2>
    </div>
    <div class="divider"></div>
    <div class="header_bab">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'" data-tab_active="{{session('payment_tab') ? 1 : 0}}">
                <div title="Счета"  id="tab-1" data-url="/finance/invoice/invoices"></div>
                <div title="Платежи" id="tab-0" data-url="/finance/invoice/payments"></div>
                <div title="Резервирование" id="tab-0" data-url="/finance/invoice/reservation"></div>
            </div>
        </div>
    </div>

    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;overflow: auto;"></div>

@stop

@section('js')

    <script>
     var PAGE = 1;

    function setPage(field) {
            PAGE = field;
            loadItems();
    }


        $(function () {
            var active_tab = $('#tt').data('tab_active');

            $('#tt').tabs({
                border:false,
                pill: false,
                plain: true,
                selected: active_tab,
                onSelect: function(title, index){
                    return reloadTab();
                }
            });
            reloadTab();
        });





        function reloadTab() {
            var tab = $('#tt').tabs('getSelected');
            var url = tab.data('url');

            loaderShow();
            $.get(url, {}, function (response) {
                loaderHide();

                $("#main_container").html(response);
                startMainFunctions();

            }).always(function() {
                loaderHide();
            });

        }


    </script>


@stop