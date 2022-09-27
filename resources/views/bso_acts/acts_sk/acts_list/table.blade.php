<table class="table table-bordered bso_items_table">
    <thead>
        <tr>
            <th>Номер</th>
            <th>Наименование</th>
            <th>Месяц</th>
            <th>Год</th>
            <th>Тип</th>
            <th>Количество БСО/договоров</th>
            <th width="10%">Открыть</th>
        </tr>
    </thead>
    <tbody class="bso_items_table_tbody">
        @if(sizeof($acts))
            @foreach($acts as $act)
                <tr class="clickable-row" data-href="123">
                    <th>{{ $act->id }}</th>
                    <th>{{ $act->title }}</th>
                    <th>{{ getMonthById($act->report_month) }}</th>
                    <th>{{ $act->report_year }}</th>
                    <th>{{ $act->type_ru() }}</th>
                    <th>{{ $act->bso_items()->count() + $act->payments()->count() }}</th>
                    <th>
                        <a href="/bso_acts/acts_sk/{{$supplier->id}}/acts/{{$act->id}}/edit" class="btn btn-primary btn-left">Открыть</a>
                    </th>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">Нет актов</td>
            </tr>
        @endif
    </tbody>
</table>