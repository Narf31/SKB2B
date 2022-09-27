<table class="bso_table">
    <thead>
        <tr>
            <th>№ п/п</th>
            <th>Филиал</th>
            <th>Вид страхования</th>
            <th>№ полиса / квит. / сер.карт с</th>
            <th>Точка продаж</th>
            <th>Событие</th>
            <th>Статус</th>
            <th>Агент</th>
            <th>Куратор</th>
            <th>Дата приема на склад</th>
            <th>Дней на складе</th>
            <th>Дата последней операции</th>
            <th>Дней у агента</th>
            <th>Акт приема передачи в СК</th>
            <th>Номер Отчета</th>
            <th>История</th>
        </tr>
    </thead>

    <tbody>
    @if(sizeof($acts))
        @foreach($acts as $key => $bso)
            <tr class="{{($bso->type->day_agent > 0 && $bso->time_on_agent() >= $bso->type->day_agent) ? 'bg-red' : ''}}">
                <td>{{ $key+1 }}</td>
                <td>{{ $bso->supplier ? $bso->supplier->title : "" }}</td>
                <td>{{ $bso->product ? $bso->product->title : "" }}</td>
                <td>{{ $bso->bso_title }}</td>
                <td>{{ $bso->point_sale ? $bso->point_sale->title : "" }}</td>
                <td>{{ $bso->bso_locations ? $bso->bso_locations->title : "" }}</td>
                <td>{{ $bso->bso_states ? $bso->bso_states->title : "" }}</td>
                <td>{{ $bso->agent ? $bso->agent->name.' - '.$bso->agent->organization->title : "" }}</td>
                <td>{{ $bso->agent && $bso->agent->curator ? $bso->agent->curator->name : "" }}</td>
                <td>{{ setDateTimeFormatRu($bso->time_create, 1) }}</td>
                <td>{{ $bso->time_on_stock() }}</td>
                <td>{{ setDateTimeFormatRu($bso->last_operation_time, 1) }}</td>
                <td>{{ $bso->time_on_agent() }}</td>

                <td>
                    @if($bso->act_sk)
                        <a target="_blank" href="/bso_acts/acts_sk/{{$bso->bso_supplier_id}}/acts/{{$bso->act_sk->id}}/edit">{{$bso->act_sk->title}}</a>
                    @else
                        <a target="_blank" href="#">---</a>

                    @endif
                </td>
                <td><a target="_blank" href="#">---</a></td>
                <td><a href="{{url("/bso/items/{$bso['id']}/")}}" target="_blank">подробнее</a></td>
            </tr>

        @endforeach
    @endif
    </tbody>


</table>