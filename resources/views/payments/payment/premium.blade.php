@extends('layouts.frame')


@section('title')

    Выплата

@stop

@section('content')


    {{ Form::open(['url' => url("/payment/".(int)$payment->id."/"), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <input type="hidden" name="payment[type_id]" value="{{$payment->type_id}}"/>
    <input type="hidden" name="payment[bso_id]" value="{{$payment->bso_id}}"/>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-12 control-label">Агент</label>
        <div class="col-sm-12">
            {{ Form::select('payment[agent_id]', $agents->prepend('Выберите значение', 0), $payment->agent_id, ['class' => 'form-control select2']) }}
        </div>
    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <label class="col-sm-12 control-label pull-left" style="margin-top: 5px;">
            Сумма
        </label>
        <label class="control-label pull-right" style="margin-right: 15px;">Зачислить на баланс {{ Form::checkbox('payment[set_balance]', 1, $payment->set_balance) }} </label>


        <div class="col-sm-12">
            {{ Form::text('payment[payment_total]', (strlen($payment->payment_total)>1)?titleFloatFormat($payment->payment_total):'', ['class' => 'form-control sum', 'required']) }}
        </div>
    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-12 control-label">Общий комменатрий</label>
        <div class="col-sm-12">
            {{ Form::textarea("payment[comments]", $payment->comments, ['class' => 'form-control']) }}
        </div>
    </div>



    {{Form::close()}}

@stop

@section('footer')

    @include("payments.payment.buttons", ["payment" => $payment])

@stop
