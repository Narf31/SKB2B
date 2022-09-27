<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Дата создания</th>
            <th>Сумма</th>
            <th>Агент</th>
            <th colspan="3">Действия</th>
        </tr>
    </thead>
    <tbody>
        @if(sizeof($reservations))
            @foreach($reservations as $reservation)
                <tr>
                    <td>{{$reservation->id}}</td>
                    <td>{{$reservation->created_at}}</td>
                    <td>{{ getPriceFormat($reservation->amount)}}</td>
                    <td>{{$reservation->user ? $reservation->user->name : ""}}</td>
                    <td class="text-center">
                        <a href="/finance/invoice/reservation/{{$reservation->id}}/export" class="btn-sm btn-success doc_export_btn">Печать</a>
                    </td>
                    <td class="text-center">
                        <a href="/finance/invoice/reservation/{{$reservation->id}}/edit"  target="_blank" class="btn-sm btn-primary">Открыть</a>
                    </td>
                    <td class="text-center">
                        <a href="#" data-href="/finance/invoice/reservation/{{$reservation->id}}/delete" class="btn-sm btn-danger delete-reservation">Удалить</a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">Нет доступных резервных счетов</td>
            </tr>
        @endif
    </tbody>
</table>
