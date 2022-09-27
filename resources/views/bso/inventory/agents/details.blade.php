@extends('layouts.app')

@section('title')

@stop



@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{$title}}</h1>
        <span class="btn btn-success pull-right" id="bso_inventory_export_xls">Выгрузка в .xls</span>
    </div>

    <div class="block-inner">
        <div class="pull-right">
            <div class="filter-group">
                {{Form::select('page_count', collect([3 => '3',  25=>'25', 50=>'50', 100=>'100', 150=>'150']), request()->has('page')?request()->session()->get('page'):25, ['class' => 'form-control select2-all', 'id'=>'page_count', 'onchange'=>'loadItems();'])}}
            </div>
        </div>
    </div>

    <div class="block-inner">
        <div id="table_full"></div>
    </div>

    <div class="block-inner">
        <div id="page_list" class="easyui-pagination pull-right" style="margin: 0 !important;"></div>
        <div style="margin-top: 12px;margin-left: 50%; display: inline-block">
            <span id="view_row"></span>/<span id="max_row"></span>
        </div>
    </div>

@stop




@section('js')



    <script type="text/javascript">

        var PAGE = 1;
        var request = JSON.parse('{!! json_encode(request()->query()) !!}');
        $(function() {

            loadItems();

            $(document).on('click', '#bso_inventory_export_xls', function () {
                var data = { page_count: -1,  PAGE: 1, };
                $.each(request, function(key, val){
                    data[key] = val;
                });
                var query = $.param({ method:  'BSO\\InventoryAgentsController@get_details_table', param: data });
                location.href = '/exports/table2excel?'+query;
            });

        });


        function loadItems() {

            var data = {
                page_count: $("#page_count").val(),
                PAGE: PAGE,
            };

            $.each(request, function(key, val){
                data[key] = val;
            });


            $('#page_list').html('');
            $('#table_row').html('');
            $('#view_row').html(0);
            $('#max_row').html(0);

            loaderShow();

            $.post("/bso/inventory_agents/get_details_table", data, function (response) {
                $('#table_full').html(response.html);
                $('#view_row').html(response.view_row);
                $('#max_row').html(response.max_row);

                $('#page_list').pagination({
                    total:response.page_max,
                    pageSize:1,
                    pageNumber: PAGE,
                    layout:['first','prev','links','next','last'],
                    onSelectPage: function(pageNumber, pageSize){
                        setPage(pageNumber);
                    }
                });
                loaderHide();


            }).always(function() {
                loaderHide();
            });


        }

        function setPage(field) {
            PAGE = field;
            loadItems();
        }







    </script>

    <style>

        .bso_table {
            font: 12px arial;
            border: 1px solid #777;
            border-collapse: collapse;
        }
        .bso_table td, th {
            border: 1px solid #777;
            padding: 5px;
            font: 12px arial;
        }

        .bso_table th {
            background-color: #EEE;
        }

        .bso_table td {
            background-color: #FFF;
        }

        input[type=button] {
            cursor: pointer;
        }

    </style>

@stop

