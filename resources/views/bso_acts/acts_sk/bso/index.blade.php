@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h2 class="inline-h1">Акты в СК по БСО</h2>
        <a href="/bso_acts/acts_sk/" class="btn btn-primary btn-right">Назад</a>
        <a href="/bso_acts/acts_sk/{{$supplier->id}}/acts" class="btn btn-success btn-right">Все Акты ({{$supplier->reports_acts()->count()}})</a>

    </div>

    <div class="header_bab">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="tt" class="easyui-tabs">
                <div title="Реестр корзина" data-acts_sk_id="0"></div>
                <div title="Реестр текущий" data-acts_sk_id="-1"></div>
                <div title="Реестр будущий" data-acts_sk_id="-2"></div>
            </div>
        </div>
    </div>

    <div class="block-inner sorting row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" style="margin-top: -5px;overflow: auto;">
        <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="filter-group" id="filters">
                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="product_id">Тип</label>
                            {{ Form::select('product_id', \App\Models\Directories\Products::all()->pluck('title', 'id'), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>
                        <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label class="control-label" for="state_id">Статус</label>
                            {{ Form::select('state_id', \App\Models\BSO\BsoState::query()->where('id', '!=', 2)->pluck('title', 'id'), -1, ['class'=>'form-control select2-all','onchange'=>'loadItems()', 'multiple' => true]) }}
                        </div>
                        {{ Form::hidden('acts_sk_id', '0') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
            <div class="row">
                <div id="table"></div>
            </div>
        </div>
        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="row">
                <div id="action_table"></div>
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
            loadItems();



            $(document).on('change', '[name="bso_item[]"]', function(){
                var uncheckeds = $('[name="bso_item[]"]').length - $('[name="bso_item[]"]:checked').length;
                $('[name="all_bso_items"]').prop('checked', uncheckeds === 0);
                showActions();
            });


            $(document).on('change', '[name="all_bso_items"]', function(){
                var checked = $(this).prop('checked');
                $('[name="bso_item[]"]').prop('checked', checked);
                showActions();
            });


            $(document).on('click', '#execute_bso', function(){
                var event_data = getEventData();
                $.post('/bso_acts/acts_sk/{{$supplier->id}}/bso/execute_bso', event_data, function(res){
                    if(res.status === 'ok'){
                        flashMessage('success', 'Операция выполнена успешно');
                        loadItems();
                    }
                })
            });


            $(window).on('scroll', function(){
                var st = $('html').scrollTop();
                var elem = $('#action_table');
                if(st > 210){
                    elem.css({
                        'width' : '47%',
                        'margin-top' : '-206px',
                        'position' : 'fixed',
                    })
                }else{
                    elem.css({
                        'width' : '',
                        'margin-top' : '0',
                        'position' : '',
                    })
                }
            })


        });


        function selectTab(index){
            var acts_sk_id = $($('[data-acts_sk_id]')[index]).data('acts_sk_id');
            $('[name="acts_sk_id"]').val(acts_sk_id);
            loadItems();
        }




        function getData(){
            return {
                state_id:$('[name="state_id"]').val(),
                acts_sk_id:$('[name="acts_sk_id"]').val(),
                product_id:$('[name="product_id"]').val(),
            }
        }


        function getEventData(){
            var event_data = {
                bso_ids: [],
                report_name: $('[name="report_name"]').val(),
                report_year: $('[name="report_year"]').val(),
                report_month: $('[name="report_month"]').val(),
                report_date_start: $('[name="report_date_start"]').val(),
                report_date_end: $('[name="report_date_end"]').val(),
                to_act_sk_id: $('[name="to_act_sk_id"]').val(),
                event_id: $('[name="event_id"]').val(),
            };
            $.each($('[name="bso_item[]"]:checked'), function(k,v){
                event_data.bso_ids.push($(v).val());
            });
            return event_data;
        }



        function loadItems(){
            loaderShow();

            $.post('/bso_acts/acts_sk/{{$supplier->id}}/bso/get_table', getData(), function(table_res){
                $('#table').html(table_res.html);
                showActions();
            }).always(function(){
                loaderHide();
            });

        }


        function showActions(){
            if($('[name="bso_item[]"]:checked').length > 0){
                $.post('/bso_acts/acts_sk/{{$supplier->id}}/bso/get_action_table', getData(), function(actions_res) {
                    $('#action_table').html(actions_res)
                });
            }else{
                $('#action_table').html('')
            }

        }

    </script>


@stop