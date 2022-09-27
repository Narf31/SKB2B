<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-equally col-xs-12 col-sm-6 col-md-6 col-lg-6">

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Время <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_time]" class="form-control valid_accept format-time" value="{{$contract->begin_date  ? getDateFormatTimeRu($contract->begin_date) : '00:00'}}">
                                <span class="glyphicon glyphicon-time calendar-icon"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date-today valid_accept" id="begin_date_0" onchange="setEndDates(0)" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата окончания <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[end_date]" readonly class="form-control end-date valid_accept" id="end_date_0" value="{{$contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : Carbon\Carbon::now()->addYear()->subDay(1)->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <input type="hidden" name="contract[installment_algorithms_id]" value="{{$contract->getAlgorithms()->first()->id}}"/>


                    <input type="hidden" name="contract[data][tenure_id]" value=""/>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3" >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws clear_offers', 'style'=>'width: 100%;'])}}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <label class="control-label">Договор пролонгации</label>
                        {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control clear_offers']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <label class="control-label">Переход из другой компании</label>
                        {{ Form::select("contract[data][is_transition]", \App\Models\Directories\Products\Data\Kasko\Drive::TRANSITION, $contract->data->is_transition, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>

                    <div class="clear"></div>



                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">Автомобиль куплен в кредит</label>
                                {{ Form::select("contract[data][bank_id]", \App\Models\Settings\Bank::where('is_actual', 1)->get()->pluck('title', 'id')->prepend('Нет', 0), $contract->data->bank_id, ['class' => 'form-control select2-all clear_offers']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <label class="control-label">Скидка за счет КВ %</label>
                        {{ Form::text("contract[data][official_discount]", titleFloatFormat($contract->data->official_discount, 0, 1), ['class' => 'form-control sum-max-value clear_offers', 'data-sum-max-value' => 25]) }}
                    </div>



                    <div class="clear"></div>

                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                    @php

                        $spec = \App\Models\Directories\Products\ProductsSpecialSsettings::where('product_id', $contract->product->id)->where('program_id', $contract->program->id)->get()->first();
                        $spec_info = null;
                        if($spec && $spec->json && strlen($spec->json) > 0) $spec_info = json_decode($spec->json);

                    @endphp

                    <div class="view-field">
                        <span class="view-label">Максимальная страховая сумма</span>
                        <span class="view-value">{{($spec_info)?$spec_info->terms->insurance_amount:''}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Покрытия и риски</span>
                        <span class="view-value">{{\App\Models\Directories\Products\Data\Kasko\Drive::COATINGS_RISKS[($spec_info)?$spec_info->terms->coatings_risks_id:1]}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Территория страхования</span>
                        <span class="view-value">{{\App\Models\Directories\Products\Data\Kasko\Drive::TERRIRORY[($spec_info)?$spec_info->terms->territory_id:1]}}</span>
                    </div>

                    <div class="view-field">
                        <span class="view-label">Варианты ремонта</span>
                        <span class="view-value">{{\App\Models\Directories\Products\Data\Kasko\Drive::REPAIR_OPTIONS[($spec_info)?$spec_info->terms->repair_options_id:1]}}</span>
                    </div>


                    <br/>

                    <h3>{{($contract->program->description)}}</h3>

                </div>
            </div>




        </div>
    </div>
</div>



<script>

    function initTerms() {

        formatTime();
        viewFranchiseNumber();


    }


    function viewFranchiseNumber() {
        if($("#franchise_id").val() > 0){
            $("#franchise_number").show();
            $('#franchise').removeClass('col-lg-3');
            $('#franchise').addClass('col-lg-1');

        }else{
            $("#franchise_number").hide();
            $('#franchise').removeClass('col-lg-1');
            $('#franchise').addClass('col-lg-3');
        }
    }


</script>