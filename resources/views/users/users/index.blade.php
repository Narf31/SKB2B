@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.users') }}</h1>
        <a href="/users/users/create" class="btn btn-primary btn-right">
            {{ trans('form.buttons.create') }}
        </a>
    </div>

    <div class="row">
        <div class="col-lg-3" style="margin-left:10px;">
            {{ Form::text('fio', Request()->fio, ['class' => 'form-control form-control-with-button','placeholder' => 'ФИО', 'id'=>'fio', 'onkeyup' => 'setPage(1)']) }}
        </div>
        <div class="col-lg-2">
            <input type="text" class="form-control inline-block" placeholder="Email" onkeyup="setPage(1)"
                   id="email">
        </div>

        <div class="col-lg-2">
            <input type="text" class="form-control inline-block" placeholder="Руководитель" onkeyup="setPage(1)"
                   id="parent">
        </div>

        <div class="col-lg-2">
            <input type="text" class="form-control inline-block" placeholder="Куратор" onkeyup="setPage(1)"
                   id="curator">
        </div>

        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <div class="filter-group">
                <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {{Form::select('pageCount', collect([25=>'25', 50=>'50', 100=>'100', 150=>'150']), request()->has('page')?request()->session()->get('page'):25, ['class' => 'form-control select2-all', 'id'=>'pageCount', 'onchange'=>'loadItems()'])}}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
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
                parent: $('#parent').val(),
                curator: $('#curator').val(),
                fio: $('#fio').val(),
                email: $('#email').val(),
                page_count: $('[name="pageCount"]').val(),
                page: PAGE
            };

            return data;
        }

        function loadItems() {

            $.get('/users/users/get_table', get_data(), function (res) {

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
