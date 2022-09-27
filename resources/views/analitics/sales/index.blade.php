@extends('layouts.app')

@section('footer')
    <style>
        .payments_table td, .payments_table th {
            white-space: nowrap;
        }

        .filters_table td, .filters_table th {
            white-space: nowrap;
        }



        .wrapper {
            overflow:scroll;
            overflow-x: scroll;
        }

        .content {
            overflow: unset;
        }

    </style>
@endsection



@section('content')

    <div class="page-heading">

        <div class="page-heading row">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <label class="control-label">Период</label>

                        {{ Form::select('payment_date_type_id', collect([1 => 'Даты оплаты', 2 => 'Дата начала договора', 3 => 'Дата заключения']), 3, ['class' => 'form-control select2-all']) }}
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">С</label>
                        {{ Form::text('date_from', date('d.m.Y', strtotime('-1 months')), ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <label class="control-label">По</label>
                        {{ Form::text('date_to', date('d.m.Y'), ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-9">

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Статус оплаты</label>
                    {{ Form::select('payment_status_id', collect([-1=>'Все', 0 => 'Не оплачен', 1 => 'Оплачен']), 1, ['class' => 'form-control select2-all']) }}
                </div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Транзакции</label>
                    {{ Form::select('is_deleted', collect([-1=>'Все', 0 => 'Транзакции', 1 => 'Удаленные']), 0, ['class' => 'form-control select2-all']) }}
                </div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Тип транзакции</label>
                    {{ Form::select('payment_type_id', collect(\App\Models\Contracts\Payments::TRANSACTION_TYPE)->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                </div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Тип договора</label>
                    {{ Form::select('contract_type', collect(\App\Models\Contracts\Contracts::TYPE)->prepend('Все', 0), 0, ['class' => 'form-control select2-all']) }}
                </div>

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Тип оплаты</label>
                    {{ Form::select('payment_type', collect(\App\Models\Contracts\Payments::PAYMENT_TYPE)->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                </div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Поток оплаты</label>
                    {{ Form::select('payment_flow', collect(\App\Models\Contracts\Payments::PAYMENT_FLOW)->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                </div>

                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Условие продажи</label>
                    {{ Form::select('contract_sales_condition', collect(\App\Models\Contracts\Contracts::SALES_CONDITION)->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                </div>


                <div class="col-sm-3 col-lg-3">
                    <label class="control-label">Точка продаж</label>
                    {{ Form::select('point_sale', $points_sale->pluck('title', 'id')->prepend('Все', -1), -1, ['class' => 'form-control select2-all']) }}
                </div>

            </div>
        </div>

        <div class="page-heading">
            <div class="block-main">
                <div class="block-sub">
                    <div class="form-horizontal">
                        <div class="form-group">

                            <div class="col-sm-12 col-md-3 col-lg-3">
                                <div>
                                    <label class="control-label">Филиал</label>
                                    {{ Form::select('org_ids[]', $organizations->pluck('title', 'id'), 0, ['class' => 'form-control select2-all', 'multiple' => true]) }}
                                </div>

                                <div>
                                    <label class="control-label">Продукт</label>
                                    {{ Form::select('product_id[]', $products->pluck('title', 'id'), '', ['class' => 'form-control select2-all', 'multiple' => true]) }}
                                </div>
                                <div>
                                    <label class="control-label">Подразделение</label>
                                    {{ Form::select('department_ids[]', \App\Models\Settings\Department::all()->pluck('title', 'id'), -1, ['class' => 'form-control select2-all', 'multiple' => true]) }}
                                </div>
                            </div>


                            <div class="col-sm-12 col-md-9 col-lg-9">

                                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


                                    <div class="col-sm-4 col-lg-4">
                                        <label class="control-label">Тип пользователя</label>
                                        {{ Form::select('user_type', collect([1 => 'Агент', 2 => 'Куратор', 3 => 'Продавец']), 1, ['class' => 'form-control select2-all']) }}
                                    </div>

                                    <div class="col-sm-8 col-lg-8">
                                        <label class="control-label">Пользователь</label>
                                        {{ Form::select('user_id', \App\Models\User::getALLUserWhere()->get()->pluck('name', 'id')->prepend('Нет', 0), request('user_id') ? request()->query('user_id') : 0, ['class' => 'form-control select2 select2', 'id'=>'user_id', 'required', 'onchange' => 'loadItems()']) }}
                                    </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-sub-collapser" data-title="Дополнительно"></div>
            </div>
        </div>

    </div>



    <div class="page-subheading">
        <h1 class="inline-h1">
            <span onclick="loadItems()" class="btn btn-primary btn-left">Применить фильтры</span>

        </h1>

        <span onclick="getXlsx()" class="btn btn-success btn-right">Печать</span>

        <span onclick="openFancyBoxFrame('/account/table_setting/{{ $table_key }}/edit/')" class="btn btn-info btn-right">
                Настроить колонки
        </span>

    </div>



    <div class="block-inner">
        <div class="pull-left">
            <div class="filter-group">
                {{Form::select('page_count', isset($count_pagination) ? collect($count_pagination) : collect($result['count_pagination']),
                request()->has('page')?request()->has('page'):100, ['class' => 'form-control select2-all',
                'id'=>'page_count', 'onchange'=>'loadItems()'])}}
            </div>
        </div>

        <div id="page_list" class="easyui-pagination pull-right" style="margin: 0 !important;"></div>
        <div style="margin-top: 12px;margin-left: 50%; display: inline-block">
            <span id="view_row"></span>/<span id="max_row"></span>
        </div>
    </div>


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="table_info"></div>
    </div>




@endsection

@section('js')

    <script>



        var PAGE = 1;


        function getData() {
            return {


                page_count: $('[name="page_count"]').val(),
                PAGE: PAGE,

                payment_date_type_id: $('[name="payment_date_type_id"]').val(),
                date_to: $('[name="date_to"]').val(),
                date_from: $('[name="date_from"]').val(),


                payment_status_id: $('[name="payment_status_id"]').val(),
                is_deleted: $('[name="is_deleted"]').val(),
                payment_type_id: $('[name="payment_type_id"]').val(),
                contract_type: $('[name="contract_type"]').val(),

                payment_type: $('[name="payment_type"]').val(),
                payment_flow: $('[name="payment_flow"]').val(),
                contract_sales_condition: $('[name="contract_sales_condition"]').val(),
                point_sale: $('[name="point_sale"]').val(),


                org_ids: $('[name="org_ids[]"]').val(),


                product_id: $('[name="product_id[]"]').val(),
                department_ids: $('[name="department_ids[]"]').val(),
                personal_selling: $('[name="personal_selling"]').val(),


                user_type: $('[name="user_type"]').val(),
                user_id: $('[name="user_id"]').val(),

            }
        }

        $(function () {


            $('.block-sub-collapser').click();
            activePagination(0, 0, 0);


            $(window).resize(function () {
                myResize();
            });

            myResize();


        });

        function myResize() {
            $(".wrapper").css({maxHeight: $(window).height()  + "px"});
        }



        function loadItems() {
            activePagination(0, 0, 0);
            $('#table_info').html('');


            loaderShow();
            $.post("{{url("/analitics/sales/get_payments_table")}}", getData(), function (response) {


                activePagination(response.view_row, response.max_row, response.page_max);
                $('#table_info').html(response.html);

                //loaderHide();

                //createRow(response.result);



            }).done(function() {
                //loaderShow();
            }).fail(function() {
                loaderHide();
            }).always(function() {
                loaderHide();
            });


        }

        function getXlsx() {
            loaderShow();
            $.post("{{url("/analitics/sales/get_payments_table_to_excel")}}", getData(), function (response) {

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

@endsection