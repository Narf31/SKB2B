<div class="product_form">





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
            @include("contracts.default.terms.arbitration.{$contract->getProductOrProgram()->slug}.view", [
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

        $(function () {

            initDocument();

        });

    </script>

@stop
