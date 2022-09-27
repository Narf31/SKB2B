@extends('layouts.frame')

@section('title')
    Подтверждение оплаты взнос # {{$payment->payment_number}}
@stop

@section('content')

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 300px;" >
        <div class="row form-horizontal">

            {{ Form::open(['url' => url("/contracts/online/{$contract->id}/payment/{$payment->id}"), 'method' => 'post',  'class' => 'form-horizontal', 'id' => 'formContract']) }}

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label pull-left" style="margin-top: 5px;" >
                    {{$contract->bso->bso_title}}
                </label>

                <input type="hidden" name="contract[payment][bso_id]" id="bso_id" value="{{$contract->bso_id}}"/>
                <input type="hidden" name="contract[payment][bso_supplier_id]" id="bso_supplier_id" value="{{$contract->bso_supplier_id}}"/>
                <input type="hidden" name="contract[payment][product_id]" id="product_id" value="{{$contract->product_id}}"/>
                <input type="hidden" name="contract[payment][agent_id]" id="agent_id" value="{{$contract->agent_id}}"/>

            </div>

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label pull-left">
                    Тип оплаты
                </label>

                <select class="form-control" id="payment_type" onchange="viewPaymentTypeForm()" name="contract[payment][payment_type]">
                    @if(sizeof($sk_products_payment))
                        @foreach($sk_products_payment as $payment_type)
                        @if(isset($payment_type->id))
                            <option value="{{$payment_type->id}}" data-key_type="{{$payment_type->key_type}}">{{$payment_type->title}}</option>
                        @endif
                        @endforeach
                    @endif
                </select>


            </div>

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 payment payment_type_0" style="display: none;" >

                <label class="control-label pull-left">
                    Квитанция
                </label>
                {{ Form::text('contract[payment][bso_receipt]', '', ['class' => 'form-control valid_fast_accept', 'id'=>'bso_receipt']) }}
                <input type="hidden" name="contract[payment][bso_receipt_id]" id="bso_receipt_id" />


            </div>

            <div class="row col-xs-4 col-sm-4 col-md-4 col-lg-4 payment payment_type_1" style="display: none;" >
                <label class="control-label pull-left">
                    Куда
                </label>
                {{ Form::select('contract[payment][payment_type_send_checkbox]', collect([0=>'Email',1=>'Телефон']), null, ['class' => 'form-control',]) }}

            </div>

            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 payment payment_type_1" style="display: none;" >
                <label class="control-label pull-left">
                    Отправить чек
                </label>
                {{ Form::text('contract[payment][payment_send_checkbox]', '', ['class' => 'form-control ', 'id'=>'payment_send_checkbox']) }}

            </div>

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 payment payment_type_2" style="display: none;" >
            </div>
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 payment payment_type_3" style="display: none;" >
            </div>
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 payment payment_type_4" style="display: none;" >

                <label class="control-label pull-left">
                    Email
                </label>
                {{ Form::text('contract[payment][send_email]', ($contract->insurer)?$contract->insurer->email:'', ['class' => 'form-control valid_fast_accept', 'id'=>'send_email']) }}

            </div>

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 payment payment_type_5" style="display: none;" >

                <label class="control-label pull-left">
                    Код
                </label>
                {{ Form::text('contract[payment][promocode]', '', ['class' => 'form-control valid_fast_accept', 'id'=>'promocode']) }}

            </div>


            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <br/><br/>

                <center><span style="font-size: 24px;color: red;" id="errors_text"></span></center>

            </div>


            {{Form::close()}}


        </div>
    </div>

@stop

@section('footer')

<span class="btn btn-success pull-right" id="butt_accept" onclick="sendAccept()" >Оплатить</span>
<br/><br/>
@stop

@section('js')
<script>


    function initPayments()
    {
        activSearchBso("bso_receipt", '', 2);
        viewPaymentTypeForm();

    }

    document.addEventListener("DOMContentLoaded", function (event) {
        initPayments();
    });

    function selectBso(object_id, key, type, suggestion)
    {

        if(parseInt(type) == 2){ // Квитанция
            $('#bso_receipt_id').val(data.bso_id);
        }
    }



    function viewPaymentTypeForm()
    {
        payment_type = $("#payment_type").find(':selected').data('key_type');

        $('.payment').hide();
        $('.payment_type_'+payment_type).show();

        setErrors("");
    }


    function setErrors(msg)
    {
        $("#errors_text").html(msg);
        return false;
    }


    function sendAccept() {
        //Проверка валиидации

        setErrors("");

        payment_type = $("#payment_type").find(':selected').data('key_type');

        if(parseInt(payment_type) == 0){

            if(parseInt($("#bso_receipt_id").val()) > 0){

            }else{
                return setErrors("Укажите квитанцию");
            }

        }

        if(parseInt(payment_type) == 1){
            if($("#payment_send_checkbox").val().length > 3){

            }else{
                return setErrors("Куда отправить чек");
            }
        }

        if(parseInt(payment_type) == 4 ){
            if($("#send_email").val().length > 3){

            }else{
                return setErrors("Укажите Email");
            }

        }

        if(parseInt(payment_type) == 5 ){
            if($("#promocode").val().length > 3){

            }else{
                return setErrors("Укажите промокод");
            }
        }

        loaderShow();


        $.post('{{url("/contracts/online/{$contract->id}/payment/{$payment->id}")}}', $('#formContract').serialize(), function (response) {


            if (Boolean(response.state) === true) {

                return parent_reload();

            }else {
                return setErrors(response.msg);
            }

        }).always(function () {
            loaderHide();
        });

        return true;

    }

</script>

@stop