<table class="tov-table">
    <tr>
        <th>Филиал</th>
        <th>Событие</th>
        <th>Статус</th>
        <th>Агент</th>
        <th>БСО</th>
        <th>Тип</th>
        <th>Страхователь</th>
        <th>Период</th>
    </tr>
    <tbody>
    @foreach($bsos as $bso)
        <tr onclick="openPage('/bso/items/{{$bso->id}}/')" class="{{getBsoStatusVolor( $bso->state_id)}}" style="cursor: pointer;">
            <td>{{$bso->supplier ? $bso->supplier->title : ''}}</td>
            <td>{{$bso->location ? $bso->location->title : ""}}</td>
            <td>{{$bso->state ? $bso->state->title : ""}}</td>
            <td>{{$bso->agent ? "{$bso->agent->name} - {$bso->agent->organization->title}" : ""}}</td>
            <td>{{$bso->bso_title}}</td>
            <td>{{$bso->type->title}}</td>
            <td>{{$bso->contract?($bso->contract->insurer?$bso->contract->insurer->title:''):''}}</td>
            <td>{{$bso->contract?(setDateTimeFormatRu($bso->contract->begin_date, 1).' - '.setDateTimeFormatRu($bso->contract->end_date, 1)):''}}</td>
        </tr>
    @endforeach
    </tbody>
</table>