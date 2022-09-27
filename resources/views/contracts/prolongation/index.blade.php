@extends('layouts.app')

@section('content')


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 filters">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row ">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <label class="control-label">Период</label>

                            {{ Form::select('payment_date_type_id', collect([1 => 'Месяц окончания договора', 2 => 'Месяц начала договора']), 1, ['class' => 'form-control select2-ws']) }}
                        </div>
                        <div class="col-sm-12 col-lg-12">
                            <label class="control-label">Месяц</label>
                            {{ Form::select('date_mond_id', collect(getRuMonthes()), date("m"), ['class' => 'form-control select2-ws']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-9">

                    <div class="col-sm-3 col-lg-3">
                        <label class="control-label">Продукт</label>
                        {{ Form::select('product_id', \App\Models\Directories\Products::all()->pluck('title', 'id')->prepend('Все', -1), -1, ['class' => 'form-control select2-ws']) }}
                    </div>

                    <div class="col-sm-3 col-lg-3">
                        <label class="control-label">Статус договора</label>
                        {{ Form::select('contract_status_id', collect(\App\Models\Contracts\Contracts::STATYS)->prepend('Все', 0), 4, ['class' => 'form-control select2-all']) }}
                    </div>

                    <div class="col-sm-3 col-lg-3">
                        <label class="control-label">Номер договора</label>
                        {{ Form::text('contract_bso_title', '', ['class' => 'form-control']) }}
                    </div>

                    <div class="col-sm-3 col-lg-3">
                        <label class="control-label">Страхователь</label>
                        {{ Form::text('contract_insurer', '', ['class' => 'form-control']) }}
                    </div>

                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">Агент</label>
                        {{ Form::select('agent_id', $users->pluck('name', 'id')->prepend('Все', -1), (count($users)>=2)?-1:auth()->id(), ['class' => 'form-control select2', 'id'=>'agent_id']) }}
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

                payment_date_type_id: $('[name="payment_date_type_id"]').val(),
                date_mond_id: $('[name="date_mond_id"]').val(),

                product_id: $('[name="product_id"]').val(),
                contract_status_id: $('[name="contract_status_id"]').val(),

                contract_insurer: $('[name="contract_insurer"]').val(),
                contract_bso_title: $('[name="contract_bso_title"]').val(),

                agent_id: $('[name="agent_id"]').val(),

            }
        }

        $(function () {


            loadItems();


        });

        function loadItems() {
            activePagination(0, 0, 1);

            $('#table_info').html('');


            loaderShow();
            $.post("{{url("/contracts/prolongation/table")}}", getData(), function (response) {


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

        function getXlsx() {
            loaderShow();
            $.post("{{url("/contracts/search/get_payments_table_to_excel")}}", getData(), function (response) {

                if(parseInt(response.state) == 1){
                    window.location = response.url;
                }

            }).done(function() {
                //loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });
        }


    </script>
@stop
