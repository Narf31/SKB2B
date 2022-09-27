@extends('layouts.frame')

@section('title')
    Статус взнос # {{$payment->payment_number}}
@stop

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 300px;" >
        <div class="row form-horizontal">

            <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

                @if($contract->bso)
                <div class="view-field">
                    <span class="view-label">Договор</span>
                    <span class="view-value">{{$contract->bso->bso_title}}</span>
                </div>
                @endif

                <div class="view-field">
                    <span class="view-label">Программа</span>
                    <span class="view-value">{{$contract->getProductOrProgram()->title}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Сумма платежа</span>
                    <span class="view-value">{{titleFloatFormat($payment->payment_total)}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Дата платежа</span>
                    <span class="view-value">{{setDateTimeFormatRu($payment->payment_data, 1)}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Счет</span>
                    <span class="view-value">#{{$payment->invoice_id}}</span>
                </div>

                <div class="view-field">
                    <span class="view-label">Метод оплаты</span>
                    <span class="view-value">

                        @if($payment->payment_method->key_type == 4)
                        <a href="{{$payment->invoice->payment_linck}}" target="_blank">{{$payment->payment_method->title}}</a>
                        @else
                            {{$payment->payment_method->title}}
                        @endif

                    </span>
                </div>

                @if($payment->payment_method->key_type == 4)
                    <div class="view-field">
                        {{-- <span class="pull-left">{{$payment->invoice->payment_linck}}</span> --}}
                    </div>
                @endif
            </div>


            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <span style="font-size: 24px;color: red;" id="errors"></span>


            </div>

        </div>
    </div>

@stop

@section('footer')
    @if($payment->payment_method->control_type == 0)
        <a class="btn btn-danger pull-left" href="{{url("/contracts/online/{$contract->id}/payment/{$payment->id}?edit=1")}}">Изменить метод оплаты</a>
    @endif
    <span class="btn btn-success pull-right" onclick="checkStatus()">Проверить статус</span>
@stop

@section('js')
<script>


    function checkStatus() {

        $("#errors").html('');
        loaderShow();

        $.post('{{url("/contracts/online/{$contract->id}/payment/{$payment->id}/check-status")}}', {}, function (response) {


            if (Boolean(response.state) === true) {

                return parent_reload();

            }else {
                $("#errors").html(response.msg);
            }

        }).always(function () {
            loaderHide();
        });

    }



</script>

@stop