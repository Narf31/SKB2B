<table class="tov-table">
    <thead>
        <tr>
            <th>Агент</th>
            <th>Куратор</th>
            <th>Всего</th>
            <th>Из них старых (более 30 дней) *</th>
            <th>Из них старых (более 90 дней)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($agents as $agent)
            @php($agent_bsos = isset($bso_items[$agent['id']]) ? $bso_items[$agent['id']] : collect([]))
            @if(count($agent_bsos) > 0)

                <tr>
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->curator ? $agent->curator->name : "" }}</td>
                    <td>
                        <a href="/bso/inventory_agents/details/?agent_id={{$agent->id}}&point_sale_id={{request()->get('point_sale_id') ?? '-1'}}&type_bso_id={{request()->get('type_bso_id') ?? '-1'}}&nop_id={{request()->get('nop_id') ?? '-1'}}&types=bso_in" data-type="stock" data-type_bso="4" target="_blank">
                            {{ $agent_bsos->count() }}
                        </a>
                    </td>
                    <td>
                        <a href="/bso/inventory_agents/details/?agent_id={{$agent->id}}&point_sale_id={{request()->get('point_sale_id') ?? '-1'}}&type_bso_id={{request()->get('type_bso_id') ?? '-1'}}&nop_id={{request()->get('nop_id') ?? '-1'}}&types=bso_in_30" data-type="stock" data-type_bso="4" target="_blank">
                            {{ $agent_bsos->filter(function($item){ return strtotime($item->time_create) < time() - 60*60*24*30; })->count() }}
                        </a>
                    </td>
                    <td>
                        <a href="/bso/inventory_agents/details/?agent_id={{$agent->id}}&point_sale_id={{request()->get('point_sale_id') ?? '-1'}}&type_bso_id={{request()->get('type_bso_id') ?? '-1'}}&nop_id={{request()->get('nop_id') ?? '-1'}}&types=bso_in_90" data-type="stock" data-type_bso="4" target="_blank">
                            {{ $agent_bsos->filter(function($item){ return strtotime($item->time_create) < time() - 60*60*24*90; })->count() }}

                        </a>
                    </td>
                </tr>
            @endif
        @endforeach

    </tbody>
</table>