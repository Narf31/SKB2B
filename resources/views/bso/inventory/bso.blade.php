@extends('layouts.app')



@section('content')


    <div class="page-heading">
        <h1 class="inline-h1">Инвентаризация БСО</h1>
        <a class="btn btn-success btn-right" href="/bso/inventory_bso/export">Выгрузка в .xls</a>

    </div>

    <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="filter-group">


                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        {{ Form::select('bso_supplier_id', App\Models\Organizations\Organization::getOrgProvider()->get()->pluck('title', 'id')->prepend('Организация', -1), request('bso_supplier_id', -1), ['class' => 'form-control select2-ws', 'id'=>'bso_supplier_id', 'onchange'=>'loadItems()']) }}
                    </div>
                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        {{ Form::select('point_sale_id', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id')->prepend('Точка продаж', -1), request('point_sale_id', -1), ['class' => 'form-control select2-ws', 'id'=>'point_sale_id', 'onchange'=>'loadItems()']) }}
                    </div>
                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        {{ Form::select('type_bso_id', \App\Models\BSO\BsoType::getDistinctType()->get()->pluck('title', 'product_id')->prepend('Вид', -1), request('type_bso_id', -1), ['class' => 'form-control select2-ws', 'id'=>'type_bso_id', 'onchange'=>'loadItems()']) }}
                    </div>

                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                </div>
            </div>
        </div>
    </div>


    <div class="block-inner sorting col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow: auto;">
        <table class="tov-table">
            <tbody>
                <tr>
                    <th rowspan="2">Вид</th>
                    <th rowspan="2">Итого<br>Принято из СК</th>
                    <th rowspan="2">На складе</th>
                    <th rowspan="2">Резерв</th>
                    <th rowspan="2">Передано агентам</th>
                    <th colspan="3">Реализовано</th>
                    <th colspan="4">Передано в СК</th>
                </tr>
                <tr>
                    <th>Проданы</th>
                    <th>Испорчены</th>
                    <th>Иные</th>
                    <th>Чистые</th>
                    <th>Проданы</th>
                    <th>Испорчены</th>
                    <th>Иные</th>
                </tr>

                @if(sizeof($bso_items))
                    @php $sk_id_temp = ''; @endphp
                    @foreach($bso_items as $bso)
                        @if($sk_id_temp != $bso->sk_title)
                            <tr><td colspan="12" class="sk_header"><h2 style="font: bold 17px arial !important;">{{$bso->sk_title}}</h2></td></tr>
                        @endif
                        @php
                            $inventory = \App\Models\BSO\BsoItem::countBsoInventory($bso->bso_supplier_id, $bso->type_bso_id);
                        @endphp

                        <tr>
                            <td>{{$bso->type_title}}</td>
                            <td class="center gray"><a href="javasript:void(0);" data-type="all" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_all}}</a></td>
                            <td style="background-color: @php if($inventory->qty_stock < $bso->min_red) echo '#ffcccc'; elseif($inventory->qty_stock < $bso->min_yellow) echo '#ffffbe'; @endphp" class="center"><a href="javasript:void(0);" data-type="stock" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_stock}}</a></td>
                            <td class="center gray"><a href="javasript:void(0);" data-type="reserv" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_reserv}}</a></td>
                            <td class="center"><a href="javasript:void(0);" data-type="agents" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_agents}}</a></td>
                            <td class="center"><a href="javasript:void(0);" data-type="sold" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_sold}}</a></td>
                            <td class="center"><a href="javasript:void(0);" data-type="spoiled" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_spoiled}}</a></td>
                            <td class="center"><a href="javasript:void(0);" data-type="other" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_other}}</a></td>
                            <td class="center gray"><a href="javasript:void(0);" data-type="sk_blank" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_sk_blank}}</a></td>
                            <td class="center gray"><a href="javasript:void(0);" data-type="sk_sold" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_sk_sold}}</a></td>
                            <td class="center gray"><a href="javasript:void(0);" data-type="sk_spoile" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_sk_spoiled}}</a></td>
                            <td class="center gray"><a href="javasript:void(0);" data-type="sk_other" data-type_bso="{{$bso->type_bso_id}}" target="_blank">{{$inventory->qty_sk_other}}</a></td>
                        </tr>

                        @php $sk_id_temp = $bso->sk_title; @endphp

                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@stop

@section('js')

    <script type="text/javascript">
        $(function() {

            $(document).on('click', 'a[data-type_bso]', function(){
                if($(this).html() != '0'){
                    $(this).attr('href', detailsBso($(this).data('type'), $(this).data('type_bso')))
                }else{
                    return false;
                }
            });
        });

        function loadItems(){
            window.location = "/bso/inventory_bso/?"+getParam();
        }

        function detailsBso(types, type_bso_id){


            return "/bso/inventory_bso/details/?"+getParam2()+"&type_bso_id="+type_bso_id+"&types="+types;

        }


        function getParam(){
            return "insurance_companies_id=-1&bso_supplier_id="+$("#bso_supplier_id").val()+"&point_sale_id="+$("#point_sale_id").val()+"&type_bso_id="+$("#type_bso_id").val();
        }

        function getParam2(){
            return "insurance_companies_id=-1&bso_supplier_id="+$("#bso_supplier_id").val()+"&point_sale_id="+$("#point_sale_id").val();
        }

    </script>

    <style>

        .center > span {
            color: blue;
            cursor: pointer;
        }

    </style>

@stop

