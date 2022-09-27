@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">Уведомления</h1>
    </div>

    <div class="header_bab">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">
                <div title="Актуально" id="tab-0" data-is_read="0"></div>
                <div title="Прочитано"  id="tab-1" data-is_read="1"></div>

            </div>
        </div>
    </div>

    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;overflow: auto;">


    </div>



@endsection



@section('js')

    <script>

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
            console.log(getData());
           // loaderShow();
            $.get('/users/notification/get_table', getData(), function (response) {
                loaderHide();

                $("#main_container").html(response);
                startMainFunctions();

            }).always(function() {
                loaderHide();
            });

        }

        function getData(){
            return {
                is_read: $('#tt').tabs('getSelected').data('is_read')
            }
        }



    </script>
@endsection
