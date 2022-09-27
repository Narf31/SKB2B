<div class="product_form">



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Информация по договору--}}
            @include('contracts.default.info.view_contract', [
                'contract'=>$contract,
            ])

            <br/>
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('contracts.default.payments.view', ["contract" => $contract, 'payments' => $contract->payments])
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Документы--}}
            @include('contracts.default.documentation.view', [
                'contract'=>$contract,
            ])
        </div>


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="row form-horizontal">
                <h2 class="inline-h1">Программы</h2>
                <br/><br/>


                @php
                    $result = json_decode($contract->calculation->json);
                @endphp

                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Программа</th>
                        <th>Тариф</th>
                        <th>Страховая сумма {{ $contract->data->currency->title }} / премия {{ $contract->data->currency->title }}</th>
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

        </div>








    </div>



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Условия договора--}}
            @include('contracts.default.terms.vzr.view', [
                'contract'=>$contract,
            ])
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            {{--Застрахованный--}}
            @include('contracts.default.insured.vzr.view', [
                'insurers' => $contract->contracts_insurers
            ])
        </div>


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Участники договора--}}
            {{--Страхователь--}}
            @include('contracts.default.subject.view', [
                'subject_title' => 'Страхователь',
                'subject_name' => 'insurer',
                'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
            ])
        </div>
    </div>

    @if(isset($view_damages) && $view_damages == 1)

        @include("orders.damages.info", ['damages' => $contract->damages])

    @endif

</div>


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">


@stop
