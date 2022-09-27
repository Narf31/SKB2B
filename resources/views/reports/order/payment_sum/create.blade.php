@extends('layouts.frame')

@section('title')
    Создание платежной суммы
@endsection



@section('content')

    {{ Form::open([
        'url' => url("/reports/order/{$report->id}/payment_sum/store"),
        'method' => 'post',
        'class' => 'form-horizontal'
    ]) }}

    @include('reports.order.payment_sum.form', ['payment_sum' => $payment_sum])

    {{ Form::close() }}

@endsection



@section('footer')
    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>
@endsection