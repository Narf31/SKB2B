@if($contract->calculation && (int)$contract->calculation->state_calc == 1)


    @php
        $result = json_decode($contract->calculation->json);

    @endphp

    <div class="col-xs-12 col-sm-6 col-md-8 col-lg-8">

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Программа</th>
                <th>Тариф</th>
                <th>Страховая премия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($result->info as $info)
                <tr>
                    <td>{{$info->title}}</td>
                    <td>{{$info->tariff}}</td>
                    <td>{{titleFloatFormat($info->payment_total)}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3">Расшифровка тарифа {{$contract->getProductOrProgram()->title}}: {{isset($result->title_tariff) ? $result->title_tariff : ''}}</td>
            </tr>
            </tbody>
        </table>

        {{--($result->total->title)--}}

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
                <span class="view-label-custom">Страховая сумма</span>
                <span class="view-value-custom">{{titleFloatFormat($contract->insurance_amount)}}</span>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="view-field-custom">
                <span class="view-label-custom">Страховая премия
                    @if(isset(\App\Models\Directories\Products\Data\Kasko\Standard::INS_YEAR[$contract->data->insurance_term]))
                    {{\App\Models\Directories\Products\Data\Kasko\Standard::INS_YEAR[$contract->data->insurance_term]}}
                    @endif
                </span>
                <span class="view-value-custom">{{titleFloatFormat($contract->payment_total)}}</span>
            </div>
        </div>

        @if(isset($result->official_discount) && isset($result->official_discount->summ) && getFloatFormat($result->official_discount->summ) > 0)
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="view-field-custom">
                    <span class="view-label-custom">Скидка за счет КВ</span>
                    <span class="view-value-custom">{{titleFloatFormat($result->official_discount->summ)}}</span>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="view-field-custom">
                    <span class="view-label-custom">Итого к оплате</span>
                    <span class="view-value-custom">{{titleFloatFormat($contract->payment_total-$result->official_discount->summ)}}</span>
                </div>
            </div>

        @endif





    </div>

    @if(sizeof($contract->payments))

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                <span class="btn btn-info btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/print")}}')">Печать</span>

                <span class="btn btn-info btn-right" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/send-matching")}}')" >Отправить на согласование</span>

            </div>




        </div>
    </div>
    @endif


@elseif($contract->calculation && (int)$contract->calculation->state_calc == 0)



    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h2 style="color: red;">{{$contract->calculation->messages}}</h2>
    </div>

@endif
