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


    </div>



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--Условия договора--}}
            @include('contracts.default.terms.flats.view', [
                'contract'=>$contract,
                'terms' => ($contract->calculation && strlen($contract->calculation->risks) > 0)?json_decode($contract->calculation->risks):null,
            ])
        </div>

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            {{--Условия договора--}}
            @include('contracts.default.insurance_object.realty.flats.view', [
                'contract'=>$contract,
                'object'=>(isset($contract->object_insurer_flats))?$contract->object_insurer_flats:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerFlats(),
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
