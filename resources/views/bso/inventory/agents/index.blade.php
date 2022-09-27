@extends('layouts.app')

@section('content')

<div class="page-heading">
    <h1 class="inline-h1">Инвентаризация по агентам</h1>
    <span class="btn btn-success btn-right" id="obj_export_xls" >Выгрузка в .xls</span>
</div>

<div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="filter-group">
                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    {{ Form::select('agent_id', \App\Models\User::getALLUser()->pluck('name', 'id')->prepend('Агент', -1), \Request::query('agent_id'), ['class' => 'form-control select2', 'id'=>'agent_id', 'onchange'=>'loadItems()']) }}
                </div>
                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    {{ Form::select('nop_id', \App\Models\User::getALLUserWhere()->where('is_parent',1)->get()->pluck('name', 'id')->prepend('Куратор', -1), \Request::query('nop_id'), ['class' => 'form-control select2', 'id'=>'nop_id', 'onchange'=>'loadItems()']) }}
                </div>
                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    {{ Form::select('point_sale_id', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id')->prepend('Точка продаж', -1), \Request::query('point_sale_id'), ['class' => 'form-control select2-ws', 'id'=>'point_sale_id', 'onchange'=>'loadItems()']) }}
                </div>
                <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    {{ Form::select('type_bso_id', \App\Models\BSO\BsoType::getDistinctType()->get()->pluck('title', 'id')->prepend('Вид', -1), \Request::query('type_bso_id'), ['class' => 'form-control select2-ws', 'id'=>'type_bso_id', 'onchange'=>'loadItems()']) }}
                </div>
                <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
            </div>
        </div>
    </div>
</div>

<div class="block-inner sorting col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow: auto;">
    <div id="agents_table"></div>
</div>

@stop

@section('js')
<script type="text/javascript">
    $(function () {
        loadItems();

        $(document).on('click', '#obj_export_xls', function(){
            var query = $.param({ method:  'BSO\\InventoryAgentsController@get_agents_table', param: getData() });
            location.href = '/exports/table2excel?'+query;
        })
    });

    function loadItems() {
        loaderShow();
        $.post("/bso/inventory_agents/get_agents_table", getData(), function (response) {
            $('#agents_table').html(response.html);
        }).always(function() {
            loaderHide();
        });
    }


    function getData(){
        return {
            agent_id:$("#agent_id").val(),
            nop_id:$("#nop_id").val(),
            point_sale_id:$("#point_sale_id").val(),
            type_bso_id:$("#type_bso_id").val(),
        }
    }
</script>
@stop

