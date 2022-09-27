<form id="product_form" class="product_form" style="padding-top: 20px;">


    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="page-heading">
            <h2 class="inline-h1">Условия договора
            <span class="pull-right">{{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}</span>
            </h2>
        </div>


        <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="row form-horizontal">


                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="field form-col">
                        <div>
                            <label class="control-label">
                                Дата заключеия <span class="required">*</span>
                            </label>
                            <input placeholder="" name="contract[sign_date]" class="form-control" value="{{$contract->sign_date  ? setDateTimeFormatRu($contract->sign_date, 1) : Carbon\Carbon::now()->addYear()->subDay(1)->format('d.m.Y')}}" readonly>
                            <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="field form-col">
                        <div>
                            <label class="control-label">
                                Дата начала <span class="required">*</span>
                            </label>
                            <input name="contract[begin_date]" class="form-control format-date valid_accept" id="begin_date_0" onchange="setAutoDate();" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
                            <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="field form-col">
                        <div>
                            <label class="control-label">
                                Дата окончания <span class="required">*</span>
                            </label>
                            <input onchange="controlChange()" name="contract[end_date]" class="form-control format-date end-date valid_accept" id="end_date_0" value="{{$contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : Carbon\Carbon::now()->addYear()->subDay(1)->format('d.m.Y')}}">
                            <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                        </div>
                    </div>
                </div>

                <div class="clear"></div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Алгоритм рассрочки</label>
                    {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws']) }}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Тип договора</label>
                    {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Договор пролонгации</label>
                    {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control']) }}
                </div>



                <div class="clear"></div>


                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                    <label class="control-label">Заказчик (СРО)</label>


                    {{Form::text("contract[liability_arbitration_manager][cro_title]", ($contract->data->cro?$contract->data->cro->title:''), ['class' => 'form-control searchGeneralOrganization', 'data-set-id'=>"cro_id"]) }}
                    <input type="hidden" name="contract[liability_arbitration_manager][cro_id]" id="cro_id" value="{{$contract->data->cro_id}}"/>


                </div>

                <div class="clear"></div>


                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                    <label class="control-label" style="max-width: none;width: 100%;">Страхователь
                        <span onclick="openFancyBoxFrame('{{url("/general/subjects/create?type=0&contract_id={$contract->id}")}}')" class="pull-right" style="font-size: 16px;cursor: pointer;color: rgb(60, 178, 25);"><i class="fa fa-user" ></i></span>
                    </label>



                    {{Form::text("contract[liability_arbitration_manager][general_insurer_title]", ($contract->data->general_insurer?$contract->data->general_insurer->title:''), ['class' => 'form-control searchGeneralUser', 'data-set-id'=>"insurer_id", "onchange"=>"getProcedures()"]) }}

                    <input type="hidden" name="contract[liability_arbitration_manager][general_insurer_id]" id="insurer_id" value="{{$contract->data->general_insurer_id}}"/>

                    {{--
                    {{Form::select("contract[liability_arbitration_manager][general_insurer_id]", \App\Models\Clients\GeneralSubjects::getAllGeneralSubjects(0, auth()->user())->get()->pluck('name','id')->prepend('Не выбрано', 0), $contract->data->general_insurer_id, ['class' => 'form-control select2', 'id' => "insurer_id", "onchange"=>"getProcedures()"])}}

                    --}}
                </div>


                <div class="clear"></div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Тип договора</label>
                    {{Form::select("contract[liability_arbitration_manager][type_agr_id]", collect(\App\Models\Directories\Products\Data\LiabilityArbitrationManager::TYPE_AGR), $contract->data->type_agr_id, ['class' => 'form-control select2-ws', 'id'=>'type_agr_id', 'onchange' => 'viewTypeAgr();'])}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 view-type-agr" id="view-type-agr-1" >
                    <label class="control-label">Кол-во текущих процедур</label>
                    {{Form::select("contract[liability_arbitration_manager][count_current_procedures]", collect(\App\Models\Directories\Products\Data\LiabilityArbitrationManager::CURRENT_PROCEDURES), $contract->data->count_current_procedures, ['class' => 'form-control select2-ws', 'id'=>'count_current_procedures', 'onchange'=>'getOriginalTariff()'])}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 view-type-agr" id="view-type-agr-2" >
                    <label class="control-label">Процедура</label>
                    {{Form::select("contract[liability_arbitration_manager][procedure_id]", collect([]), $contract->data->procedure_id, ['class' => 'form-control select2-all', 'id'=>'procedure_id'])}}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Страховая сумма</label>
                    {{ Form::text("contract[insurance_amount]", titleFloatFormat($contract->insurance_amount), ['class' => 'form-control sum', 'id'=>'insurance_amount', 'onchange'=>'sumBaseTarifeToKV()']) }}
                </div>


                <div class="clear"></div>


                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Оригинальный тариф</label>
                    {{ Form::text("contract[liability_arbitration_manager][original_tariff]", titleFloatFormat($contract->data->original_tariff), ['class' => 'form-control', 'readonly', 'id'=>'original_tariff']) }}
                </div>


                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Базовый тариф</label>
                    {{ Form::text("contract[liability_arbitration_manager][base_tariff]", titleFloatFormat($contract->data->base_tariff), ['class' => 'form-control', 'readonly', 'id'=>'base_tariff']) }}
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                    <label class="control-label">Желаемый тариф</label>
                    {{ Form::text("contract[liability_arbitration_manager][manager_tariff]", titleFloatFormat($contract->data->manager_tariff), ['class' => 'form-control sum', 'id'=>'manager_tariff', 'onchange'=>'setManagerPaymentTotal()']) }}
                </div>


                <div class="clear"></div>
                <br/>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field-custom">
                        <span class="view-label-custom">Страховая премия - оригинальная</span>
                        <span class="view-value-custom" id="original_payment_total">
                            {{titleFloatFormat($contract->data->original_payment_total)}}
                        </span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field-custom">
                        <span class="view-label-custom">Страховая премия - базовая</span>
                        <span class="view-value-custom" id="base_payment_total">{{titleFloatFormat($contract->data->base_payment_total)}}</span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field-custom">
                        <span class="view-label-custom">Страховая премия - желаемая</span>
                        <span class="view-value-custom" >
                            {{ Form::text("contract[liability_arbitration_manager][manager_payment_total]", titleFloatFormat($contract->data->manager_payment_total), ['class' => 'form-control sum', 'id'=>'manager_payment_total', 'onchange'=>'setManagerTariff()']) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>


    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        {{--Участники комиссионного вознаграждения--}}
        @include('contracts.default.managers.liabilityArbitrationManager.edit', [
            'contract'=>$contract,
        ])

    </div>

    <div class="clear"></div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <span class="btn btn-success btn-left" onclick="saveContractAndCalc('{{$contract->id}}', 0);">Сохранить</span>

        <span class="btn btn-primary btn-right" onclick="saveContractAndCalc('{{$contract->id}}', 2);">На согласование</span>
    </div>



</form>




<script>

    function initTerms() {

        //formatTime();



    }

    function setAutoDate() {
        if($("#type_agr_id").val() == 1){
            setEndDates(0);
        }else{
            controlChange();
        }

    }


    function viewTypeAgr() {

        $('.view-type-agr').hide();
        $('#view-type-agr-'+$("#type_agr_id").val()).show();
        getOriginalTariff();

    }

    function initTab() {
        $(".kv_sum").change(function () {
            sumBaseTarifeToKV();
        });

        viewTypeAgr();
        formatDate();

        getProcedures();

        controlChange();

        searchGeneralOrganization();
        searchGeneralUser();

    }

    function saveTab() {

        $.post('/contracts/online/{{$contract->id}}/save', $('#product_form').serialize(), function (response) {

        }).always(function () {

        });
    }

    function getProcedures()
    {
        if($("#insurer_id").val()>0){

            procedure_id = '{{($contract->data->procedure_id>0)?$contract->data->procedure_id:0}}';

            $.getJSON("/contracts/online/{{$contract->id}}/action/product/procedures/list/"+$("#insurer_id").val(), {}, function (response) {
                var options = "";
                response.map(function (item) {

                    if(parseInt(procedure_id) == 0){
                        procedure_id = item.id;
                    }

                    options += "<option value='" + item.id + "'>" + item.title + "</option>";
                });

                $("#procedure_id").html(options).select2('val', procedure_id);
            });

            /*

             */

        }else{

            var options = "<option value='0'>Выберите cтрахователя</option>";
            $("#procedure_id").html(options).select2('val', 0);

        }

    }
    
    var begin_date_0 = '';
    var end_date_0 = '';
    function controlChange() {

        _upTarrif = false;

        if(begin_date_0 != $("#begin_date_0").val()){
            begin_date_0 = $("#begin_date_0").val();
            _upTarrif = true;
        }

        if(end_date_0 != $("#end_date_0").val()){
            end_date_0 = $("#end_date_0").val();
            _upTarrif = true;
        }

        if(_upTarrif == true){
            getOriginalTariff();
        }

    }


</script>