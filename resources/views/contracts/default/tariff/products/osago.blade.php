@if($contract->calculation && (int)$contract->calculation->state_calc == 1 && $contract->payment_total > 0)


    @php
        $result = json_decode($contract->calculation->json);

    @endphp

    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Тариф</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{(isset($result->data))?$result->data->result->TariffStr:''}}</td>
            </tr>
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
                <span class="view-value-custom">{{titleFloatFormat($contract->payment_total)}}</span>
            </div>
        </div>


    </div>


    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])

            <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>
        </div>
    </div>


@elseif($contract->calculation && (int)$contract->calculation->state_calc == 0)



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 style="color: red;">{{$contract->calculation->messages}}</h2>
    </div>

@endif