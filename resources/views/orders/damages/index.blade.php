@extends('layouts.app')


@section('content')




    @if(sizeof($subpermissions))
    <div class="header_bab" >


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/orders/damages/create")}}')">Создать</span>

            <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

                    @foreach($subpermissions as $key => $subpermission)
                        <div title="{{ trans("users/roles.subpermission_titles.{$subpermission->title}") }}" data-status="{{$subpermission->status_id}}"></div>
                    @endforeach

            </div>

        </div>
    </div>

    <div class="block-main" style="margin-top: -5px;">
        <div class="block-sub">
            <div class="form-horizontal">
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 filters">
                    <div class="row ">

                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <label class="control-label">Город</label>
                            @include('orders.default.partials.cityes_select', ['city_id' => 0])
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                            <label class="control-label">Тип убытка</label>
                            {{Form::select('position_type_id', collect(\App\Models\Orders\Damages::POSITION_TYPE)->prepend('Все', -1), -1, ['class' => 'form-control select2-ws', 'id'=>'position_type_id'])}}
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
                            <label class="control-label">Номер убытка</label>
                            {{ Form::text('damage_id', '', ['class' => 'form-control']) }}
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <label class="control-label">Номер договора</label>
                            {{ Form::text('contract_bso_title', '', ['class' => 'form-control']) }}
                        </div>




                    </div>

                    <div class="row ">

                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <div class="row">
                                <div class="col-sm-6 col-lg-6">
                                    <label class="control-label">Период с</label>
                                    {{ Form::text('date_from', '', ['class' => 'form-control datepicker date ', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="col-sm-6 col-lg-6">
                                    <label class="control-label">По</label>
                                    {{ Form::text('date_to', '', ['class' => 'form-control datepicker date ', 'autocomplete' => 'off']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                            <label class="control-label">Страхователь</label>
                            {{ Form::text('contract_insurer', '', ['class' => 'form-control']) }}
                        </div>


                    </div>

                    <div class="row ">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span class="btn btn-primary pull-left" onclick="selectTab(TAB_INDEX)">Применить</span>

                            <div class="filter-group pull-right">
                                <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    {{Form::select('pageCount', collect([25=>'25', 50=>'50', 100=>'100', 150=>'150']), 25, ['class' => 'form-control select2-ws', 'id'=>'pageCount', 'onchange'=>'setPageCount()'])}}
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
                                    <span id="view_row">0</span>/<span id="max_row">0</span>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>


    @endif


    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" ></div>





@stop

@section('js')

    <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    <script>

        var PAGE = 1;
        var pageCount = 25;
        var TAB_INDEX = 0;

        $(function () {

            if($('[data-status]').length > 0){
                $('#tt').tabs({
                    border:false,
                    pill: false,
                    plain: true,
                    onSelect: function(title, index){
                        return selectTab(index);
                    }
                });
                selectTab(0);
            }
        });


        function selectTab(id) {

            TAB_INDEX = id;

            var tab = $('#tt').tabs('getSelected');
            var status = tab.data('status');
            loaderShow();

            $.post("/orders/damages/list", {status:status, data:getData()}, function (response) {
                loaderHide();
                $("#main_container").html(response.html);
                $('#view_row').html(response.view_row);
                $('#max_row').html(response.max_row);

                $('#page_list').pagination({
                    total: response.page_max,
                    pageSize: 1,
                    pageNumber: PAGE,
                    layout: ['first', 'prev', 'links', 'next', 'last'],
                    onSelectPage: function (pageNumber, pageSize) {
                        setPage(pageNumber);
                    }
                });

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


        function getData() {
            data = {
                PAGE:PAGE,
                pageCount:pageCount,
                city_id:$("#city_id").val(),
                damage_id: $('[name="damage_id"]').val(),
                contract_bso_title: $('[name="contract_bso_title"]').val(),
                contract_insurer: $('[name="contract_insurer"]').val(),
                date_from: $('[name="date_from"]').val(),
                date_to: $('[name="date_to"]').val(),
                position_type_id: $('[name="position_type_id"]').val(),

            };

            return data;
        }


        function get_executors() {
            selectTab(TAB_INDEX);

            var status = tab.data('status');
            if(status == 1){
                openFlagToMap();
            }


        }

        function setPageCount(field) {
            pageCount = $('select[name="pageCount"]').val();
            selectTab(TAB_INDEX);
        }
        function setPage(field) {
            PAGE = field;
            selectTab(TAB_INDEX);
        }




    </script>


@stop