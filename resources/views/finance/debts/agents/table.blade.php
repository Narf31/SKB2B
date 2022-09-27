<table class="table table-bordered">
    <thead>
        <tr class="head-tr">
            <th class="center">Агент</th>
            <th class="center">Руководитель</th>
            <th class="center">Сумма долга нал</th>
            <th class="center">Сумма долга безнал</th>
            <th class="center">Сумма долга СК</th>
            <th class="center">Сумма долга</th>
            @foreach($user_balances as $balance)
                <th class="center">{{$balance->title}}</th>
            @endforeach
        </tr>
    </thead>
    @foreach($user_balances as $balance)
     @php ($total[$balance->id] = 0)
    @endforeach
    <tbody>
        @if(isset($agent_summaries) && count($agent_summaries)>0)
        @foreach($agent_summaries as $agent_id => $agent_summary)
            @if(isset($agents[$agent_id]))
                @php($agent = $agents[$agent_id])
                @php($primary_debt = $agent->debts()->orderBy('payment_data')->first())
                @php($agent_overdue = $primary_debt ? $primary_debt->overdue() : "")
                <tr class="clickable-row" data-href="/finance/debts/{{$agent['id']}}/detail" style="background-color: {{ isset($agent_overdue['color']) ?  $agent_overdue['color'] : "#fff"  }};">
                    <td class="right">{{ $agent->name }}</td>
                    <td class="right">{{ $agent->perent ? $agent->perent->name : ""}}</td>
                    <td class="right">{{ isset($agent_summary['cash']) ? getPriceFormat($agent_summary['cash']) : "0,00" }}</td>
                    <td class="right">{{ isset($agent_summary['cashless']) ? getPriceFormat($agent_summary['cashless']) : "0,00" }}</td>
                    <td class="right">{{ isset($agent_summary['sk']) ? getPriceFormat($agent_summary['sk']) : "0,00" }}</td>
                    <td class="right">{{ isset($agent_summary['all']) ? getPriceFormat($agent_summary['all']) : "0,00" }}</td>
                    @foreach($user_balances as $balance)
                    <td class="right"> {{titleFloatFormat($agent->getBalance($balance->id)->balance)}}</td>
                    @php ($total[$balance->id] += $agent->getBalance($balance->id)->balance)
                    @endforeach
                </tr>
            @endif
        @endforeach
        @else
            <tr>
                <td colspan="56" style="text-align: center">Нет долгов</td>
            </tr>
        @endif
        <tr>
            <td colspan="2" class="right">Итого</td>
            <td class="right">{{ isset($summary['cash']) ? getPriceFormat($summary['cash']) : getPriceFormat(0) }}</td>
            <td class="right">{{ isset($summary['cashless']) ? getPriceFormat($summary['cashless']) : getPriceFormat(0) }}</td>
            <td class="right">{{ isset($summary['sk']) ? getPriceFormat($summary['sk']) : getPriceFormat(0) }}</td>
            <td class="right">{{ isset($summary['all']) ? getPriceFormat($summary['all']) : getPriceFormat(0) }}</td>
            @foreach($user_balances as $balance)
             <td class="right">{{titleFloatFormat($total[$balance->id])}}</td>
            @endforeach
        </tr>

    </tbody>
</table>