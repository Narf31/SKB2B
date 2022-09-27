@extends('layouts.frame')


@section('title')

    Взнос # {{$payment->payment_number}} - <a href="{{url("/bso/items/{$payment->bso_id}/")}}" target="_blank" >{{$payment->bso->bso_title}}</a>

@stop

@section('content')


    {{ Form::open(['url' => url("/payment/{$payment->id}/"), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <input type="hidden" name="payment[type_id]" value="{{$payment->type_id}}"/>
    <input type="hidden" name="payment[bso_id]" value="{{$payment->bso_id}}"/>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left">Акт в страховую компанию</label><br/><br/>

            <span class="pull-left">
                @if($payment->realized_act_id > 0)
                    <a target="_blank" href="/bso_acts/acts_implemented/details/{{$payment->realized_act_id}}/">{{$payment->realized_act->act_number}}</a>
                @else
                    Не сформирован
                @endif

            </span>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left"> Отчеты Бордеро / ДВОУ</label><br/><br/>
            <span class="pull-left">
                @if($payment->reports_order_id > 0)
                    <a href="{{url("/reports/order/{$payment->reports_order_id}/")}}" target="_blank">{{$payment->reports_border->title}}</a>
                @else
                    Не сформирован
                @endif
                /
                @if($payment->reports_dvou_id > 0)
                    <a href="{{url("/reports/order/{$payment->reports_dvou_id}/")}}" target="_blank">{{$payment->reports_dvoy->title}}</a>
                @else
                    Не сформирован
                @endif

            </span>
        </div>

    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left" style="margin-top: 5px;">Заявка из фронт офиса</label>
            <input class="form-control" id="order_title_0" name="payment[order_title]" type="text" value="{{$payment->order_title}}" >
            <input type="hidden" name="payment[order_id]" id="order_id_0" value="{{$payment->order_id}}">
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <label class="control-label pull-left" style="margin-top: 5px;">
                Квитанция
                @if($payment->bso_receipt_id > 0)
                    <sup style="font-size: 85%"><a id="detach_receipt">(отвязать)</a></sup>
                @endif
            </label>
            <label class="control-label pull-right">
                Без квитанции
                <input type="checkbox" value="1" @if($payment->bso_not_receipt == 1) checked @endif name="payment[bso_not_receipt]" onchange="viewSetBsoNotReceipt(this)"/>
            </label>

            {{ Form::text("payment[bso_receipt]", $payment->bso_receipt, [
                ($payment->bso_not_receipt == 0?'':'disabled'),
                'class' => 'form-control valid_accept class_bso_receipt',
                'id'=>"bso_receipt",
            ]) }}

            <input type="hidden" name="payment[bso_receipt_id]" id="bso_receipt_id" value="{{$payment->bso_receipt_id}}"/>
        </div>

    </div>


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left" style="margin-top: 5px;">Агент</label><br/><br/>
            {{ Form::select('payment[agent_id]', $agents->prepend('Выберите значение', 0), $payment->agent_id, ['class' => 'form-control select2']) }}
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left" style="margin-top: 5px;">Менеджер</label><br/><br/>
            {{ Form::select('payment[manager_id]', $agents->prepend('Выберите значение', 0), $payment->manager_id, ['class' => 'form-control select2', 'id' => 'manager_id']) }}
        </div>

    </div>


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left" style="margin-top: 5px;">Руководитель</label><br/><br/>
            {{ Form::select('payment[parent_agent_id]', $agents->prepend('Выберите значение', 0), $payment->parent_agent_id, ['class' => 'form-control select2']) }}
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
            <label class="control-label pull-left" style="margin-top: 5px;">Точка продажи / Отдел</label><br/><br/>
            {{ Form::select('payment[point_sale_id]', \App\Models\Settings\PointsSale::all()->pluck('title', 'id'), $payment->point_sale_id, ['class' => 'form-control select2-all']) }}
        </div>

    </div>



    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Дата оплаты</label>
            {{ Form::text("payment[payment_data]", setDateTimeFormatRu($payment->payment_data, 1), ['class' => 'form-control datepicker date']) }}
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Номер</label>
            {{ Form::text("payment[payment_number]", $payment->payment_number, ['class' => 'form-control sum']) }}
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Тип оплаты</label>
            {{ Form::select("payment[payment_type]", collect(\App\Models\Contracts\Payments::PAYMENT_TYPE), $payment->payment_type, ['class' => 'form-control']) }}

        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Поток оплаты</label>
            {{ Form::select("payment[payment_flow]", collect(\App\Models\Contracts\Payments::PAYMENT_FLOW), $payment->payment_flow, ['class' => 'form-control']) }}
        </div>
    </div>







    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >
        <label class="col-sm-12 control-label pull-left" style="margin-top: 5px;">
            Финансовая политика
        </label>
        <label class="control-label pull-right" style="margin-right: 15px;">Указать в ручную <input type="checkbox" value="1" @if($payment->financial_policy_manually_set==1) checked @endif name="payment[financial_policy_manually_set]" id="financial_policy_manually_set_0" onchange="viewSetFinancialPolicyManually(this, '0')"/> </label>
        <div class="col-sm-12">
        {{ Form::select('payment[financial_policy_id]', \App\Models\Directories\FinancialPolicy::where('insurance_companies_id', $payment->bso->insurance_companies_id)
            ->where('bso_supplier_id', $payment->bso->bso_supplier_id)
            ->where('product_id', $payment->bso->product_id)
            ->orderBy('title')->get()->pluck('title', 'id'), $payment->financial_policy_id, ['class' => 'form-control', 'id'=>'financial_policy_id_0']) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ></div>
        <div id="financial_policy_manually_0" style="display: none;">

            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                {{ Form::text('payment[financial_policy_kv_bordereau]', $payment->financial_policy_kv_bordereau, ['class' => 'form-control sum', 'id'=>'financial_policy_kv_bordereau_0', 'placeholder'=>'Бордеро']) }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                {{ Form::text('payment[financial_policy_kv_dvoy]', $payment->financial_policy_kv_dvoy, ['class' => 'form-control sum', 'id'=>'financial_policy_kv_dvoy_0', 'placeholder'=>'ДВОУ']) }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                {{ Form::text('payment[financial_policy_kv_agent]', $payment->financial_policy_kv_agent, ['class' => 'form-control sum', 'id'=>'financial_policy_kv_agent_0', 'placeholder'=>'Агента']) }}
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
                {{ Form::text('payment[financial_policy_kv_parent]', $payment->financial_policy_kv_parent, ['class' => 'form-control sum', 'id'=>'financial_policy_kv_parent_0', 'placeholder'=>'Руков.']) }}
            </div>
        </div>

    </div>



    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" >

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Сумма</label>
            {{ Form::text("payment[payment_total]", ($payment->payment_total!=0.00)?titleFloatFormat($payment->payment_total):'', ['class' => 'form-control sum']) }}
        </div>

        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Офици. %</label>
            {{ Form::text("payment[official_discount]", ($payment->official_discount!=0.00)?titleFloatFormat($payment->official_discount):'', ['class' => 'form-control sum']) }}
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Неофиц. %</label>
            {{ Form::text("payment[informal_discount]", ($payment->informal_discount!=0.00)?titleFloatFormat($payment->informal_discount):'', ['class' => 'form-control sum']) }}
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" >
            <label class="control-label pull-left">Банк %</label>
            {{ Form::text("payment[bank_kv]", ($payment->bank_kv!=0.00)?titleFloatFormat($payment->bank_kv):'', ['class' => 'form-control sum']) }}
        </div>
    </div>




    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-12 control-label">Общий комменатрий</label>
        <div class="col-sm-12">
            {{ Form::textarea("payment[comments]", $payment->comments, ['class' => 'form-control']) }}
        </div>
    </div>

    {{Form::close()}}


@stop

@section('footer')


    @include("payments.payment.buttons", ["payment" => $payment])



@stop

@section('js')

    <script>


        $(function () {


            @if($payment->financial_policy_manually_set==1)
                viewSetFinancialPolicyManually($("#financial_policy_manually_set_0"), 0);
            @endif


            activSearchOrdersToFront("order_title", "_0", function (object_id, key, suggestion) {
                var data = suggestion.data;
                if (parseInt(data.id) > 0) {
                    $('#' + object_id + key).val(data.title).change();
                    $('#order_id' + key).val(data.id).change();
                    loaderShow();
                    $.get("/bso/actions/get_order_id_front/", {order_id: data.id}, function (response) {
                        if (response) {
                            $('#manager_id').val(response.manager_id).change();
                        }
                    });
                }
            });

            if(parseInt($('#bso_receipt_id').val()) === 0){
                init_receipt_suggestions()
            }

            $(document).on('click', '#detach_receipt', function(){
                if(confirm("Вы действительно хотите отвязать квитанцию от данного платежа?")){
                    $.post('/payment/{{$payment->id}}/detach_receipt', {}, function(res){
                        if(res.status === "ok"){
                            location.reload();
                        }
                    });
                }
            })

        });

        function init_receipt_suggestions(){
            $('#bso_receipt').suggestions({
                serviceUrl: "/bso/actions/get_bso/",
                type: "PARTY",
                params: {
                    bso_agent_id: parseInt('{{$payment->agent_id}}'),
                    bso_supplier_id: parseInt('{{$payment->bso->bso_supplier_id}}'),
                    type_bso: 2,
                    query: $('#bso_receipt_id').val()
                },
                count: 5,
                minChars: 3,
                formatResult: function (e, t, n, i) {
                    var s = this;
                    var title = n.value;
                    var bso_type = n.data.bso_type;
                    var bso_sk = n.data.bso_sk;
                    var agent_name = n.data.agent_name;
                    var view_res = title;
                    view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">СК</span>' + bso_sk + "</div>";
                    view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">Тип</span>' + bso_type + "</div>";
                    view_res += '<div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">Агент</span>' + agent_name + "</div>";
                    return view_res;
                },
                onSelect: function (suggestion) {
                    $('#bso_receipt_id').val(suggestion.data.bso_id)
                }
            });
        }


        function viewSetFinancialPolicyManually(obj, key)
        {
            if($(obj).is(':checked')){
                $("#financial_policy_manually_"+key).show();
                $("#financial_policy_id_"+key).hide();
            }else{
                $("#financial_policy_manually_"+key).hide();
                $("#financial_policy_id_"+key).show();
            }
        }


        function viewSetBsoNotReceipt(obj)
        {
            if($(obj).is(':checked')){
                $("#bso_receipt").attr('disabled','disabled');
                $("#bso_receipt").removeClass('valid_fast_accept');

            }else{
                $("#bso_receipt").removeAttr('disabled');
                $("#bso_receipt").addClass('valid_fast_accept');
                init_receipt_suggestions();
            }

            $("#bso_receipt").css("border-color","");
        }




    </script>
@stop