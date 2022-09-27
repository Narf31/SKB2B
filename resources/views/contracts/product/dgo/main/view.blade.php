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
            {{--Участники договора--}}
            {{--Страхователь--}}
            @include('contracts.default.subject.view', [
                'subject_title' => 'Страхователь',
                'subject_name' => 'insurer',
                'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
            ])
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Участники договора--}}

            @if($contract->insurer_id == $contract->owner_id)
                <div class="row form-horizontal" >
                    <h2 class="inline-h1">Собственник - Страхователь</h2>
                    <br/><br/>
                    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <br/>
                    </div>
                </div>
            @else
                {{--Собственник--}}
                @include('contracts.default.subject.view', [
                    'subject_title' => 'Собственник',
                    'subject_name' => 'owner',
                    'subject' => (isset($contract->owner)?$contract->owner:new \App\Models\Contracts\Subjects())
                ])
            @endif
        </div>








    </div>



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">



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
                    $info = json_decode($contract->calculation->json);
                @endphp

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
                        <td>{{$contract->product->title}}</td>
                        <td>{{$info->payment_tariff}}</td>
                        <td>{{titleFloatFormat($info->insurance_amount)}} / {{titleFloatFormat($info->payment_total)}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>


        </div>


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">


                {{--Водители--}}
                @include('contracts.default.insured.drivers.view', [
                    'insurers' => $contract->contracts_insurers
                ])

            {{--Транспортное средство--}}
            @include('contracts.default.insurance_object.auto.gap.view', [
                'object' => $contract->object_insurer_auto
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
