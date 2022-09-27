@extends('layouts.app')


@section('content')

<div class="page-heading">
    <h2 class="inline-h1">Акты в СК по {{ $supplier->title }}</h2>
    <a href="/bso_acts/acts_sk/" class="btn btn-primary btn-right">Назад</a>
</div>



<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 5px;">
    <div class="row form-group">

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <label class="control-label">Месяц</label>
            @php($type_select = collect(getRuMonthes())->prepend('Все', 0))
            {{ Form::select('month', $type_select, 0, ['class' => 'form-control select2-all', 'onchange' => 'loadItems()']) }}
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <label class="control-label">Год</label>
            @php($type_select = collect(getYearsRange(-5, +1))->prepend('Все', 0))
            {{ Form::select('year', $type_select, 0, ['class' => 'form-control select2-all', 'onchange' => 'loadItems()']) }}
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <label class="control-label">Тип</label>
            @php($type_select = collect([0=>'БСО', 1=>'Договор'])->prepend('Все', -1))
            {{ Form::select('type', $type_select, -1, ['class' => 'form-control select2-all', 'onchange' => 'loadItems()']) }}
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
            <label class="control-label">Название</label>
            @php($type_select = collect([0=>'БСО', 1=>'Договор'])->prepend('Все', -1))
            {{ Form::text('title', '', ['class' => 'form-control select2-all', 'onkeyup' => 'loadItems()', 'autocomplete' => 'off']) }}
        </div>

        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" >
            <label class="control-label">На странице</label>
            @php($status_select = collect([2=>'2(тестирование)', 25=>25, 50 => 50, 100=>100, 0=>'Все']))
            @php($status_selected = request('page_count') ? request()->query('page_count') : 25)
            {{ Form::select('page_count', $status_select, $status_selected, ['class' => 'form-control select2-all', 'id'=>'status', 'required', 'onchange' => 'loadItems()']) }}
        </div>

    </div>
</div>
<div id="table"></div>
<div class="row">
    <center style="margin-top: 25px; margin-left: 48%; display: inline-block">
        <span id="view_row"></span>/<span id="max_row"></span>
    </center>
    <div id="page_list" class="easyui-pagination pull-right"></div>
</div>


@endsection

@section('js')
<script>

    var PAGE = 1;
    $(function () {


        loadItems();
    });

    function setPage(field) {
        PAGE = field;
        loadItems();
    }

    function loadItems(){
        loaderShow();

        $('#page_list').html('');
        $('#table_row').html('');
        $('#view_row').html(0);
        $('#max_row').html(0);

        $.post("/bso_acts/acts_sk/{{$supplier->id}}/acts_list_table", getData(), function (response) {

            $('#table').html(response.html);
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

        }).fail(function(){
            $('#table').html('');
        }).always(function() {
            loaderHide();
        });
    }

    function getData(){
        return {
            month: $('[name="month"]').val(),
            year: $('[name="year"]').val(),
            type: $('[name="type"]').val(),
            title: $('[name="title"]').val(),
            page_count: $('[name="page_count"]').val(),
            PAGE: PAGE,
        }
    }

</script>
@endsection
