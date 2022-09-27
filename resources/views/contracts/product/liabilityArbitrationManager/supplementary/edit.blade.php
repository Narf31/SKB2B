<form id="product_form" class="product_form" >



    <div class="form-equally col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div class="page-heading">
            <h2 class="inline-h1">Условия продления договора

                <span class="pull-right">{{\App\Models\Contracts\ContractsSupplementary::STATUS[$supplementary->status_id]}}</span>

            </h2>
        </div>

        <div class="row form-horizontal">





            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Дата заключеия <span class="required">*</span>
                        </label>
                        <input placeholder="" name="contract[sign_date]" class="form-control" value="{{setDateTimeFormatRu($supplementary->sign_date, 1)}}" readonly>
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
                        <input name="contract[begin_date]" class="form-control format-date valid_accept" id="begin_date_0" onchange="setAutoDate();" value="{{setDateTimeFormatRu($supplementary->begin_date, 1)}}">
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
                        <input onchange="controlChange()" name="contract[end_date]" class="form-control format-date end-date valid_accept" id="end_date_0" value="{{setDateTimeFormatRu($supplementary->end_date, 1)}}">
                        <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                    </div>
                </div>
            </div>

            <input type="hidden" id="type_agr_id" name="contract[liability_arbitration_manager][type_agr_id]" value="{{$contract->data->type_agr_id}}"/>
            <input type="hidden" id="insurance_amount" name="contract[liability_arbitration_manager][procedure_id]" value="{{$contract->insurance_amount}}"/>


            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">Оригинальный тариф</label>
                {{ Form::text("contract[liability_arbitration_manager][original_tariff]", titleFloatFormat($supplementary->data->original_tariff), ['class' => 'form-control', 'readonly', 'id'=>'original_tariff']) }}
            </div>


            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">Базовый тариф</label>
                {{ Form::text("contract[liability_arbitration_manager][base_tariff]", titleFloatFormat($supplementary->data->base_tariff), ['class' => 'form-control', 'readonly', 'id'=>'base_tariff']) }}
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">Желаемый тариф</label>
                {{ Form::text("contract[liability_arbitration_manager][manager_tariff]", titleFloatFormat($supplementary->data->manager_tariff), ['class' => 'form-control sum', 'id'=>'manager_tariff', 'onchange'=>'setManagerPaymentTotal()']) }}
            </div>


            <div class="clear"></div>
            <br/>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="view-field-custom">
                    <span class="view-label-custom">Страховая премия - оригинальнальная</span>
                    <span class="view-value-custom" id="original_payment_total">
                            {{titleFloatFormat($supplementary->data->original_payment_total)}}
                        </span>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="view-field-custom">
                    <span class="view-label-custom">Страховая премия - базовая</span>
                    <span class="view-value-custom" id="base_payment_total">{{titleFloatFormat($supplementary->data->base_payment_total)}}</span>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="view-field-custom">
                    <span class="view-label-custom">Страховая премия - желаемая</span>
                    <span class="view-value-custom" >
                            {{ Form::text("contract[liability_arbitration_manager][manager_payment_total]", titleFloatFormat($supplementary->data->manager_payment_total), ['class' => 'form-control sum', 'id'=>'manager_payment_total', 'onchange'=>'setManagerTariff()']) }}
                        </span>
                </div>
            </div>

        </div>

        {{--Участники комиссионного вознаграждения--}}
        @include('contracts.default.managers.liabilityArbitrationManager.edit', [
            'contract_id'=>$contract->id,
            'contract'=>$supplementary,
        ])


        <div class="form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <span class="btn btn-success btn-left" onclick="saveSupplementary(0);">Сохранить</span>
            <span class="btn btn-primary btn-right" onclick="saveSupplementary(1);">На согласование</span>
        </div>


    </div>



    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="page-heading">
        <h2 class="inline-h1">Условия договора
            <span class="pull-right">
                    {{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}

                @if($contract->statys_id == 2)
                    @if($contract->calculation && $contract->calculation->matching)
                        - {{\App\Models\Contracts\Matching::STATYS[$contract->calculation->matching->status_id]}}

                        @if($contract->calculation->matching->status_id == 2)
                            <span data-intro='Редактировать договора!' onclick="editStatusContract('{{$contract->id}}')" >
                                    <i class="fa fa-edit" style="cursor: pointer;color: green;"></i>
                                </span>
                        @endif
                    @endif
                @elseif($contract->statys_id == 4 && $contract->bso)
                    {{$contract->bso->bso_title}}
                @endif
                </span>
        </h2>
    </div>


    <div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <br/>
        <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">


            <div class="view-field">
                <span class="view-label">Дата заключеия</span>
                <span class="view-value">{{setDateTimeFormatRu($contract->sign_date)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Дата начала</span>
                <span class="view-value">{{setDateTimeFormatRu($contract->begin_date)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Дата окончания</span>
                <span class="view-value">{{setDateTimeFormatRu($contract->end_date)}}</span>
            </div>

            @if($contract->installment_algorithms)
                <div class="view-field">
                    <span class="view-label">Алгоритм рассрочки</span>
                    <span class="view-value">{{$contract->installment_algorithms->info->title}}</span>
                </div>
            @endif

            <div class="view-field">
                <span class="view-label">Тип договора</span>
                <span class="view-value">{{collect([0=>"Первичный", 1=>'Пролонгация'])[$contract->is_prolongation]}}</span>
            </div>

            @if($contract->is_prolongation == 1)

                <div class="view-field">
                    <span class="view-label">Договор пролонгации</span>
                    <span class="view-value">{{$contract->prolongation_bso_title}}</span>
                </div>

            @endif

            <div class="view-field">
                <span class="view-label">Заказчик (СРО)</span>
                <span class="view-value">{{($contract->data->cro)?$contract->data->cro->title:''}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Страхователь</span>
                <span class="view-value">{{($contract->data->general_insurer)?$contract->data->general_insurer->title:''}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Тип договора</span>
                <span class="view-value">{{\App\Models\Directories\Products\Data\LiabilityArbitrationManager::TYPE_AGR[$contract->data->type_agr_id]}}</span>
            </div>

            @if($contract->data->type_agr_id == 1)
                <div class="view-field">
                    <span class="view-label">Кол-во текущих процедур</span>
                    <span class="view-value">{{\App\Models\Directories\Products\Data\LiabilityArbitrationManager::CURRENT_PROCEDURES[$contract->data->count_current_procedures]}}</span>
                </div>

            @endif

            @if($contract->data->type_agr_id == 2)
                <div class="view-field">
                    <span class="view-label">Процедура</span>
                    <span class="view-value">{{$contract->data->procedure->title}}</span>
                </div>

            @endif


            <div class="view-field-custom">
                <span class="view-label-custom">Страховая сумма</span>
                <span class="view-value-custom">{{titleFloatFormat($contract->insurance_amount)}}</span>
            </div>




        </div>

    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-size: 15px;">
        @if(sizeof($contract->masks))
            @foreach($contract->masks as $mask)
                <a href="{{$mask->getUrlAttribute()}}" target="_blank">{{$mask->original_name}}</a> /
            @endforeach
        @endif
    </div>

    @if($contract->statys_id > 0)

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                @if($contract->statys_id == 2 && $contract->calculation && $contract->calculation->matching)
                    @include('contracts.default.payments.temp', ["contract" => $contract, 'payments' => $contract->payments])

                    @if($contract->calculation->matching->status_id == 4)
                        <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>
                    @endif
                @else
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @include('contracts.default.payments.view', ["contract" => $contract, 'payments' => $contract->payments])
                    </div>
                @endif

            </div>
        </div>


    @endif

</div>


</form>



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">


    @include('contracts.product.liabilityArbitrationManager.supplementary_js')



    <script>

        function initTerms() {

            $(".kv_sum").change(function () {
                sumBaseTarifeToKV();
            });

            formatDate();
            controlChange();



        }

        function setAutoDate() {
            controlChange();
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


        function saveSupplementary(state)
        {


            loaderShow();

            $.post('/contracts/online/{{$contract->id}}/supplementary/{{$supplementary->number_id}}/save', $('#product_form').serialize(), function (response) {
                loaderHide();


                if (Boolean(response.state) === true) {

                    if(state == 1) {

                        loaderShow();
                        openPage("/contracts/online/{{$contract->id}}/matching/supplementary/{{$supplementary->number_id}}/");

                    }else{
                        flashMessage('success', "Данные успешно сохранены!");
                    }


                }else {
                    if(response.errors){
                        $.each(response.errors, function (index, value) {
                            flashHeaderMessage(value, 'danger');
                            $('[name="' + index + '"]').addClass('form-error');
                        });
                    }else{
                        flashHeaderMessage(response.msg, 'danger');
                    }

                }

            }).always(function () {
                loaderHide();

            });

            return true;



        }


    </script>

@stop

