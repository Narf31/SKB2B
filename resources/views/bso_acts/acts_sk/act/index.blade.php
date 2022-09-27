@extends('layouts.app')


@php

    $monthes = getRuMonthes();
    $years = getYearsRange(-5, +1);

@endphp


@section('content')
    <div class="page-heading">
        <h2 class="inline-h1">Акт приема передачи</h2>
        <a href="/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/" class="btn btn-primary btn-right">Назад</a>
    </div>

    <div class="block-main" style="margin-top: 5px;">
        <div class="block-sub">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    {!! Form::open(['name'=>"act_form"]) !!}

                        <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Номер</th>
                                <td>{{$act->id}}</td>
                            </tr>
                            <tr>
                                <th>Название</th>
                                <td>
                                    <input name="title" class="form-control" type="text" value="{{$act->title}}">
                                </td>
                            </tr>
                            <tr>
                                <th>Сформирован:</th>
                                <td>{{$act->created_at}}</td>
                            </tr>
                            <tr>
                                <th>Пользователем:</th>
                                <td>{{$act->create_user ? $act->create_user->name : ""}}</td>
                            </tr>
                            <tr>
                                <th>Страховая компания:</th>
                                <td>{{$act->bso_supplier ? $act->bso_supplier->title : ""}}</td>
                            </tr>
                            <tr>
                                <th>Отчетный период:</th>
                                <td>
                                    {{ Form::select('report_month', $monthes, $act->report_month, ['class' => 'form-control', 'style' => 'width: 130px; display: inline']) }} /
                                    {{ Form::select('report_year', $years, $act->report_year, ['class' => 'form-control', 'style' => 'width: 130px; display: inline']) }}

                                </td>
                            </tr>
                            <tr>
                                <th>Дата заключения договора с:</th>
                                <td>
                                    {{ Form::text('report_date_start', setDateTimeFormatRu($act->report_date_start, 1), ['class' => 'form-control datepicker date']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Дата заключения договора по:</th>
                                <td>
                                    {{ Form::text('report_date_end', setDateTimeFormatRu($act->report_date_end, 1), ['class' => 'form-control datepicker date']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Подписант организации</th>
                                <td>
                                    {{ Form::text('signatory_org', $act->signatory_org, ['class' => 'form-control']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Подписант поставщика</th>
                                <td>
                                    {{ Form::text('signatory_sk_bso_supplier', $act->signatory_sk_bso_supplier, ['class' => 'form-control']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Акцептован:</th>
                                <td>
                                    @if($act->accept_status)
                                        Да
                                    @else
                                        Нет <a id="accept_payment" class="btn btn-primary btn-right">Акцептовать</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                @if($act->type_id == 0)
                                    <td colspan="2">
                                        <a class="btn btn-success btn-left doc_export_btn" href="/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/{{$act->id}}/export">
                                            Сформировать акт
                                        </a>
                                        <span class="btn btn-primary btn-right" id="save_act">Сохранить</span>
                                    </td>
                                @else
                                    <td>
                                        <a href="#" id="form-report" class="report-btn btn btn-primary btn-left">Сформировать отчет на основе акта</a>
                                    </td>
                                    <td>
                                        <a class="btn btn-success btn-left doc_export_btn" href="/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/{{$act->id}}/export">
                                            Сформировать акт
                                        </a>
                                        <span class="btn btn-primary btn-right" id="save_act">Сохранить</span>
                                    </td>
                                @endif

                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}

                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

                </div>
            </div>


            @if($act->type_id == 0)
                @include('bso_acts.acts_sk.bso.act_bso_table')
            @elseif($act->type_id == 1)
                @include('bso_acts.acts_sk.contracts.act_payment_table')
            @endif

        </div>
    </div>

@endsection



@section('js')
    <script>
        $(function(){

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


            $(document).on('click', '#accept_payment', function(){
                $.post('/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/{{$act->id}}/accept', {}, function(res){
                    if(res.status === 'ok'){
                        location.reload();
                    }
                });
            });


            $(document).on('click', '#save_act', function(){
                var data = $('[name="act_form"]').serialize();
                $.post('/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/{{$act->id}}/update', data, function(res){
                    if(res.status === 'ok'){
                        location.reload();
                    }
                })
            });


            $(document).on('click', '#delete_items', function(){

                var data = getEventData();

                if(checkAllSelect()){
                    var delete_act = confirm('Вы удаляете все элементы. Необходимо так же расформировать акт?')
                    data.delete_act =  delete_act ? 1 : 0;
                }

                $.post('/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/{{$act->id}}/delete_items', data, function(res){
                    if(res.status === 'ok'){
                        if(delete_act){
                            location.href = "/bso_acts/acts_sk/{{$act->bso_supplier->id}}/acts/";
                        }else{
                            location.reload();
                        }
                    }
                })
            });





        });

        function showActions(){
            if($('[name="bso_item[]"]:checked').length > 0){
                $('#actions').show();
            }

            if($('[name="payment[]"]:checked').length > 0){
                $('#actions').show();
            }
        }

        function checkAllSelect(){
            var type_name = getTypeName();
            var uncheckeds = $('[name="'+type_name+'[]"]').length - $('[name="'+type_name+'[]"]:checked').length;
            return uncheckeds === 0;
        }

        function getTypeName(){
            var type_name = '';
            if($('[name="payment[]"]:checked').length > 0){
                type_name = 'payment';
            }else if($('[name="bso_item[]"]:checked').length > 0){
                type_name = 'bso_item';
            }
            return type_name;
        }

        function getEventData(){
            var event_data = {
                item_ids: [],
            };
            $.each($('[name="'+getTypeName()+'[]"]:checked'), function(k,v){
                event_data.item_ids.push($(v).val());
            });
            return event_data;
        }


    </script>
@endsection