@if($contract->calculation && (int)$contract->calculation->state_calc == 1)

    @php
        $info = json_decode($contract->calculation->json);
    @endphp


    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Программа</th>
                <th>Тариф</th>
                <th>Страховая сумма / премия</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$info->title}}</td>
                <td>{{$info->payment_tariff}}</td>
                <td>{{titleFloatFormat($info->insurance_amount)}} / {{titleFloatFormat($info->payment_total)}}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field-custom">
                <span class="view-label-custom">Программа</span>
                <span class="view-value-custom">{{\App\Models\Directories\Products\Data\GAP\Gap::OPTION[$contract->data->insurance_option]}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field-custom">
                <span class="view-label-custom">Страховая премия</span>
                <span class="view-value-custom">{{titleFloatFormat($info->payment_total)}}</span>
            </div>
        </div>


    </div>






    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
            <span class="btn btn-info btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/print")}}')">Печать</span>

            <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>

            <span class="btn btn-info btn-right" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/send-matching")}}')" >Отправить на согласование</span>
            </div>

        </div>
    </div>


@elseif($contract->calculation && (int)$contract->calculation->state_calc == 0)



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 style="color: red;">{{$contract->calculation->messages}}</h2>
    </div>

@endif