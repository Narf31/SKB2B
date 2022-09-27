<table class="tov-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Дата оплаты</th>
        <th>Статус</th>
        <th>Алгоритм рассрочки</th>
        <th>Сумма платежа</th>
    </tr>
    </thead>
    <tbody>
    @if(sizeof($payments))
        @foreach($payments as $payment)
            <tr style="cursor: pointer;">
                <td>{{$payment->payment_number}}</td>
                <td>{{setDateTimeFormatRu($payment->payment_data, 1)}}</td>
                <td>{{\App\Models\Contracts\Payments::STATUS[$payment->statys_id]}}</td>
                <td>{{titleFloatFormat($payment->installment_algorithms_payment)}}%</td>
                <td>{{titleFloatFormat($payment->payment_total-$payment->official_discount_total)}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>