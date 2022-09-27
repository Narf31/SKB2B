<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">
                    <input type="hidden" name="contract[begin_time]" value="00:00" />
                    <input type="hidden" name="contract[end_date]" value="{{$contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : Carbon\Carbon::now()->addYear()->subDay(1)->format('d.m.Y')}}" class="end-date" id="end_date_0"/>



                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
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

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Срок страхования</label>
                        {{ Form::select("contract[data][insurance_term]", \App\Models\Directories\Products\Data\Kasko\Standard::INS_YEAR, $contract->data->insurance_term, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>



                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws clear_offers', 'style'=>'width: 100%;'])}}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Договор пролонгации</label>
                        {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control clear_offers', 'readonly']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Переход из другой компании</label>
                        {{ Form::select("contract[data][is_transition]", \App\Models\Directories\Products\Data\Kasko\Standard::TRANSITION, $contract->data->is_transition, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Территория страхования</label>
                        {{ Form::select("contract[data][territory_id]", \App\Models\Directories\Products\Data\Kasko\Standard::TERRIRORY , $contract->data->territory_id, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>


                    <div class="clear"></div>





                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Лимит возмещения </label>
                        {{ Form::select("contract[data][limit_indemnity_id]", \App\Models\Directories\Products\Data\Kasko\Standard::LIMIT_INDEMNITY, $contract->data->limit_indemnity_id, ['class' => 'form-control select2-all clear_offers']) }}
                    </div>






                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Страховая сумма <span class="required">*</span></label>
                        {{ Form::text("contract[insurance_amount]", ($contract->insurance_amount > 0)? titleFloatFormat($contract->insurance_amount, 0, 1):'', ['class' => 'form-control sum valid_accept', 'onblur'=>"copyDataValIsNull('car_price', 'insurance_amount');", 'id'=>'insurance_amount']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Алгоритм рассрочки</label>
                        {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Скидка за счет КВ %</label>
                        {{ Form::text("contract[data][official_discount]", titleFloatFormat($contract->data->official_discount, 0, 1), ['class' => 'form-control sum-max-value clear_offers', 'data-sum-max-value' => 35]) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4" id="franchise">
                        <label class="control-label">Франшиза</label>
                        {{ Form::select("contract[data][franchise_id]", \App\Models\Directories\Products\Data\Kasko\Standard::FRANCHISE , $contract->data->franchise_id, ['class' => 'form-control select2-ws clear_offers', 'id'=>'franchise_id', 'onchange'=>"viewFranchiseNumber()"]) }}
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" id="franchise_number">
                        <label class="control-label">№ случая</label>
                        {{ Form::select("contract[data][franchise_number_id]", \App\Models\Directories\Products\Data\Kasko\Standard::FRANCHISE_NUMBER , $contract->data->franchise_number_id, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>





                    <div class="clear"></div>







                </div>

            </div>
        </div>
    </div>
</div>


<div >
    <div class="page-heading">
        <h2 class="inline-h1">Дополнительные условия</h2>
    </div>

    <div class="row form-horizontal" >
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="row form-horizontal">


                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">Кредитное авто</label>
                            {{ Form::select("contract[data][is_auto_credit]", \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::IS_CREDIT, $contract->data->is_auto_credit, ['class' => 'form-control select2-all clear_offers']) }}
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                            <label class="control-label">Покрытия и риски</label>
                            {{ Form::select("contract[data][coatings_risks_id]", \App\Models\Directories\Products\Data\Kasko\Standard::COATINGS_RISKS , $contract->data->coatings_risks_id, ['class' => 'form-control select2-ws clear_offers', 'onchange' => 'changeFranchise()']) }}
                        </div>



                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">Варианты ремонта</label>
                            {{ Form::select("contract[data][repair_options_id]", \App\Models\Directories\Products\Data\Kasko\Standard::REPAIR_OPTIONS , $contract->data->repair_options_id, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>




                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">Гражданская ответственность</label>
                            {{ Form::select("contract[data][civil_responsibility_sum]", collect(\App\Models\Directories\Products\Data\Kasko\Standard::CIVIL_RESPONSIBILITY), $contract->data->civil_responsibility_sum, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">GAP</label>
                            {{ Form::select("contract[data][is_gap]", collect([0=>'Нет', 1 => 'Да']), $contract->data->is_gap, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>





                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">Аварийный Комиссар</label>
                            {{ Form::select("contract[data][is_emergency_commissioner]", collect([0=>'Нет', 1 => 'Да']), $contract->data->is_emergency_commissioner, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">Эвакуация ТС при ДТП</label>
                            {{ Form::select("contract[data][is_evacuation]", collect([0=>'Нет', 1 => 'Да']), $contract->data->is_evacuation, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>






                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <label class="control-label">Сбор справок в случае необходимости</label>
                            {{ Form::select("contract[data][is_collection_certificates]", collect([0=>'Нет', 1 => 'Да']), $contract->data->is_collection_certificates, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>


                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 hidden">
                            <label class="control-label">Несчастный случай</label>
                            {{ Form::select("contract[data][ns_type]", \App\Models\Directories\Products\Data\Kasko\Standard::NS_TYPE, $contract->data->ns_type, ['class' => 'form-control select2-ws clear_offers', 'id'=>'ns_type', 'onchange'=>"viewNSData()"]) }}
                        </div>


                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 ns_data hidden">
                            <label class="control-label">Количество застрахованых мест <span class="required">*</span></label>
                            {{ Form::select("contract[data][ns_count]", \App\Models\Directories\Products\Data\Kasko\Standard::NS_COUNT, $contract->data->ns_count, ['class' => 'form-control select2-ws clear_offers']) }}
                        </div>


                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 ns_data hidden">
                            <label class="control-label">Страховая стоимость <span class="required">*</span></label>
                            {{ Form::text("contract[data][ns_sum]", ($contract->data->ns_sum > 0)? titleFloatFormat($contract->data->ns_sum, 0, 1):'', ['class' => 'form-control sum ']) }}
                        </div>





                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


<script>

    function initTerms() {

        formatTime();
        viewFranchiseNumber();
        viewNSData();

    }

    function changeFranchise() {
        if($('[name="contract[data][coatings_risks_id]"]').val() == 2){
            $("#franchise_id").select2('val', 0);
            $("#franchise_id").select2("readonly", true);
            viewFranchiseNumber();
        }else{
            $("#franchise_id").select2("readonly", false);
        }
    }

    function viewFranchiseNumber() {
        if($("#franchise_id").val() > 0){
            $("#franchise_number").show();
            $('#franchise').removeClass('col-lg-4');
            $('#franchise').addClass('col-lg-2');

        }else{
            $("#franchise_number").hide();
            $('#franchise').removeClass('col-lg-2');
            $('#franchise').addClass('col-lg-4');
        }
    }


    function viewNSData() {
        if($("#ns_type").val() > 0){
            $(".ns_data").show();
        }else{
            $(".ns_data").hide();
        }
    }


</script>