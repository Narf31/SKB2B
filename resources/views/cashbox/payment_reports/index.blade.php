@extends('layouts.app')

@section('content')




<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;overflow: auto;">
    <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="filter-group" id="filters">

                    <div class="btn-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="control-label" for="location_id">Организация</label>
                        {{ Form::select('organizations_id', App\Models\Organizations\Organization::getALLOrg()->get()->pluck('title', 'id')->prepend('Все', 0), 0, ['class'=>'form-control select2','onchange'=>'loadItems()']) }}
                    </div>

                    <div class="btn-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label class="control-label" for="like_title">Название отчета</label>
                        {{ Form::text('like_title', '', ['class'=>'form-control','onchange'=>'loadItems()']) }}
                    </div>



                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" for="location_id">Статус</label>
                        {{ Form::select('accept_status', collect(\App\Models\Reports\ReportOrders::STATE), [2,3], ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                    </div>


                    <div class="btn-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <label class="control-label" for="location_id">Тип</label>
                        {{ Form::select('type_id', collect(\App\Models\Reports\ReportOrders::TYPE), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
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
        </div>
    </div>
</div>



<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div id="table"></div>
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
            organizations_id: $('[name="organizations_id"]').val(),
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

        $.post('{{url("/cashbox/payment_reports/table")}}', getData(), function (table_res) {

            $('#table').html(table_res.html);

            if (table_res.count < 1) {
                table_res.count = 1;
            }

            var maxpage = Math.ceil(table_res.count / table_res.perpage);

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