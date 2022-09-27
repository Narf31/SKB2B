<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Организация</th>
            <th>Куратор</th>
            <th>Оборот</th>
            <th>Бордеро</th>
            <th>ДВОУ</th>

        </tr>
    </thead>
    <tbody>
        @php($group_id = 0)

        @if(sizeof($organizations))
            @foreach($organizations as $organization)

                <tr class="clickable-row">
                    <td>
                        <a href="{{url("/reports/reports_sk/{$organization->id}/reports/")}}" target="_blank">
                            {{ $organization->title }}
                        </a> (всего отчетов {{$organization->reports->count()}})
                    </td>
                    <td>{{$organization->curator?$organization->curator->name:''}}</td>
                    <td class="text-center">{{titleFloatFormat($organization->getPaymentsTotal())}}</td>
                    <td class="text-center"><a href="{{url("/reports/reports_sk/{$organization->id}/bordereau/")}}">{{titleFloatFormat($organization->getPaymentsTotalKV(0))}}</a></td>
                    <td class="text-center"><a href="{{url("/reports/reports_sk/{$organization->id}/dvoy/")}}">{{titleFloatFormat($organization->getPaymentsTotalKV(1))}}</a></td>
                </tr>
            @endforeach
        @else
            <tr class="clickable-row">
                <td colspan="3" class="text-center">Нет результатов</td>
            </tr>
        @endif
    </tbody>
</table>