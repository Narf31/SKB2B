<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><input type="checkbox" class="check_all_checkbox" name="all_payments"></th>
            <th>Тип</th>
            <th>Номер договора</th>
            <th>Страхователь</th>
            <th>СК</th>
            <th>Продукт</th>
            <th>Тип платежа</th>
            <th>Поток оплаты</th>
            <th>Квитанция</th>
            <th>Сумма</th>
            {{--<th>КВ агента, %</th>
            <th>КВ агента, руб</th>--}}
            <th>КВ %</th>
            <th>КВ руб</th>
            <th>К оплате</th>
        </tr>

    </thead>
    <tbody>
        @if(sizeof($payments))
            @foreach($payments as $payment)
                <tr>
                    <td><input type="checkbox" name="payment[{{$payment->id}}]" value="{{$payment->id}}"></td>
                    <td>{{ \App\Models\Contracts\Payments::TRANSACTION_TYPE[$payment->type_id] }} {{ ($payment->type_id == 0)? $payment->payment_number : '' }}</td>
                    <td>{{ $payment->bso ? $payment->bso->bso_title : "" }}</td>
                    <td>
                        {{ ($payment->type_id == 0)? $payment->getInsurer() : $payment->comments }}
                    </td>
                    <td>{{ $payment->bso && $payment->bso->insurance ? $payment->bso->insurance->title : "" }} {{ $payment->bso && $payment->bso->supplier_org ? $payment->bso->supplier_org->title : "" }}</td>
                    <td>{{ $payment->bso && $payment->bso->product ? $payment->bso->product->title : ""}}</td>
                    <td>{{ \App\Models\Contracts\Payments::PAYMENT_TYPE[$payment->payment_type] }}</td>
                    <td>{{ \App\Models\Contracts\Payments::PAYMENT_FLOW[$payment->payment_flow] }}</td>
                    <td>{{ $payment->bso_receipt }}</td>
                    <td>{{ getPriceFormat($payment->payment_total) }}</td>
                    {{--<td>{{ $payment->financial_policy_kv_agent }}</td>
                    <td>{{ $payment->financial_policy_kv_agent_total }}</td>--}}
                    <td>{{ $payment->financial_policy_kv_bordereau }}</td>
                    <td>{{ $payment->financial_policy_kv_bordereau_total }}</td>
                    <td>{{ getPriceFormat($payment->getPaymentAgentSum()) }}</td>
                </tr>
            @endforeach
        @else

            <tr>
                <td colspan="13" class="text-center">Нет доступных платежей</td>
            </tr>
        @endif
    </tbody>
</table>