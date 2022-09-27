@extends('layouts.app')

@section('head')
    <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">
@append

@php
    $type = ($general->type_id == 0)?'fl':'ul';
@endphp

@section('content')

    <div class="header_bab" >


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

                <div title="Основная информация" data-view="general.subjects.form.index"></div>
                @if($general->id > 0)
                    <div title="ПОД/ФТ" data-view="general.subjects.form.podft"></div>
                    <div title="Специальные отметки" data-view="general.subjects.form.special_marks"></div>
                    <div title="Документы" data-view="general.subjects.form.documents"></div>
                    <div title="Взаимодействия и связи" data-view="general.subjects.form.interactions_connections"></div>
                    <div title="Договоры" data-view="general.subjects.form.contracts"></div>
                    <div title="Убытки" data-view="general.subjects.form.damages"></div>
                    <div title="История изменений" data-view="general.subjects.form.log"></div>
                @endif
            </div>
        </div>
    </div>
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;">
    </div>




@stop




@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

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

            $.get("/general/subjects/edit/{{$general->id}}/get_html_block", {view:load, data:data}, function (response) {
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
@append