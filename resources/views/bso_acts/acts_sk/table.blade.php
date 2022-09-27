<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Организация</th>
            <th>Акты по БСО</th>
            <th>Акты по договорам</th>
        </tr>
    </thead>
    <tbody>
        @php($org_id = 0)

        @if(sizeof($suppliers))
            @foreach($suppliers as $supplier)
                @if($org_id != $supplier->purpose_org_id)
                    <tr>
                        <td colspan="4">
                            <b>{{$supplier->purpose_org ? $supplier->purpose_org->title : "" }}</b>
                        </td>
                    </tr>
                    @php($org_id = $supplier->purpose_org_id)
                @endif
                <tr class="clickable-row">
                    <td>
                        <a href="/bso_acts/acts_sk/{{ $supplier->id }}/acts" target="_blank">
                            {{$supplier->title}}
                        </a>
                        (Всего актов {{$supplier->reports_acts()->count()}})
                    </td>
                    <td class="text-center">
                        <a href="/bso_acts/acts_sk/{{ $supplier->id }}/bso">
                            Количество БСО: {{ $supplier->bso_items_to_sk_acts()->count() }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="/bso_acts/acts_sk/{{ $supplier->id }}/contracts">
                            Количество договоров: {{ $supplier->getBasePayments()->where('acts_sk_id', '<=', 0)->count() }}
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="clickable-row">
                <td colspan="3" class="text-center">Нет результатов</td>
            </tr>
        @endif
    </tbody>
</table>