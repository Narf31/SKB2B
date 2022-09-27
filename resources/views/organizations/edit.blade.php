@extends('layouts.app')

@section('head')
<link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">
@append


@section('content')

    <div class="header_bab" >


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

                <div title="Основная информация" data-view="organizations.organizations.form"></div>
                @if($organization->id > 0)

                    <div title="Пользователи - сотрудники" data-view="organizations.users"></div>
                    <div title="Банковские реквизиты" data-view="organizations.bank_account"></div>
                    <div title="Документы" data-view="organizations.scan_doc"></div>

                    @if($organization->org_type->is_provider == 1)

                        <div title="Платежный агент" data-view="organizations.organizations.partials.payment_agent"></div>

                    @endif

                @endif
            </div>
        </div>
    </div>
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;">
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
            loadTab(load, '');

        }

        function loadTab(load, data) {
            loaderShow();

            $.get("/directories/organizations/{{(int)$organization->id}}/get_html_block", {view:load, data:data}, function (response) {
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
            // initMaps();
            //
            // getUsersToOrg();
        }









    </script>
@append
