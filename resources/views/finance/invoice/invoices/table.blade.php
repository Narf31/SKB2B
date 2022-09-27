<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Юр. лицо</th>
            <th>Тип счёта</th>
            <th>Поток</th>
            <th>Агент</th>
            <th>Статус</th>
            <th>Кол-во платежей</th>
            <th>Общая сумма</th>
        </tr>
    </thead>
    <tbody>
        @if(sizeof($invoices))
            @foreach($invoices as $invoice)
                <tr class="clickable-row" data-href="{{url("/finance/invoice/invoices/{$invoice->id}/edit")}}">
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->org ? $invoice->org->title : "" }}</td>
                    <td>{{ $invoice->types_ru('type')  }}</td>
                    <td>{{ $invoice->payments->first() ? $invoice->payments->first()->payment_flow_ru('payment_flow') : "" }}</td>
                    <td>{{ $invoice->agent ? $invoice->agent->name : "" }}</td>
                    <td>{{ $invoice->statuses_ru('status_id') }}</td>
                    <td>{{ $invoice->payments->count() }}</td>
                    <td>{{ getPriceFormat($invoice->payments->sum('payment_total')) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-center">Нет доступных счетов</td>
            </tr>
        @endif
    </tbody>
</table>