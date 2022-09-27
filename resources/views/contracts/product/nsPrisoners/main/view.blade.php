<div class="product_form">




    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Информация по договору--}}
            @include('contracts.default.info.view_contract', [
                'contract'=>$contract,
            ])
        </div>



        @if($contract->statys_id >= 2)

            @if($contract->matching_underwriter)
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @include('contracts.default.matching.kasko.view', [
                        'contract'=>$contract,
                        'matching' => $contract->matching_underwriter,
                    ])
                </div>
            @endif


        @endif


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Условия договора--}}
            @include('contracts.default.terms.default.view', [
                'contract'=>$contract,
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

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('contracts.default.payments.view', ["contract" => $contract, 'payments' => $contract->payments])
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
            </div>

        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Документы--}}
            @include('contracts.default.documentation.view', [
                'contract'=>$contract,
            ])
        </div>



        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            {{--Застрахованный--}}
            @include('contracts.default.insured.nsPrisoners.view', [
                'insurer' => ((isset($contract->contracts_insurers) && isset($contract->contracts_insurers[0]))?$contract->contracts_insurers[0]:null)
            ])
        </div>


    </div>







    <div class="clear"></div>



    @if(isset($view_damages) && $view_damages == 1)

        @include("orders.damages.info", ['damages' => $contract->damages])

    @endif

    @if($contract->pso_order && $contract->pso_order->status_id > 0)
        @include("orders.pso.info", ['order' => $contract->pso_order])
    @endif

</div>








@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">


@stop
