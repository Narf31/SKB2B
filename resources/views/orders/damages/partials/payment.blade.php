@extends('layouts.frame')


@section('title')

   Платеж

@stop

@section('content')


   {{ Form::open(['url' => url("/orders/damages/{$damage->id}/payment/{$payment_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}

   <div class="form-group">
      <label class="col-sm-12 control-label">Сумма платежа</label>
      <div class="col-sm-12">
         {{ Form::text('payment_total', titleFloatFormat($payment->payment_total), ['class' => 'form-control sum', 'id'=>'payment_total']) }}
      </div>
   </div>

   <div class="form-group">
      <label class="col-sm-12 control-label">Дата оплаты</label>
      <div class="col-sm-12">
         {{ Form::text('payment_data', setDateTimeFormatRu($payment->payment_data, 1), ['class' => 'form-control format-date datepicker date']) }}
      </div>
   </div>

   <div class="form-group">
      <label class="col-sm-12 control-label">Комментарий</label>
      <div class="col-sm-12">
         {{ Form::textarea('comments', $payment->comments, ['class' => 'form-control', 'required']) }}
      </div>
   </div>


   {{Form::close()}}


@stop

@section('footer')

   @if($payment_id>0)

      <script>


         function deletePayment(url) {
             if (!customConfirm()) return false;

             $.post(url, {
                 _method: 'delete'
             }, function () {
                 window.parent.reloadTab();
             });
         }

      </script>


      <button class="btn btn-danger pull-left" onclick="deletePayment('/orders/damages/{{$damage->id}}/payment/{{$payment_id}}')">{{ trans('form.buttons.delete') }}</button>

   @endif

   <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
