@extends('layouts.app')

@section('content')


    <div class="page-heading">
        <h2>Акты Прием/Передача</h2>
    </div>

    @if(auth()->user()->role->rolesVisibility(5)->visibility == 0)

        <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="filter-group">
                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="type_id">Тип</label>
                            {{ Form::select('type_id', \App\Models\BSO\BsoCartType::all()->pluck('title', 'id')->prepend('Не выбрано', -1), -1, ['class' => 'form-control select2-ws', 'id'=>'type_id', 'onchange'=>'loadItems()']) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="tp_id">Точка продаж</label>
                            {{ Form::select('tp_id', \App\Models\Settings\PointsSale::all()->pluck('title', 'id')->prepend('Не выбрано', -1), -1, ['class' => 'form-control select2-all', 'id'=>'tp_id', 'onchange'=>'loadItems()']) }}
                        </div>



                        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="user_id_from">Передал</label>
                            {{ Form::select('user_id_from', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Не выбрано', -1), -1, ['class' => 'form-control select2', 'id'=>'user_id_from', 'onchange'=>'loadItems()']) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="user_id_to">Принял</label>
                            {{ Form::select('user_id_to', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Не выбрано', -1), -1, ['class' => 'form-control select2', 'id'=>'user_id_to', 'onchange'=>'loadItems()']) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="bso_manager_id">Сотрудник</label>
                            {{ Form::select('bso_manager_id', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Не выбрано', -1), -1, ['class' => 'form-control select2', 'id'=>'bso_manager_id', 'onchange'=>'loadItems()']) }}
                        </div>


                    </div>
                </div>
            </div>
        </div>

    @else

        <input type="hidden" id="type_id" value="-1"/>
        <input type="hidden" id="user_id_from" value="-1"/>
        <input type="hidden" id="user_id_to" value="-1"/>
        <input type="hidden" id="bso_manager_id" value="-1"/>
        <input type="hidden" id="tp_id" value="-1"/>

    @endif

    <div class="block-inner sorting col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow: auto;">
        <table class="tov-table-no-sort">
            <thead>
                <tr>
                    <th width="120px">Акт</th>
                    <th width="250px">Тип</th>
                    <th width="180px">Время создания</th>
                    <th>Передал</th>
                    <th>Принял</th>
                    <th width="200px">Точка продаж</th>
                </tr>
            </thead>
            <tbody id="table_row"></tbody>
        </table>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 pull-left">
                <div class="filter-group">
                    {{Form::select('page_count', collect([3=>'3(Тест)',25=>'25', 50=>'50', 100=>'100', 150=>'150']), request()->has('page')?request()->session()->get('page'):25, ['class' => 'form-control select2-all', 'id'=>'page_count', 'onchange'=>'loadItems()'])}}
                </div>
            </div>
            <center style="margin-top: 25px; margin-left: 34%; display: inline-block">
                <span id="view_row"></span>/<span id="max_row"></span>
            </center>
            <div id="page_list" class="easyui-pagination pull-right"></div>
        </div>
    </div>




@stop

@section('js')



    <script>

        var PAGE = 1;

        $(function () {
            loadItems();
        });

        function getData() {
            return {
                type_id: $('#type_id').val(),
                user_id_from: $('#user_id_from').val(),
                user_id_to: $('#user_id_to').val(),
                bso_manager_id: $('#bso_manager_id').val(),
                tp_id: $('#tp_id').val(),
                page_count: $("#page_count").val(),
                PAGE: PAGE,
            };
        }

        function loadItems() {
            loaderShow();

            var data = getData();

            $('#page_list').html('');
            $('#table_row').html('');
            $('#view_row').html(0);
            $('#max_row').html(0);


            $.post("{{url("/bso_acts/acts_transfer/get_acts_table/")}}", data, function (response) {

                $('#table_row').html(response.html);
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


                $(".clickable-row-blank").click( function(){
                    if ($(this).attr('data-href')) {
                        window.open($(this).attr('data-href'), '_blank');
                    }
                });

                $('td').css({'border-right': '1px #e0e0e0 solid'});
                $.each($('tr'), function(i, v){
                    var children = $(v).children().first();
                    if(children[0].localName === 'td'){
                        children.css({'border-left': '1px #e0e0e0 solid'});
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


@stop