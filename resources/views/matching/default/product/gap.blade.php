@php
    $info = json_decode($contract->calculation->json);
@endphp



@if(isset($is_underwriter) && $is_underwriter == true)
    <h2 xmlns="http://www.w3.org/1999/html">
        Тарифы
    </h2>
<table class="table table-striped table-bordered" id="tariff-form">
    <thead>
    <tr>
        <th>Программа</th>
        <th>Лимит возмещения</th>
        <th>Тариф</th>
        <th>Страховая премия</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$info->title}}</td>
            <td>
                {{ Form::text("group[max_amount]", titleFloatFormat($info->max_amount, 0, 1), ['class' => 'form-control sum']) }}
            </td>
            <td>
                {{ Form::text("group[payment_tariff]", titleFloatFormat($info->payment_tariff, 0, 1), ['class' => 'form-control sum']) }}
            </td>
            <td>{{titleFloatFormat($info->payment_total)}}</td>
        </tr>
    </tbody>
</table>

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

<hr/>
<span class="btn btn-success pull-left" onclick="editTariff()">Сохранить и пересчитать</span>

<script>

    function editTariff()
    {
        loaderShow();

        $.post("/matching/underwriting/{{$matching->id}}/set-tariff", $('#tariff-form :input').serialize()+"&"+$('#financial-policy-form :input').serialize(), function (response) {
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

    function refreshScoring() {
        loaderShow();

        $.get("/matching/underwriting/{{$matching->id}}/refresh-scoring", function (response) {
            loaderHide();
            reload();

        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });
    }

</script>

@else

    <label class="control-label" style="width: 100%;max-width: none;">
        Тарифы
    </label>



    <table class="table table-striped table-bordered" id="tariff-form">
        <thead>
        <tr>
            <th>Программа</th>
            <th>Лимит возмещения</th>
            <th>Тариф</th>
            <th>Страховая премия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{$info->title}}</td>
            <td>
                {{ titleFloatFormat($info->max_amount, 0, 1) }}
            </td>
            <td>
                {{ titleFloatFormat($info->payment_tariff, 0, 1) }}
            </td>
            <td>{{titleFloatFormat($info->payment_total)}}</td>
        </tr>
        </tbody>
    </table>



@endif