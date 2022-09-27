<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>№</th>
        <th>Индекс</th>
        <th>Организация</th>
        <th>Страхователь</th>
        <th>Продукт</th>
        <th>№ договора</th>
        <th>Квитанция</th>
        <th>Сумма</th>
        <th>Оф.скидка</th>
        <th>Неофф.скидка</th>
        {{--<th>КВ агента, %</th>
        <th>КВ агента, руб</th>--}}
        <th>КВ бордеро, %</th>
        <th>КВ бордеро, руб</th>
        <th>К оплате</th>
    </tr>
    </thead>
    <tbody>
    @php($total = 0)
{{--    @php($total_kv_agent = 0)--}}
    @php($total_kv_borderau = 0)
    @php($total_sum = 0)
    @if(sizeof($invoice->payments))
        @foreach($invoice->payments as $key => $payment)
            @php($total += $payment->payment_total)
{{--            @php($total_kv_agent += $payment->financial_policy_kv_agent_total)--}}
            @php($total_kv_borderau += $payment->financial_policy_kv_bordereau_total)
            @php($total_sum += $payment->getPaymentAgentSum())
            <tr @if($payment->is_deleted == 1) style="background-color: #ffcccc;" @endif>



                <td>{{ $key+1 }}</td>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->agent_organization ? $payment->agent_organization->title : "" }}</td>
                <td>{{ ($payment->contract->insurer)?$payment->contract->insurer->title:'' }}</td>
                <td>{{ $payment->bso && $payment->bso->product ? $payment->bso->product->title : ""}}</td>
                <td>{{ $payment->bso ? $payment->bso->bso_title : "" }}</td>
                <td>{{ $payment->bso_receipt ? : "" }}</td>
                <td>{{ getPriceFormat($payment->payment_total) }}</td>
                <td>{{ getPriceFormat($payment->official_discount_total) }}</td>
                <td>{{ getPriceFormat($payment->informal_discount_total) }}</td>

                {{--<td>{{ $payment->financial_policy_kv_agent }}</td>
                <td>{{ getPriceFormat($payment->financial_policy_kv_agent_total) }}</td>--}}

                <td>{{ $payment->financial_policy_kv_bordereau }}</td>
                <td>{{ getPriceFormat($payment->financial_policy_kv_bordereau_total) }}</td>

                <td>{{ getPriceFormat($payment->getPaymentAgentSum()) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan='6'>&nbsp</td>
            <td><strong class="itogo">ИТОГО:</strong></td>
            <td><strong>{{ getPriceFormat($total) }}</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
{{--            <td><strong>{{ getPriceFormat($total_kv_agent) }}</strong></td>--}}
            <td><strong>{{ getPriceFormat($total_kv_borderau) }}</strong></td>
            <td><strong>{{ getPriceFormat($total_sum) }}</strong></td>
        </tr>

    @endif
    </tbody>
</table>