@extends('layouts.frame')

@section('title')
    Редактирование платежной суммы
@endsection



@section('content')

    {{ Form::open([
        'url' => url("/reports/order/{$report->id}/payment_sum/{$payment_sum->id}/save"),
        'method' => 'post',
        'class' => 'form-horizontal'
    ]) }}

        @include('reports.order.payment_sum.form', ['payment_sum' => $payment_sum])

    {{ Form::close() }}


@endsection



@section('footer')
    <button onclick="submitForm()" type="submit" class="btn pull-right btn-primary">{{ trans('form.buttons.save') }}</button>
    <a id="delete_payment_sum" class="btn  pull-left btn-danger">{{ trans('form.buttons.delete') }}</a>
@endsection

@section('js')

    <script>

        $(function(){

            $(document).on('click', '#delete_payment_sum', function(){
                $.post('{{ url("/reports/order/{$report->id}/payment_sum/{$payment_sum->id}/delete") }}', {}, function(){
                    parent_reload();
                });
            })

        });

    </script>

@endsection