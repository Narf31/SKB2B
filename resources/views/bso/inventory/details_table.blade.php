<table class="bso_table">
<thead>
    <tr>
        <th>№ п/п</th>
        <th>Организация</th>
        <th>Филиал</th>
        <th>Вид страхования</th>
        <th>№ полиса / квит. / сер.карт с</th>
        <th>Точка оборота БСО</th>
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
    @if(sizeof($result))
        @foreach($result as $key => $bso)
            <tr class="{{\App\Models\BSO\BsoState::STATUS_COLORS[$bso->bso_states->id]}}">
                <td>{{ $key+1 }}</td>
                <td>{{ $bso->supplier_org ? $bso->supplier_org->title : "" }}</td>
                <td>{{ $bso->supplier ? $bso->supplier->title : "" }}</td>
                <td>{{ $bso->product ? $bso->product->title : "" }}</td>
                <td>{{ $bso->bso_title }}</td>
                <td>{{ $bso->point_sale ? $bso->point_sale->title : "" }}</td>
                <td>{{ $bso->bso_locations ? $bso->bso_locations->title : "" }}</td>
                <td>{{ $bso->bso_states ? $bso->bso_states->title : "" }}</td>
                <td>{{ $bso->agent ? $bso->agent->name.' '.$bso->agent->organization->title : "" }}</td>
                <td>{{ $bso->agent && $bso->agent->curator ? $bso->agent->curator->name : "" }}</td>
                <td>{{ setDateTimeFormatRu($bso->time_create, 1) }}</td>
                <td>{{ $bso->time_on_stock() }}</td>
                <td>{{ setDateTimeFormatRu($bso->last_operation_time, 1) }}</td>
                <td>{{ $bso->time_on_agent() }}</td>
                <td>
                    @if($bso->payments)
                        @foreach($bso->payments as $payment)
                            @if($payment->acts_sk_id > 0)
                                <a target="_blank" href="/bso_acts/acts_sk/{{$bso->bso_supplier_id}}/acts/{{$payment->acts_sk_id}}/edit">
                                    {{$payment->act_sk->title ? $payment->act_sk->title : 'Без названия'}}
                                </a>
                            @endif
                        @endforeach
                    @elseif($bso->act_sk)
                        <a target="_blank" href="/bso_acts/acts_sk/{{$bso->bso_supplier_id}}/acts/{{$bso->act_sk->id}}/edit">
                            {{$bso->act_sk->title ? $bso->act_sk->title : 'Без названия'}}
                        </a>
                    @else
                        ---
                    @endif
                </td>
                <td>
                    @if($bso->payments)
                        @foreach($bso->payments as $payment)
                            @if($payment->reports_order_id > 0)
                                <a href="{{url("/reports/order/{$payment->reports_order_id}/")}}" target="_blank">
                                    {{$payment->reports_border->title ? $payment->reports_border->title : "Без названия"}}
                                </a>
                            @else
                                ---
                            @endif
                            /
                            @if($payment->reports_dvou_id > 0)
                                <a href="{{url("/reports/order/{$payment->reports_dvou_id}/")}}" target="_blank">
                                    {{$payment->reports_dvoy->title ? $payment->reports_dvoy->title : "Без названия"}}
                                </a>
                            @else
                                ---
                            @endif
                        @endforeach

                    @else
                        ---
                    @endif
                </td>
                <td><a href="{{url("/bso/items/{$bso['id']}/")}}" target="_blank">подробнее</a></td>
            </tr>
        @endforeach
    @endif

</tbody>

</table>