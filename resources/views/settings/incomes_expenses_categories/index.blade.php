@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.incomes_expenses_categories') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/incomes_expenses_categories/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-sm-3">
                        <label class="control-label">Тип</label>
                        {{ Form::select('type', collect(\App\Models\Settings\IncomeExpenseCategory::TYPE)->prepend('Все', 0), 0, ['class' => 'form-control select2-all', 'onchange' => 'loadItems()']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="table"></div>

@endsection


@section('js')
    <script>

        $(function () {
            loadItems();
        });


        function getData(){
            return {
                type: $('[name="type"]').val()
            }
        }

        function loadItems() {
            loaderShow();
            var data = getData();
            $.post('/settings/incomes_expenses_cetegories_table/', data, function (res) {
                $('#table').html(res.html);
                loaderHide();


                $('.tov-table').DataTable({
                    autoWidth: true,
                    searching: false,
                    info: false,
                    paging: false,
                });
            })
        }

    </script>


@endsection