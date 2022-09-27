@extends('layouts.app')


@php

    $monthes = getRuMonthes();
    $years = getYearsRange(-5, +1);

@endphp


@section('content')

    <div class="block-main" style="margin-top: 5px;">
        <div class="block-sub">
            <div class="row">




                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {{ Form::open(['url' => url("/reports/order/{$report->id}/"), 'method' => 'post', 'class' => 'form-horizontal']) }}
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Название (Сформирован: {{setDateTimeFormatRu($report->created_at)}} {{$report->create_user ? "пользователем {$report->create_user->name}" : ""}})</label>
                        <div class="col-sm-12">
                            {{ Form::text('title', $report->title, ['class' => 'form-control', 'required']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-6 ">Отчетный период</label>
                        <label class="col-sm-3 ">Договора с</label>
                        <label class="col-sm-3 ">Договора по</label>
                        <div class="col-sm-6">
                            {{ Form::select('report_month', $monthes, $report->report_month, ['class' => 'form-control', 'style' => 'width: 48%; display: inline']) }} /
                            {{ Form::select('report_year', $years, $report->report_year, ['class' => 'form-control', 'style' => 'width: 48%; display: inline']) }}
                        </div>

                        <div class="col-sm-3">
                            {{ Form::text('report_date_start', setDateTimeFormatRu($report->report_date_start, 1), ['class' => 'form-control datepicker date']) }}

                        </div>

                        <div class="col-sm-3">
                            {{ Form::text('report_date_end', setDateTimeFormatRu($report->report_date_end, 1), ['class' => 'form-control datepicker date']) }}

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-6 ">Подписант организации</label>
                        <label class="col-sm-6 ">Подписант поставщика</label>

                        <div class="col-sm-6">
                            {{ Form::text('signatory_org', $report->signatory_org, ['class' => 'form-control']) }}
                        </div>

                        <div class="col-sm-6">
                            {{ Form::text('signatory_sk_bso_supplier', $report->signatory_sk_bso_supplier, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-12">Комментарий</label>
                        <div class="col-sm-12">
                            {{ Form::textarea('comments', $report->comments, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    @if($report->accept_status == 0)

                        <input type="submit" class="btn btn-primary btn-left" value="Сохранить"/>

                        @if(sizeof($payments))
                            <span class="btn btn-success btn-right" onclick="setStatus(1)">На согласование</span>
                        @else
                            <span class="btn btn-danger btn-right" onclick="deleteOrder()">Удалить отчет</span>
                        @endif



                    @else

                        @if($report->accept_status == 1 && auth()->user()->hasPermission('reports', 'reports_matching'))
                            <input type="submit" class="btn btn-primary btn-left" value="Сохранить"/>

                            @if(sizeof($payments))
                                <span class="btn btn-success btn-right" onclick="setStatus(2)">Согласован</span>
                            @else
                                <span class="btn btn-danger btn-right" onclick="deleteOrder()">Удалить отчет</span>
                            @endif


                        @endif

                        @if(($report->accept_status == 2 || $report->accept_status == 3) && auth()->user()->hasPermission('reports', 'reports_payment'))
                            <span class="btn btn-success btn-right" onclick="setStatus(1)">Вернуть на согласование</span>
                        @endif

                    @endif

                    {{Form::close()}}
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th colspan="3">Комиссия</th>
                            <th colspan="2">Сумма</th>
                        </tr>

                        <tr>
                            <th>Бордеро</th>
                            <th>ДВОУ</th>
                            <th>Общая</th>
                            <th>К перечислению в СК</th>
                            <th>К возврату агенту</th>
                        </tr>

                        </thead>
                        <tbody>

                        <tr>
                            <td>{{titleFloatFormat($report->bordereau_total)}}</td>
                            <td>{{titleFloatFormat($report->dvoy_total)}}</td>
                            <td>{{titleFloatFormat($report->amount_total)}}</td>
                            <td>{{titleFloatFormat($report->to_transfer_total)}}</td>
                            <td>{{titleFloatFormat($report->to_return_total)}}</td>
                        </tr>

                        <tr>
                            <th colspan="3"><p style="text-align: right">Итог</p></th>
                            <th>{{titleFloatFormat($report->to_transfer_total - $report->report_payment_sums->where('type_id', 1)->sum('amount'))}}</th>
                            <th>{{titleFloatFormat($report->to_return_total - $report->report_payment_sums->where('type_id', 0)->sum('amount'))}}</th>
                        </tr>

                        </tbody>
                    </table>

                    <h2>Фактические данные
                        @if(auth()->user()->hasPermission('reports', 'reports_payment'))
                            <span class="btn btn-success pull-right" style="width: 30px;height: 25px;font-size: 10px;"
                                  onclick="openFancyBoxFrame('{{ url("/reports/order/{$report->id}/payment_sum/create") }}')">
                                <i class="fa fa-plus"></i>
                            </span>
                        @endif
                    </h2>

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Комментарии</th>
                            <th>Списание</th>
                            <th>Приход</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($report->report_payment_sums as $payment_sum)
                            <tr @if(auth()->user()->hasPermission('reports', 'reports_payment')) onclick="openFancyBoxFrame('{{ url("/reports/order/{$report->id}/payment_sum/{$payment_sum->id}/edit") }}')" @endif >
                                <td style="white-space: nowrap;">{{ setDateTimeFormatRu($payment_sum->created_at) }}
                                    <br/> {{ $payment_sum->user ? $payment_sum->user->name : ""}}</td>
                                <td>{{ $payment_sum->comments }}</td>
                                <td style="white-space: nowrap;">{{ $payment_sum->type_id == 0 ? titleFloatFormat($payment_sum->amount) : "" }}</td>
                                <td style="white-space: nowrap;">{{ $payment_sum->type_id == 1 ? titleFloatFormat($payment_sum->amount) : ""}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="2"><span class="pull-right">Итог</span></th>
                            <th>{{titleFloatFormat($report->report_payment_sums->where('type_id', 0)->sum('amount'))}}</th>
                            <th>{{titleFloatFormat($report->report_payment_sums->where('type_id', 1)->sum('amount'))}}</th>
                        </tr>
                        </tfoot>

                    </table>


                </div>

            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="action_table" style="display: none;">


                @if($report->accept_status == 0)

                    @if(auth()->user()->hasPermission('reports', 'payment_delete'))

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="3">Маркер</th>
                                <th colspan="2">Операции</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select id="marker_color" class="form-control marker_color">
                                        @foreach(\App\Models\Reports\ReportOrders::MARKER_COLORS as $key => $marker)
                                            <option value="{{$key}}" style="background-color: {{$marker['color']}};">{{$marker['title']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ Form::text('marker_text', '', ['class' => 'form-control', 'id'=>'marker_text']) }}
                                </td>
                                <td class="text-center">
                                    <span class="btn btn-success pull-left" onclick="markerPaymentOrder()">Установить</span>
                                </td>
                                <td width="1%">
                                    <span class="btn btn-danger btn-right" onclick="deletePaymentOrder()">Удалить выбранные</span>
                                </td>
                                <td width="1%">
                                    <span class="btn btn-danger btn-right" onclick="deleteOrder()">Удалить отчет</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    @endif

                @else

                    @if(
                    ($report->accept_status == 1 && auth()->user()->hasPermission('reports', 'reports_matching')) ||
                    ($report->accept_status == 2 && auth()->user()->hasPermission('reports', 'reports_payment')))

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="3">Маркер</th>
                                <th colspan="3">Перерасчёт КВ</th>
                                <th colspan="2">Операции</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <select id="marker_color" class="form-control marker_color">
                                        @foreach(\App\Models\Reports\ReportOrders::MARKER_COLORS as $key => $marker)
                                            <option value="{{$key}}" style="background-color: {{$marker['color']}};">{{$marker['title']}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ Form::text('marker_text', '', ['class' => 'form-control', 'id'=>'marker_text']) }}
                                </td>
                                <td class="text-center">
                                    <span class="btn btn-success pull-left" onclick="markerPaymentOrder()">Установить</span>
                                </td>
                                <td>
                                    <input type="checkbox" name="activate_kv">
                                    {{ Form::text('kv_borderau', '', ['disabled' => 'disabled', 'id' => 'kv_borderau', 'class' => 'form-control kv_input']) }} &nbsp; КВ Бордеро
                                </td>
                                <td>
                                    <input type="checkbox" name="activate_kv">
                                    {{ Form::text('kv_dvou', '', ['disabled' => 'disabled', 'id' => 'kv_dvou', 'class' => 'form-control kv_input']) }}&nbsp; КВ ДВОУ
                                </td>
                                <td width="1%">
                                    <span class="btn btn-success " id="recalc_kv" onclick="recalcKV()">Пересчитать КВ</span>
                                </td>
                                <td width="1%">
                                    <span class="btn btn-danger btn-right" onclick="deletePaymentOrder()">Удалить выбранные</span>
                                </td>
                                <td width="1%">
                                    <span class="btn btn-danger btn-right" onclick="deleteOrder()">Удалить отчет</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    @endif

                @endif



            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 payments_table" style="overflow: auto;">
            @include("payments.reports.reports_payments", ["payments"=>$payments])
        </div>
    </div>

@endsection



@section('js')

    <style>
        .kv_input {
            width: 100px !important;
            display: inline-block;
        }

        .payments_table td {
            white-space: nowrap;
        }

        .payments_table {
            /* overflow-x: scroll; */
        }


        .wrapper1, .wrapper2 {
            width: 100%;
            overflow-x: scroll;
            overflow-y: hidden;
        }

        .wrapper1 {
            height: 20px;
        }

        .wrapper2 {
        }

        .div1 {
            height: 20px;
        }

        .div2 {
            overflow: none;
        }

    </style>

    <script>
        $(function(){

            $(document).on('change', '[name="activate_kv"]', function(){
                toggleKVFields();
            });

            $(document).on('change', '[name="payment[]"]', function(){
                var uncheckeds = $('[name="payment[]"]').length - $('[name="payment[]"]:checked').length;
                $('[name="all_payments"]').prop('checked', uncheckeds === 0);
                showActions();
            });

            $(document).on('change', '[name="all_payments"]', function(){
                var checked = $(this).prop('checked');
                $('[name="payment[]"]').prop('checked', checked);
                showActions();
            });

        });


        function toggleKVFields(){
            $.each($('[name="activate_kv"]'), function(k,v){
                var check = $(this).prop('checked');
                var input = $(this).siblings('input');
                if(check){
                    input.removeAttr('disabled')
                }else{
                    input.attr('disabled', 'disabled');
                }
            })
        }


        function showActions(){
            if($('[name="payment[]"]:checked').length > 0){
                $('#action_table').show();
            }else{
                $('#action_table').hide();
            }
        }


        function getEventData(){
            var event_data = {
                payment_ids: [],
                marker_color: $('#marker_color').val(),
                marker_text: $('#marker_text').val(),
            };

            var borderau_inp = $('#kv_borderau');
            if(!borderau_inp.attr('disabled')){
                event_data['kv_borderau'] = borderau_inp.val()
            }

            var dvou_inp = $('#kv_dvou');
            if(!dvou_inp.attr('disabled')){
                event_data['kv_dvou'] = dvou_inp.val()
            }


            $.each($('[name="payment[]"]:checked'), function(k,v){
                event_data.payment_ids.push($(v).val());
            });
            return event_data;
        }

        function recalcKV(){
            $.post('{{url("/reports/order/{$report->id}/recalc_kv")}}', getEventData(), function(res){
                resetCheckboxes();
                if(res.status === 'ok'){
                    location.reload()
                }
            })
        }

        function deletePaymentOrder() {
            loaderShow();

            $.post('{{url("/reports/order/{$report->id}/delete_payments")}}', getEventData(), function(res){
                if(parseInt(res.status) === 1){
                    $('#action_table').hide();
                    $('[name="payment[]"]').prop('checked', null);
                    $('[name="all_payments"]').prop('checked', null);
                    resetCheckboxes();
                    reload();
                }
            });
        }

        function deleteOrder() {
            loaderShow();

            $.post('{{url("/reports/order/{$report->id}/delete_order")}}', {}, function(res){
                if(parseInt(res.status) === 1){
                   window.location = "/reports/reports_sk/{{$report->agent_organization_id}}/info";
                }
            });
        }

        function markerPaymentOrder() {
            loaderShow();
            $.post('{{url("/reports/order/{$report->id}/marker_payments")}}', getEventData(), function(res){
                if(parseInt(res.status) === 1){
                    $('#action_table').hide();
                    $('#marker_color').val(0);
                    $('#marker_text').val('');
                    $('[name="payment[]"]').prop('checked', null);
                    $('[name="all_payments"]').prop('checked', null);
                    resetCheckboxes();
                    reload();
                }
            });
        }


        function resetCheckboxes(){
            $('[name="activate_kv"]').removeProp('checked');
            $('[name="payment[]"]').removeProp('checked');
            $('#action_table').hide();
            $('#marker_color').val(0);
            $('#marker_text').val('');
            $('[name="payment[]"]').prop('checked', null);
            $('[name="all_payments"]').prop('checked', null);
            $('[name="activate_kv"]').prop('checked', null);
            toggleKVFields();
        }

        function setStatus(status)
        {
            loaderShow();
            $.post('{{url("/reports/order/{$report->id}/set_status")}}', {status:status}, function(res){
                if(parseInt(res.status) === 1){

                    resetCheckboxes();
                    reload();
                }
            });
        }

    </script>
@endsection