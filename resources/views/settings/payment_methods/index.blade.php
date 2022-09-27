@extends('layouts.app')

@section('content')

    <div class="page-heading">
        <h1 class="inline-h1">{{ trans('menu.payment_methods') }}</h1>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url('/settings/payment_methods/create')  }}')">
            {{ trans('form.buttons.create') }}
        </span>
    </div>

    @if(sizeof($pay_methods))
        <table class="tov-table">
            <thead>
                <tr>
                    <th><a href="javascript:void(0);">{{ trans('settings/banks.title') }}</a></th>
                    <th><a href="javascript:void(0);">{{ trans('settings/banks.is_actual') }}</a></th>
                    <th><a href="javascript:void(0);">Тип оплаты</a></th>
                    <th><a href="javascript:void(0);">Поток оплаты</a></th>
                    <th><a href="javascript:void(0);">Интерфейс</a></th>
                </tr>
            </thead>
            @foreach($pay_methods as $pay_method)
                <tr onclick="openFancyBoxFrame('{{ url("/settings/payment_methods/$pay_method->id/edit") }}')">
                    <td>{{ $pay_method->title }}</td>

                    <td>{{ (isset($pay_method->is_actual) && $pay_method->is_actual==1)? trans('form.yes') :trans('form.no') }}</td>

                    <td>{{ isset($pay_method->payment_type) && $pay_method->payment_type >=0? \App\Models\Contracts\Payments::PAYMENT_TYPE[$pay_method->payment_type]:'' }}</td>

                    <td>{{ isset($pay_method->payment_flow) && $pay_method->payment_flow >=0? \App\Models\Contracts\Payments::PAYMENT_FLOW[$pay_method->payment_flow]:'' }}</td>

                    <td>{{ isset($pay_method->key_type) && $pay_method->key_type >=0? \App\Models\Settings\PaymentMethods::KEY_TYPE[$pay_method->key_type]:'' }}</td>
                </tr>
            @endforeach

        </table>
    @else
        {{ trans('form.empty') }}
    @endif

@endsection
