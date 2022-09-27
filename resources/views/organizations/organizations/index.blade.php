@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.organizations') }}</h1>
        @if(auth()->user()->hasPermission('directories', 'organizations_edit'))
            <a class="btn btn-primary btn-right" href="{{ url("$control_url/organizations/create")  }}">
                {{ trans('form.buttons.create') }}
            </a>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-3" style="margin-left:10px;">
            {{ Form::text('title', '', ['class' => 'form-control form-control-with-button','placeholder' => 'Название', 'id'=>'title', 'onchange' => 'setPage(1)']) }}
        </div>

        <div class="col-xs-8 col-sm-3 col-md-2 col-lg-2">
            {{ Form::text('inn', '', ['class' => 'form-control','id'=>'inn', 'placeholder'=>"ИНН", 'onchange' => 'setPage(1)']) }}
        </div>

        <div class="col-xs-8 col-sm-3 col-md-2 col-lg-2">
            {{ Form::text('curator', '', ['class' => 'form-control','id'=>'curator', 'placeholder'=>"Куратор", 'onchange' => 'setPage(1)']) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 pull-right">
            <div class="filter-group pull-right" style="margin-top: 10px;">
                {{Form::select('page_count', collect([25=>'25', 50=>'50', 100=>'100', 150=>'150']), request()->has('page')?request()->session()->get('page'):25, ['class' => 'form-control select2-all', 'id'=>'page_count', 'onchange'=>'loadItems()'])}}
                <div class="pull-right">
                    <span id="view_row"></span>/<span id="max_row"></span>
                </div>
            </div>

        </div>

    </div>

    <div id="table">

    </div>

    <div class="row">
        <div id="page_list" class="easyui-pagination pull-right"></div>
    </div>


@endsection

@section('js')

    <script src="/js/jquery.easyui.min.js"></script>


    <script>

        PAGE = 1;

        function get_data() {

            data = {
                title: $('#title').val(),
                inn: $('#inn').val(),
                curator: $('#curator').val(),
                page_count: $('[name="page_count"]').val(),
                page: PAGE
            };

            return data;
        }

        function loadItems() {

            $.get('/directories/organizations/organizations/get_table', get_data(), function (res) {

                $('#view_row').html(res.view_row);
                $('#max_row').html(res.max_row);

                $('#table').html(res.result);

                $('#page_list').pagination({
                    total: res.page_max,
                    pageSize: 1,
                    pageNumber: PAGE,
                    layout: ['first', 'prev', 'links', 'next', 'last'],
                    onSelectPage: function (pageNumber, pageSize) {
                        setPage(pageNumber);
                    }
                });


            })
        }

        function setPage(field) {
            PAGE = field;
            loadItems();
        }

        $(function () {
            loadItems();
        })
    </script>
@endsection

