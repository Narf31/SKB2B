@php
    $result = $contract->data;
@endphp


<h2>
    Тарифы
</h2>
@if(isset($is_underwriter) && $is_underwriter == true)

<table class="table table-striped table-bordered" id="tariff-form">
    <thead>
    <tr>
        <th>Программа</th>
        <th>Тариф</th>
        <th>Страховая премия</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Расчетный тариф</td>
        <td>{{$result->original_tariff}}</td>
        <td>{{titleFloatFormat($result->original_payment_total)}}</td>
    </tr>
    <tr>
        <td>Желаемый тариф</td>
        <td>
            <input type="hidden" id="insurance_amount" value="{{$contract->insurance_amount}}"/>
            {{ Form::text("group[manager_tariff]", titleFloatFormat($contract->data->manager_tariff), ['class' => 'form-control sum', 'id'=>'manager_tariff', 'onchange'=>'setManagerPaymentTotal()']) }}
        </td>
        <td>
            {{ Form::text("group[manager_payment_total]", titleFloatFormat($contract->data->manager_payment_total), ['class' => 'form-control sum', 'id'=>'manager_payment_total', 'onchange'=>'setManagerTariff()']) }}
        </td>
    </tr>
    </tbody>
</table>


<div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="field form-col">
        <div>
            <label class="control-label">
                Ретроактивный период c
            </label>
            <input name="group[retroactive_period]" class="form-control datepicker date" value="{{ (strlen($contract->data->retroactive_period_data) > 0? setDateTimeFormatRu($contract->data->retroactive_period_data, 1):'') }}" id="retroactive_period">
            <span class="glyphicon glyphicon-calendar calendar-icon"></span>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="field form-col">
        <div>
            <label class="control-label">
                Ретроактивный период по
            </label>
            <input class="form-control format-date" readonly value="{{date('d.m.Y', strtotime('-1 day '.$contract->begin_date))}}">
            <span class="glyphicon glyphicon-calendar calendar-icon"></span>
        </div>
    </div>
</div>
</div>

<div class="clear"></div>




@if(auth()->user()->hasPermission('contracts', 'select_financial_policy'))
<div id="financial-policy-form">
    @if(auth()->user()->hasPermission('contracts', 'set_financial_policy_manually'))
        <label class="control-label pull-right">Указать в ручную <input type="checkbox" value="1" @if($contract->financial_policy_manually_set==1) checked @endif name="contract[financial_policy_manually_set]" id="financial_policy_manually_set" onchange="viewSetFinancialPolicyManually(this, '0')"/> </label>
    @endif
        <h2>
            Финансовая политика
        </h2>

        <div class="wraper-inline-100" id="financial_policy_id_block">
            {{ Form::select('contract[financial_policy_id]', \App\Models\Directories\FinancialPolicy::where('is_actual', 1)->where('bso_supplier_id', $contract->bso_supplier_id)->whereIn('product_id', [$contract->product_id,0])->pluck('title', 'id'), $contract->financial_policy_id, ['class' => 'form-control', 'id'=>'financial_policy_id', ($contract->financial_policy_manually_set==1) ? 'style="display: none;"' : '' ] ) }}
        </div>



    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        @if(auth()->user()->hasPermission('contracts', 'set_financial_policy_manually'))
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ></div>
            <div id="financial_policy_manually" @if($contract->financial_policy_manually_set==0) style="display: none;"@endif>
                <div class="row col-xs-4 col-sm-4 col-md-4 col-lg-3" >
                    <label class="control-label">Бордеро</label>
                    {{ Form::text('contract[financial_policy_kv_bordereau]', titleFloatFormat($contract->financial_policy_kv_bordereau, 0, 1), ['class' => 'form-control sum', 'id'=>'financial_policy_kv_bordereau']) }}
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3" >
                    <label class="control-label">ДВОУ</label>
                    {{ Form::text('contract[financial_policy_kv_dvoy]', titleFloatFormat($contract->financial_policy_kv_dvoy, 0, 1), ['class' => 'form-control sum', 'id'=>'financial_policy_kv_dvoy']) }}
                </div>
                <div class="row col-xs-4 col-sm-4 col-md-4 col-lg-3" >
                    <label class="control-label">Руков.</label>
                    {{ Form::text('contract[financial_policy_kv_parent]', titleFloatFormat($contract->financial_policy_kv_parent, 0, 1), ['class' => 'form-control sum', 'id'=>'financial_policy_kv_parent']) }}
                </div>
            </div>
        @endif
    </div>
</div>
@endif


<h2>
    Скоринг
</h2>



<div style="font-size: 18px;">
    {!! $contract->scoring_text !!}

</div>
<hr/>
<span class="btn btn-success pull-left" onclick="editTariff()">Сохранить и пересчитать</span>

<script>

    function editTariff()
    {
        loaderShow();

        $.post("/matching/underwriting/{{$matching->id}}/set-tariff", $('#tariff-form :input').serialize()+"&group[retroactive_period]="+$('#retroactive_period').val()+"&"+$('#financial-policy-form :input').serialize(), function (response) {
            loaderHide();

            if (Boolean(response.state) === true) {

                flashMessage('success', "Данные успешно сохранены!");
                reload();

            }else {
                flashHeaderMessage(response.msg, 'danger');

            }

        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });

    }

    function viewSetFinancialPolicyManually(obj, key)
    {
        if($(obj).is(':checked')){
            $("#financial_policy_manually").show();
            $("#financial_policy_id").hide();
        }else{
            $("#financial_policy_manually").hide();
            $("#financial_policy_id").show();
        }
    }

    function setManagerPaymentTotal() {
        $("#manager_payment_total").val(CommaFormatted(getSumToProcent($("#manager_tariff").val(), $("#insurance_amount").val())));
    }

    function setManagerTariff() {
        manager_payment_total = parseFloat(StringToFloat($("#manager_payment_total").val()));
        insurance_amount = parseFloat(StringToFloat($("#insurance_amount").val()));
        manager_tariff = (manager_payment_total/insurance_amount)*100;
        $("#manager_tariff").val(CommaFormatted(manager_tariff));
    }


</script>

@else

    <table class="table table-striped table-bordered" id="tariff-form">
        <thead>
        <tr>
            <th>Программа</th>
            <th>Тариф</th>
            <th>Страховая премия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Расчетный тариф</td>
            <td>{{$result->original_tariff}}</td>
            <td>{{titleFloatFormat($result->original_payment_total)}}</td>
        </tr>
        <tr>
            <td>Желаемый тариф</td>
            <td>{{$result->manager_tariff}}</td>
            <td>{{titleFloatFormat($result->manager_payment_total)}}</td>
        </tr>
        </tbody>
    </table>

@endif