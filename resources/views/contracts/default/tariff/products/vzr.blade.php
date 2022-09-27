@if($contract->calculation && (int)$contract->calculation->state_calc == 1)

    @php
        $result = json_decode($contract->calculation->json);
        $currency_title = $contract->data->currency->title;
    @endphp


    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Программа</th>
                <th>Тариф</th>
                <th>Страховая сумма {{ $currency_title }} / премия {{ $currency_title }}</th>
                <th>Страховая сумма RUB / премия RUB</th>
            </tr>
            </thead>
            <tbody>
            @foreach($result->info as $info)
                <tr>
                    <td>{{$info->title}}</td>
                    <td>{{$info->payment_tariff}}</td>
                    <td>{{titleFloatFormat($info->insurance_curr_amount)}} / {{titleFloatFormat($info->payment_curr_total)}}</td>
                    <td>{{titleFloatFormat($info->insurance_amount)}} / {{titleFloatFormat($info->payment_total)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field-custom">
                <span class="view-label-custom">Программа</span>
                <span class="view-value-custom">{{$contract->getProductOrProgram()->title}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field-custom">
                <span class="view-label-custom">Страховая премия</span>
                <span class="view-value-custom">{{titleFloatFormat($result->payment_curr_total)}} {{ $currency_title}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field-custom">
                <span class="view-label-custom">К оплате</span>
                <span class="view-value-custom">{{titleFloatFormat($contract->payment_total)}} RUB</span>
            </div>
        </div>

    </div>






    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <span class="btn btn-info btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/print")}}')">Печать</span>
            </div>
            <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>
        </div>
    </div>


@elseif($contract->calculation && (int)$contract->calculation->state_calc == 0)



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 style="color: red;">{{$contract->calculation->messages}}</h2>
    </div>

@endif