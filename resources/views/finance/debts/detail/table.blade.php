<table class="table table-bordered">
    <thead>
        <tr class="head-tr">
            <th class="center">№ п/п</th>
            <th class="center">Дата</th>
            <th class="center">Тип</th>
            <th class="center">Основание</th>
            <th class="center" style="text-align: center;">Сумма долга</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($debts) && count($debts)>0)
            @foreach($debts as $debt)
                @php($overdue = $debt->overdue())
                <tr style="background-color: {{ isset($overdue['color']) ? $overdue['color'] : "#fff" }};">
                    <td class="right">{{ $debt->id }}</td>
                    <td class="right">{{ getDateFormatRu($debt->payment_data) }}</td>
                    <td class="right">{{ $debt->type_ru() }}</td>
                    <td class="right">{{ $debt->bso ? $debt->bso->bso_title : "" }}</td>
                    <td class="right" style="text-align: center;">{{ getPriceFormat($debt->payment_total) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" style="text-align: center">Нет долгов</td>
            </tr>
        @endif
        <tr>
            <td colspan="4" class="right">Всего</td>
            <td class="right" style="text-align: center;">{{ getPriceFormat($summary['all']) }}</td>
        </tr>
    </tbody>
</table>