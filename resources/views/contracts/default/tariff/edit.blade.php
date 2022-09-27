@if($contract->calculation && (int)$contract->calculation->state_calc == 1)


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
            <span class="view-label-custom">Страховая премия</span>
            <span class="view-value-custom">{{titleFloatFormat($contract->payment_total)}}</span>
        </div>
    </div>





    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])
            <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>
        </div>
    </div>




@endif