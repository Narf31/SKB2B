@extends('layouts.app')

@section('content')



    @php($organization_payments = $organization->getDebtBrokerToSk())
    <table class="tov-table">
        <thead>
        <tr>
            <th>Куратор</th>
            <th>Бордеро</th>
            <th>ДВОУ</th>
            <th>К оплате в СК</th>
            <th>Долг перед агентом Отчеты</th>
            <th>Дебир</th>
            <th>Кредит</th>
        </tr>
        </thead>
        <tbody>
        <tr class="clickable-row">
            <td>{{$organization->curator?$organization->curator->name:''}}</td>
            <td>

                @if(auth()->user()->hasPermission('reports', 'reports_edit'))
                    <a href="{{url("/reports/reports_sk/{$organization->id}/bordereau/")}}">
                        {{titleFloatFormat($organization->getPaymentsTotalKV(0))}}
                    </a>
                @else
                    {{titleFloatFormat($organization->getPaymentsTotalKV(0))}}
                @endif
            </td>
            <td>

                @if(auth()->user()->hasPermission('reports', 'reports_edit'))
                    <a href="{{url("/reports/reports_sk/{$organization->id}/dvoy/")}}">
                        {{titleFloatFormat($organization->getPaymentsTotalKV(1))}}
                    </a>
                @else
                    {{titleFloatFormat($organization->getPaymentsTotalKV(1))}}
                @endif

            </td>
            <td>
                {{ titleFloatFormat($organization_payments['to_transfer_total']) }}
            </td>
            <td>
                {{ titleFloatFormat($organization_payments['to_return_total']) }}
            </td>
            <td>{{titleFloatFormat($organization->getPaymentsTotal(0)+$organization_payments['to_transfer_total'])}}</td>
            <td>{{titleFloatFormat($organization->getPaymentsTotal(1)+$organization_payments['to_return_total'])}}</td>

        </tr>
        </tbody>
    </table>

<div class="page-heading row" id="main_container" >
    <div class="form-horizontal block-inner page-heading row">
        <div class="row btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <label class="control-label" for="like_title">Название</label>
            {{ Form::text('like_title', '', ['class'=>'form-control','onchange'=>'loadItems()']) }}
        </div>
        <div class="btn-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <label class="control-label" for="location_id">Тип</label>
            {{ Form::select('type_id', collect(\App\Models\Reports\ReportOrders::TYPE), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
        </div>

        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <label class="control-label" for="location_id">Статус</label>
            {{ Form::select('accept_status', collect(\App\Models\Reports\ReportOrders::STATE), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
        </div>


        <div class="btn-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <label class="control-label" for="location_id">Месяц</label>
            {{ Form::select('report_month', collect(getRuMonthes()), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
        </div>

        <div class="btn-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <label class="control-label" for="year">Год</label>
            {{ Form::text('year', '', ['class'=>'form-control','onchange'=>'loadItems()']) }}
        </div>
    </div>
</div>



<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div id="table" style="overflow: auto;"></div>
    </div>

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 pull-left">
            <div class="filter-group">
                {{Form::select('page_count', collect([25=>'25', 50=>'50', 100=>'100', 150=>'150']), request()->has('page')?request()->session()->get('page'):25, ['class' => 'form-control select2-all', 'id'=>'page_count', 'onchange'=>'loadItems()'])}}
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
        loadItems()
    });


    function getData() {
        return {
            type_id: $('[name="type_id"]').val(),
            accept_status: $('[name="accept_status"]').val(),
            report_month: $('[name="report_month"]').val(),
            page_count: $("#page_count").val(),
            like_title: $('[name="like_title"]').val(),
            year: $('[name="year"]').val(),

            page: PAGE,
        }
    }

    function loadItems() {
        loaderShow();

        $.post('{{url("/reports/reports_sk/{$organization->id}/info/table")}}', getData(), function (table_res) {

            $('#table').html(table_res.html);

            if (table_res.count < 1) {
                table_res.count = 1;
            }

            let maxpage = Math.ceil(table_res.count / table_res.perpage);

            $('#view_row').html(PAGE);
            $('#max_row').html(maxpage);


            $('#page_list').pagination({
                total: maxpage,
                pageSize: 1,
                pageNumber: PAGE,
                layout: ['first', 'prev', 'links', 'next', 'last'],
                onSelectPage: function (pageNumber, pageSize) {
                    setPage(pageNumber);
                }
            });

            $(".clickable-row").click(function () {
                if ($(this).attr('data-href')) {
                    window.location = $(this).attr('data-href');
                }
            });

        }).always(function () {
            loaderHide();
        });

    }

    function setPage(field) {
        PAGE = field;
        loadItems();
    }

</script>

@stop