<table class="tov-table">
    <tr>
        <th>Филиал</th>
        <th>Статус</th>
        <th>Агент</th>
        <th>БСО</th>
        <th>Тип</th>
        <th>Страхователь</th>
        <th>Период</th>
    </tr>
    <tbody>
    @foreach($contracts as $contract)
        <tr onclick="openPage('/contracts/online/{{$contract->id}}/')" class="{{getContractStatusVolor($contract)}}" style="cursor: pointer;">
            <td>{{$contract->bso_supplier ? $contract->bso_supplier->title : ''}}</td>
            <td>{{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}</td>
            <td>{{$contract->agent ? "{$contract->agent->name} - {$contract->agent->organization->title}" : ""}}</td>
            <td>{{$contract->bso->bso_title}}</td>
            <td>{{$contract->product->title}}</td>
            <td>{{$contract->insurer?$contract->insurer->title:''}}</td>
            <td>{{setDateTimeFormatRu($contract->begin_date, 1).' - '.setDateTimeFormatRu($contract->end_date, 1)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>