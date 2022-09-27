@extends('layouts.app')

@section('content')



    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.templates') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/templates/create')  }}')">
            {{ trans('form.buttons.add') }}
        </span>
    </div>
    <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="filter-group" id="filters">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" for="user_id_from">Категория шаблона</label>
                        @include('settings.templates.partial.category_select', ['all' => true])
                    </div>
                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <label class="control-label" for="user_id_from">Для поставщика</label>
                        {{ Form::select('supplier_id', \App\Models\Directories\BsoSuppliers::all()->pluck('title', 'id')->prepend('Не выбрано', -1), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="table"></div>


@endsection

@section('js')
    <script>

        $(function(){
            loadItems();
        });


        function loadItems(){
            loaderShow();
            $.post('/settings/templates/get_table', getData(), function(res){
                $('#table').html(res);
            }).always(function(){
                loaderHide();
            });
        }

        function getData(){
            return {
                category_id: $('[name="category_id"]').val(),
                supplier_id: $('[name="supplier_id"]').val()
            }
        }

        function deleteItem(id) {
            if (!customConfirm()) return false;
            $.post('{{ url('/settings/templates') }}/' + id, {
                _method: 'delete'
            }, function () {
                window.location.reload();
            });
        }
    </script>

@stop



