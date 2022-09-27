<div class="view-field">
    <span class="view-label">Страховая премия</span>
    <span class="view-value">{{titleFloatFormat($contract->payment_total)}}</span>
</div>

@if($contract->calculation && (int)$contract->calculation->state_calc == 1)

    @php
        $result = json_decode($contract->calculation->json);
    @endphp

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Программа</th>
            <th>Страховая сумма</th>
            <th>Тариф</th>
            <th>Страховая премия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($result->info as $info)
            <tr>
                <td>{{$info->title}}</td>
                <td>{{titleFloatFormat($info->insurance_amount)}}</td>
                <td>{{$info->tariff}}</td>
                <td>{{titleFloatFormat($info->payment_total)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3">Расшифровка тарифа {{$contract->getProductOrProgram()->title}}: {{isset($result->title_tariff) ? $result->title_tariff : ''}}</td>
        </tr>
        </tbody>
    </table>


@endif