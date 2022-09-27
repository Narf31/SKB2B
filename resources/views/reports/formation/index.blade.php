@extends('layouts.app')

@section('content')


    <div class="header_bab">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="tt" class="easyui-tabs">
                <div title="Реестр корзина" data-report_id="0"></div>
                <div title="Реестр текущий" data-report_id="-1"></div>
                <div title="Реестр будущий" data-report_id="-2"></div>
            </div>
        </div>
    </div>

    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;overflow: auto;">
        <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="filter-group" id="filters">


                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="location_id">Филиал</label>
                            {{ Form::select('bso_suppliers', \App\Models\Directories\BsoSuppliers::getFilials()->pluck('title', 'id'), [0], ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="product_id">
                                Продукт
                                <sup><a href="#" class="btn-xs btn-success" id="select_all_products">выбрать все</a></sup>
                            </label>
                            {{ Form::select('product_id', \App\Models\Directories\Products::all()->pluck('title', 'id'), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="location_id">Событие</label>
                            {{ Form::select('location_id', \App\Models\BSO\BsoLocations::whereIn('id', [1,4])->get()->pluck('title', 'id'), [4], ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="location_id">Тип</label>
                            {{ Form::select('type_id', collect(\App\Models\Contracts\Payments::TRANSACTION_TYPE), [0], ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>


                        <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="location_id">Тип платежа</label>
                            {{ Form::select('payment_type', collect(\App\Models\Contracts\Payments::PAYMENT_TYPE), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="location_id">Поток оплаты</label>
                            {{ Form::select('payment_flow', collect(\App\Models\Contracts\Payments::PAYMENT_FLOW), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>

                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="location_id">Метод оплаты</label>
                            {{ Form::select('payment_methods', \App\Models\Settings\PaymentMethods::all()->pluck('title', 'id'), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>



                        {{ Form::hidden('report_id', '0') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div id="action_table"></div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div id="table"></div>
            </div>
        </div>
    </div>





@stop

@section('js')

    <script>
        $(function () {

            $('#tt').tabs({
                border:false, pill: false, plain: true,
                onSelect: function(title, index){
                    return selectTab(index);
                }
            });



            $(document).on('change', '[name="payment[]"]', function(){
                var uncheckeds = $('[name="payment[]"]').length - $('[name="payment[]"]:checked').length;
                $('[name="all_payments"]').prop('checked', uncheckeds === 0);
                showActions();
            });


            $(document).on('click', '#select_all_products', function(){
                $.each($('[name="product_id"] option'), function(k,v){
                    $(v).prop('selected', true);
                });
                $('[name="product_id"]').change();
            });


            $(document).on('change', '[name="all_payments"]', function(){
                var checked = $(this).prop('checked');
                $('[name="payment[]"]').prop('checked', checked);
                showActions();
            });


            $(document).on('click', '#execute', function(){
                var event_data = getEventData();
                $.post('/reports/reports_sk/{{$organization->id}}/{{$report_prefix}}/execute', event_data, function(res){
                    if(parseInt(res.status) === 1){
                        flashMessage('success', 'Операция выполнена успешно');
                        if(parseInt(res.report_id) > 0){
                            window.location = "/reports/order/"+res.report_id+"/";
                        }else{
                            loadItems();
                        }
                    }
                })
            });

            loadItems();

        });


        function selectTab(index){
            var report_id = $($('[data-report_id]')[index]).data('report_id');
            $('[name="report_id"]').val(report_id);
            loadItems();


        }




        function getData(){
            return {
                report_id:$('[name="report_id"]').val(),
                product_id:$('[name="product_id"]').val(),
                location_id:$('[name="location_id"]').val(),
                type_id:$('[name="type_id"]').val(),
                bso_suppliers:$('[name="bso_suppliers"]').val(),
                payment_methods:$('[name="payment_methods"]').val(),
                payment_type:$('[name="payment_type"]').val(),
                payment_flow:$('[name="payment_flow"]').val(),
            }
        }


        function getEventData(){
            var event_data = {
                payment_ids: [],
                report_name: $('[name="report_name"]').val(),
                and_dvou_report: 0,
                report_year: $('[name="report_year"]').val(),
                report_month: $('[name="report_month"]').val(),
                report_date_start: $('[name="report_date_start"]').val(),
                report_date_end: $('[name="report_date_end"]').val(),
                to_report_id: $('[name="to_report_id"]').val(),
                event_id: $('[name="event_id"]').val(),
            };
            $.each($('[name="payment[]"]:checked'), function(k,v){
                event_data.payment_ids.push($(v).val());
            });
            if($('[name="and_dvou_report"]').prop('checked')){
                event_data.and_dvou_report = 1;
            }
            return event_data;
        }


        function loadItems(){
            loaderShow();

            $.post('/reports/reports_sk/{{$organization->id}}/{{$report_prefix}}/get_table', getData(), function(table_res){
                $('#table').html(table_res);
                showActions();
            }).always(function(){
                loaderHide();
            });

        }


        function showActions(){
            if($('[name="payment[]"]:checked').length > 0){

                $.post('/reports/reports_sk/{{$organization->id}}/{{$report_prefix}}/get_action_table', getData(), function(actions_res) {
                    $('#action_table').html(actions_res);

                    setTimeout(function() {
                        $('#event_id').change();
                    }, 100);



                });



            }else{
                $('#action_table').html('')
            }

        }

    </script>


@stop