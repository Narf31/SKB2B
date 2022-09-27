@extends('layouts.app')

@section('head')
    <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

@append

@section('content')


    <div class="page-heading">
        <h2>Счета</h2>



    </div>

    <div class="divider"></div>

    <br/>

    <div class="filter-group row">

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
            <label class="control-label">Агент</label>
            {{ Form::select('agent_id', \App\Models\User::getALLUserWhere()->get()->pluck('name', 'id')->prepend('Все', 0), 0, ['class' => 'form-control select2', 'id'=>'agent_id', 'onchange'=>'loadContent()']) }}
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <label class="control-label">Тип</label>
            {{ Form::select('type', \App\Models\Settings\PaymentMethods::all()->pluck('title', 'id')->prepend('Все', 0),  0, ['class' => 'form-control select2-all', 'id'=>'type', 'required', 'onchange' => 'loadContent()']) }}
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <label class="control-label">Статус</label>
            @php($status_select = collect(\App\Models\Finance\Invoice::STATUSES)->prepend('Все', '-1'))
            {{ Form::select('status', $status_select, 1, ['class' => 'form-control select2-all', 'id'=>'status', 'required', 'onchange' => 'loadContent()']) }}
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <label class="control-label">Номер счета</label>
            {{ Form::text('invoice_number', '', ['class' => 'form-control', 'onchange' => 'loadContent()']) }}
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" >
            <label class="control-label">Дата от</label>
            {{ Form::text('date_from', '', ['class' => 'form-control datepicker', 'onchange' => 'loadContent()']) }}
        </div>

        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <label class="control-label">Дата по</label>
            {{ Form::text('date_to', '', ['class' => 'form-control datepicker', 'onchange' => 'loadContent()']) }}
        </div>


        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-6">
            <div class="filter-group col-xs-12 col-sm-12 col-md-6 col-lg-2 pull-right" style="margin-top: 10px;margin-right: -15px;">
                {{Form::select('page_count', collect([25=>'25', 50=>'50', 100=>'100', 150=>'150']), request()->has('page')?request()->session()->get('page'):25, ['class' => 'form-control select2-all', 'id'=>'page_count', 'onchange'=>'loadContent()'])}}
                <div class="pull-right">
                    <span id="view_row"></span>/<span id="max_row"></span>
                </div>
            </div>


        </div>



    </div>

    <br/>

    <div class="divider"></div>



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div id="table"></div>
        </div>

        <div class="row">

            <div id="page_list" class="easyui-pagination pull-right"></div>
        </div>

    </div>



@stop

@section('js')

    <script>


        var PAGE = 1;

        $(function () {
            loadContent();

        });

        function loadContent() {

            $('#page_list').html('');
            $('#table_row').html('');
            $('#view_row').html(0);
            $('#max_row').html(0);

            loaderShow();
            $.post("{{url("/cashbox/invoice/get_invoices_table")}}", getData(), function (response) {
                $('#table').html(response.html);
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

                $(".clickable-row").click( function(){
                    if ($(this).attr('data-href')) {
                        window.location = $(this).attr('data-href');
                    }
                });

                loaderHide();

            }).fail(function(){
                $('#invoices_info').html('');
            }).always(function() {
                loaderHide();
            });
        }


        function getData(){
            return {
                agent_id: $('[name="agent_id"]').val(),
                type: $('[name="type"]').val(),
                status: $('[name="status"]').val(),
                date_from: $('[name="date_from"]').val(),
                date_to: $('[name="date_to"]').val(),
                invoice_number: $('[name="invoice_number"]').val(),

                page_count: $('[name="page_count"]').val(),
                PAGE: PAGE,
            }
        }

        function setPage(field) {
            PAGE = field;
            loadContent();
        }

    </script>


@stop