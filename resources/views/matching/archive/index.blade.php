@extends('layouts.app')

@section('content')


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 filters">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row ">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div class="row">
                        <div class="col-sm-6 col-lg-6">
                            <label class="control-label">С</label>
                            {{ Form::text('date_from', date('d.m.Y', strtotime('-1 months')), ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="col-sm-6 col-lg-6">
                            <label class="control-label">По</label>
                            {{ Form::text('date_to', date('d.m.Y'), ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="col-sm-12 col-lg-12">
                            <label class="control-label">Продукт</label>
                            {{ Form::select('product_id', \App\Models\Directories\Products::all()->pluck('title', 'id')->prepend('Все', -1), -1, ['class' => 'form-control select2-ws']) }}
                        </div>
                    </div>

                </div>
                <div class="col-sm-12 col-md-9 col-lg-9">

                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">Тип</label>
                        {{ Form::select('type_id', collect([-1=>'Все', 0 => 'Андеррайтинг', 1 => 'Служба безопасности']), -1, ['class' => 'form-control select2-ws']) }}
                    </div>


                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">Страхователь</label>
                        {{ Form::text('contract_insurer', '', ['class' => 'form-control']) }}
                    </div>

                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">Сотрудник</label>
                        {{ Form::select('check_user_id', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Все', -1), -1, ['class' => 'form-control select2', 'id'=>'check_user_id']) }}
                    </div>

                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">Инициатор</label>
                        {{ Form::select('initiator_user_id', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Все', -1), -1, ['class' => 'form-control select2', 'id'=>'initiator_user_id']) }}
                    </div>



                </div>
            </div>
        </div>

    </div>


    <div class="row page-subheading">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <span onclick="loadItems()" class="btn btn-primary btn-left">Применить фильтры</span>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div style="margin-top: 12px;margin-left: 50%; display: inline-block">
                <span id="view_row">0</span>/<span id="max_row">0</span>
            </div>
        </div>

    </div>

    <br/><br/>
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="table_info"></div>
        <div id="page_list" class="easyui-pagination pull-right" style="margin: 0 !important;"></div>
    </div>

@stop


@section('js')
    <script>

        var PAGE = 1;
        function getData() {
            return {
                PAGE: PAGE,

                date_to: $('[name="date_to"]').val(),
                date_from: $('[name="date_from"]').val(),

                product_id: $('[name="product_id"]').val(),

                type_id: $('[name="type_id"]').val(),
                contract_insurer: $('[name="contract_insurer"]').val(),

                check_user_id: $('[name="check_user_id"]').val(),
                initiator_user_id: $('[name="initiator_user_id"]').val(),



            }
        }

        $(function () {


            loadItems();


        });

        function loadItems() {
            activePagination(0, 0, 1);

            $('#table_info').html('');


            loaderShow();
            $.post("{{url("/matching/archive/table")}}", getData(), function (response) {


                activePagination(response.view_row, response.max_row, response.page_max);
                $('#table_info').html(response.html);


            }).done(function() {
                //loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });

        }

        function activePagination(view_row, max_row, pages) {

            $('#view_row').html(view_row);
            $('#max_row').html(max_row);

            $('#page_list').pagination({
                total: pages,
                pageSize: 1,
                pageNumber: PAGE,
                layout: ['first', 'prev', 'links', 'next', 'last'],
                onSelectPage: function (pageNumber, pageSize) {
                    PAGE = pageNumber;
                    loadItems()
                },

            });
        }



    </script>
@stop
