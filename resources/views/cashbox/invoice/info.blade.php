@if(sizeof($invoices))
    <table class="tov-table">
        <thead>
        <tr>
            <th>Номер</th>
            <th>Филиал</th>
            <th>Тип счёта</th>
            <th>Агента</th>
            <th>Статус</th>
            <th>Кол-во платежей</th>
            <th>Общая сумма</th>
        </tr>
        </thead>
        @foreach($invoices as $invoice)
            <tr class="clickable-row @if($invoice->status_id == 2) bg-green @endif" data-href="{{url("/cashbox/invoice/{$invoice->id}/edit")}}" >
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->org ? $invoice->org->title : "" }}</td>
                <td>{{ $invoice->payment_method ? $invoice->payment_method->title : ""}}</td>
                <td>{{ $invoice->agent ? $invoice->agent->name : "" }}</td>
                <td>{{ $invoice->statuses_ru('status_id')}}</td>
                <td>{{ $invoice->payments->count() }}</td>
                <td>{{ getPriceFormat($invoice->payments->sum('payment_total')) }}</td>
            </tr>
        @endforeach

    </table>
@else
    {{ trans('form.empty') }}
@endif