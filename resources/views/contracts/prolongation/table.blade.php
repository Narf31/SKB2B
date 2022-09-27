<table class="table table-bordered text-left payments_table huck">
    <thead>
    <tr>
        <th>Номер договора</th>
        <th>Статус</th>
        <th>Период действия</th>

        <th>Продукт</th>
        <th>Страхователь</th>

        <th>Страховая сумма</th>
        <th>Страховая премия</th>

        <th>Агент</th>
        <th>Агент - Организация</th>
    </tr>
    </thead>
    <tbody>


    @foreach($contracts as $contract)

        <tr style="cursor: pointer;" onclick="openPage('/contracts/online/{{$contract->id}}')">
            <td>{{($contract->bso)?$contract->bso->bso_title:''}}</td>
                <td>{{$contract->getContractsStatusTitle()}}
                    @if($contract->statys_id == 2)
                        @if($contract->calculation && $contract->calculation->matching)
                            - {{\App\Models\Contracts\Matching::STATYS[$contract->calculation->matching->status_id]}}
                        @endif
                    @endif
                </td>
            <td>
                {{setDateTimeFormatRu($contract->begin_date, 1)}} - {{setDateTimeFormatRu($contract->end_date, 1)}}
            </td>

            <td>{{$contract->product->title}}</td>
            <td>{{($contract->insurer)?$contract->insurer->title:''}}</td>

            <td>{{titleFloatFormat($contract->insurance_amount)}}</td>
            <td>{{titleFloatFormat($contract->payment_total)}}</td>

            <td>{{$contract->agent->name}}</td>
            <td>{{$contract->agent->organization->title}}</td>

        </tr>

    @endforeach
    </tbody>
</table>
