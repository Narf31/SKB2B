<div class="product_form">


    @if($contract->pso_order && $contract->pso_order->status_id == 0 && !isset($is_matching))

        @php
            $_view_pso = true;
            if($contract->matching_underwriter && $contract->matching_underwriter->status_id == 5) $_view_pso = false;
            if($contract->matching_sb && $contract->matching_sb->status_id == 5) $_view_pso = false;
        @endphp

        @if($_view_pso == true)
            <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @include("orders.pso.partials.send_order", ['order' => $contract->pso_order])
            </div>
        @endif
    @endif


    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Информация по договору--}}
            @include('contracts.default.info.view_contract', [
                'contract'=>$contract,
            ])
        </div>



        @if($contract->statys_id == 2)

            @if($contract->matching_underwriter)
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @include('contracts.default.matching.kasko.view', [
                        'contract'=>$contract,
                        'matching' => $contract->matching_underwriter,
                    ])
                </div>
            @endif

            @if($contract->matching_sb)

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @include('contracts.default.matching.kasko.view', [
                        'contract'=>$contract,
                        'matching' => $contract->matching_sb,
                    ])
                </div>

            @endif

        @endif



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
    </div>







    <div class="clear"></div>




    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Условия договора--}}
            @include("contracts.default.terms.kasko.{$contract->getProductOrProgram()->slug}.view", [
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


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Участники договора--}}

            @if($contract->insurer_id == $contract->beneficiar_id)
                <div class="row form-horizontal" >
                    <h2 class="inline-h1">Выгодоприобретатель - Страхователь</h2>
                    <br/><br/>
                    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <br/>
                    </div>
                </div>
            @elseif($contract->owner_id == $contract->beneficiar_id)
                <div class="row form-horizontal" >
                    <h2 class="inline-h1">Выгодоприобретатель - Собственник</h2>
                    <br/><br/>
                    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <br/>
                    </div>
                </div>
            @else
                {{--Выгодоприобретатель--}}
                @include('contracts.default.subject.view', [
                    'subject_title' => 'Выгодоприобретатель',
                    'subject_name' => 'beneficiar',
                    'subject' => (isset($contract->beneficiar)?$contract->beneficiar:new \App\Models\Contracts\Subjects())
                ])
            @endif
        </div>


    </div>



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            {{--Водители--}}
            @include('contracts.default.insured.drivers.view', [
                'insurers' => $contract->contracts_insurers
            ])
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            {{--Транспортное средство--}}
            @include('contracts.default.insurance_object.auto.kasko.view', [
                'object' => $contract->object_insurer_auto
            ])
        </div>



    </div>

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

    <script>

        @if($contract->pso_order && $contract->pso_order->status_id == 0)
            $(function () {

            activePSO();

        });
            @else
            $(function () {

                initDocument();

            });

        @endif

    </script>

@stop
