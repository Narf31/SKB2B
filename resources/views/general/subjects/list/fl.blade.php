@extends('layouts.app')

@section('head')


@append



@section('content')



    <div class="page-heading">
        <h1 class="inline-h1">Контрагенты - Физ. лица</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url("general/subjects/create?type={$type}")  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>



    <div class="divider"></div>

    <br/>

    <div class="filter-group row">


        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <label class="control-label">ФИО</label>
            {{ Form::text('title', '', ['class' => 'form-control', 'onchange' => 'loadContent()']) }}
        </div>


        <div class="col-xs-8 col-sm-3 col-md-2 col-lg-2">
            <label class="control-label">Дата рождения</label>
            {{ Form::text('birthdate', '', ['class' => 'form-control datepicker date', 'onchange' => 'loadContent()']) }}
        </div>


        <div class="col-xs-4 col-sm-3 col-md-7 col-lg-7">
            <div class="filter-group col-xs-12 col-sm-9 col-md-3 col-lg-2 pull-right" style="margin-top: 10px;margin-right: -15px;">
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


    <div class="col-md margin20 animated fadeInRight">
        <h2 class="inline-h1"></h2>
        <div class="row form-group">
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
            $.post("{{url("/subject/get_table/{$type}")}}", getData(), function (response) {
                $('#table').html(response.html);
                $('#view_row').html(response.view_row);
                $('#max_row').html(response.max_row);

                if(response.page_max && response.page_max<response.page_sel){
                    PAGE = 1;
                    loadContent();
                }



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
                birthdate: $('[name="birthdate"]').val(),
                title: $('[name="title"]').val(),
                page_count: $('[name="page_count"]').val(),
                PAGE: PAGE,
            }
        }

        function setPage(field) {
            PAGE = field;
            loadContent();
        }



    </script>
@append