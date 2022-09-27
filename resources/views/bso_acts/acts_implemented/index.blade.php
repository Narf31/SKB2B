@extends('layouts.app')

@section('head')
    <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

    <style>
        tr.green{
            background-color: #ebfaeb;
        }
    </style>

@append

@section('content')


    <div class="page-heading">
        <h2>Акты от агентов</h2>
    </div>

    <div class="divider"></div>

    <div class="header_bab" >

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">
                <div title="Акты"  id="tab-0" data-view="acts"></div>
                <div title="Проданные договоры"  id="tab-1" data-view="contracts"></div>
                <div title="Чистые БСО"  id="tab-2" data-view="сlean"></div>
                <div title="Испорченные БСО"  id="tab-3" data-view="spoiled"></div>
            </div>
        </div>
    </div>

    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;overflow: auto;">
    </div>

@stop

@section('js')

    <script>

        var map;
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
            load = tab.data('view');
            TAB_INDEX = id;

            loaderShow();

            $.get("{{url("/bso_acts/acts_implemented/get_view")}}", {load:load}, function (response) {
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


        function show_checked_options() {
            show_actions();
        }

        function check_all_bso(obj) {
            $('.bso_item_checkbox').attr('checked', $(obj).is(':checked'));
            show_actions();
        }

        function show_actions() {
            if ($('.bso_item_checkbox:checked').length > 0) {
                $('.event_form').show();
            } else {
                $('.event_form').hide();
            }

            $('.event_td').addClass('hidden');
            $('.event_' + $('#event_id').val()).removeClass('hidden');
            highlightSelected();

        }

        function highlightSelected() {
            $('.bso_item_checkbox').each(function(){
                $(this).closest('tr').toggleClass('green', $(this).is(':checked'));
            });
        }

        function get_realized_acts(type_id) {

            if ($('#event_id').val() == 2) {
                $.post("{{url("/bso_acts/acts_implemented/get_realized_acts/")}}", {user_id: $('#agent_id').val(), type_id: type_id}, function (response) {
                    var orders_list = '';
                    $.each(response, function (i, item) {
                        orders_list += "<option value='" + item.id + "'>" + item.act_number + "</option>";
                    });
                    $('#order_id').html(orders_list);

                })

            } else {
                $('#order_id').html('');
            }

        }



    </script>


@stop