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



                    @if(auth()->user()->hasPermission('role', 'is_curator'))
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">Агент</label>
                            {{ Form::select("contract[set_agent_id]", \App\Models\User::getALLUserWhere()->get()->pluck('name', 'id'), $contract->agent_id, ['class' => 'form-control select2-all clear_offers']) }}
                        </div>
                        <div class="clear"></div>
                    @endif


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
                        {{ Form::select("contract[data][insurance_term]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::INS_YEAR, $contract->data->insurance_term, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>

                    {{-- Алгоритм рассрочки --}}
                    <input type="hidden" name="contract[installment_algorithms_id]" value="{{ $contract->getAlgorithms()->first()->id }}" />


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Скидка за счет КВ %</label>
                        {{ Form::text("contract[data][official_discount]", titleFloatFormat($contract->data->official_discount, 0, 1), ['class' => 'form-control sum-max-value clear_offers', 'data-sum-max-value' => 25]) }}
                    </div>



                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Переход из другой компании</label>
                        {{ Form::select("contract[data][is_transition]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::TRANSITION, $contract->data->is_transition, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2"  >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws clear_offers disabled ', 'style'=>'width: 100%;', ((int)$contract->prolongation_bso_id > 0)?'disabled':''])}}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Договор пролонгации</label>
                        {{ Form::text("contract[prolongation_bso_title]",
                        $contract->prolongation_bso_title,
                        ['class' => 'form-control clear_offers', 'id'=>'search-contract', ((int)$contract->prolongation_bso_id > 0)?'readonly':'']) }}
                    </div>


                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Банк <span class="required">*</span></label>
                        {{ Form::select("contract[data][bank_id]", \App\Models\Clients\GeneralSubjects::where('person_category_id', '10')->where('status_work_id', '0')->orderBy("title")->get()->pluck('title', 'id') , $contract->data->bank_id, ['class' => 'form-control select2-ws']) }}
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Тип недвижимости</label>
                        {{ Form::select("contract[data][type_realty]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_REALTY, $contract->data->type_realty, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Категория недвижимости</label>
                        {{ Form::select("contract[data][class_realty]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::CLASS_REALTY, $contract->data->class_realty, ['class' => 'form-control select2-ws clear_offers', 'onchange'=>'setValidObjectAddress()', 'id' => 'class_realty']) }}
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Сумма кредита/ОСЗ <span class="required">*</span></label>
                        {{ Form::text("contract[insurance_amount]", ($contract->insurance_amount > 0)? titleFloatFormat($contract->insurance_amount, 0, 1):'', ['class' => 'form-control sum valid_accept', 'onblur'=>"copyDataValIsNull('car_price', 'insurance_amount');", 'id'=>'insurance_amount']) }}
                    </div>



                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Срок кредита мес. <span class="required">*</span></label>
                        {{ Form::text("contract[data][credit_term]", titleFloatFormat($contract->data->credit_term, 0, 1), ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Ставка кредита <span class="required">*</span></label>
                        {{ Form::text("contract[data][loan_rate]", titleFloatFormat($contract->data->loan_rate, 0, 1), ['class' => 'form-control sum valid_accept']) }}
                    </div>



                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label">Адрес объекта страхования <span class="required">*</span></label>
                        {{ Form::text("contract[object][address]", $contract->data->address, ['class' => 'form-control valid_accept', 'id' => "object_address"]) }}


                        <input name="contract[object][address_kladr]" value="{{$contract->data->address_kladr}}" type="hidden"/>
                        <input name="contract[object][address_fias]" value="{{$contract->data->address_fias}}" type="hidden"/>
                        <input name="contract[object][address_region]" value="{{$contract->data->address_region}}" type="hidden"/>
                        <input name="contract[object][address_city]" value="{{$contract->data->address_city}}" type="hidden"/>
                        <input name="contract[object][address_city_kladr_id]" value="{{$contract->data->address_city_kladr_id}}" type="hidden"/>
                        <input name="contract[object][address_street]" value="{{$contract->data->address_street}}" type="hidden"/>
                        <input name="contract[object][address_house]" value="{{$contract->data->address_house}}" type="hidden" class="valid_accept" data-parent='object_address'/>


                        <input name="contract[object][address_block]" value="{{$contract->data->address_block}}" type="hidden"/>

                        <input name="contract[object][address_flat]" value="{{$contract->data->address_flat}}" type="hidden" id="object_address_flat"/>

                        <input name="contract[object][address_latitude]" value="{{$contract->data->address_latitude}}" type="hidden"/>
                        <input name="contract[object][address_longitude]" value="{{$contract->data->address_longitude}}" type="hidden"/>


                    </div>



                    <div class="clear"></div>





                </div>

            </div>
        </div>
    </div>
</div>




<div class="form-equally row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row form-horizontal">



        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
            <h2 class="inline-h1" >Риски</h2>

            <div class="clear" style="padding-top: 30px;"></div>

            <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label" style="color: #000000;font-size: 173%;">Жизнь</label>
                <div class="pull-right">
                    <input @if((int)$contract->data->is_life == 1)) checked="checked" @endif class="easyui-switchbutton is_life clear_offers" id="risk-life" data-options="onText:'Да',offText:'Нет'" name="contract[data][is_life]" value="1" type="checkbox">
                </div>



            </div>

            <div class="clear" style="padding-top: 8px;"></div>
            <div class="divider"></div>
            <br/>

            <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label" style="color: #000000;font-size: 173%;">Имущество</label>
                <div class="pull-right">
                    <input @if((int)$contract->data->is_property == 1)) checked="checked" @endif class="easyui-switchbutton is_property clear_offers" id="risk-property" data-options="onText:'Да',offText:'Нет'" name="contract[data][is_property]" value="1" type="checkbox">
                </div>

            </div>

            <div class="clear" style="padding-top: 11px;"></div>
            <div class="divider"></div>
            <br/>

            <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label class="control-label" style="color: #000000;font-size: 173%;">Титул</label>
                <div class="pull-right">
                    <input @if((int)$contract->data->is_title == 1)) checked="checked" @endif class="easyui-switchbutton is_title clear_offers" id="risk-title" data-options="onText:'Да',offText:'Нет'" name="contract[data][is_title]" value="1" type="checkbox">
                </div>

            </div>

            <div class="clear" style="padding-top: 3px;"></div>
            <div class="divider"></div>
            <br/>

        </div>





        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 risk-form-life">
            <h2 class="inline-h1" >Жизнь</h2>


            <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding-top: 10px">


                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="control-label">Профессия</label>
                    {{ Form::text("contract[data][profession]", $contract->data->profession, ['class' => 'form-control']) }}
                </div>



                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="control-label">Отклонение по здоровью</label>
                    <div class="clear"></div>
                    <div class="form-equally col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        {{ Form::select("contract[data][type_health_deviation]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_HEALTH_DEVIATION, $contract->data->type_health_deviation, ['class' => 'form-control select2-ws', 'id' => 'type_health_deviation']) }}
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 type_health_deviation" style="padding-right: 0px;">
                        {{ Form::text("contract[data][health_deviation]", $contract->data->health_deviation, ['class' => 'form-control', 'id'=>'health_deviation', 'placeholder' => 'Описание']) }}
                    </div>

                </div>

                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: -3px;">
                    <label class="control-label">Спорт</label>

                    <div class="clear"></div>
                    <div class="form-equally col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        {{ Form::select("contract[data][type_sport]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_SPORT, $contract->data->type_sport, ['class' => 'form-control select2-ws', 'id' => 'type_sport']) }}
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 type_sport" style="padding-right: 0px;">
                        {{ Form::text("contract[data][sport]", $contract->data->sport, ['class' => 'form-control', 'id'=>'sport', 'placeholder' => 'Описание']) }}
                    </div>

                </div>



            </div>



        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 risk-form-property">
            <h2 class="inline-h1" >Имущество</h2>

            <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12 " style="padding-top: 10px">


                <div class=" col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px">
                    <label class="control-label">Площадь</label>
                    {{ Form::text("contract[data][area]", titleFloatFormat($contract->data->area, 0, 1), ['class' => 'form-control sum']) }}
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px">
                    <label class="control-label">Год постройки</label>
                    {{ Form::text("contract[data][year_construction]", $contract->data->year_construction, ['class' => 'form-control ']) }}
                </div>

                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_combustible_material]",  1, $contract->data->is_combustible_material, ['class' => '']) }} Материал стен или перекрытий из горючих материалов?</label>

                </div>

                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_availability_repair]",  1, $contract->data->is_availability_repair, ['class' => '']) }} Наличие кап.ремонта/перепланировки? </label>

                </div>

                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_repair_work_progress]",  1, $contract->data->is_repair_work_progress, ['class' => '']) }} Проводятся ремонтные работы? </label>


                </div>




            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 risk-form-title">

            <h2 class="inline-h1" >Титул</h2>



            <div class="row form-equally col-xs-12 col-sm-5 col-md-5 col-lg-5 " style="padding-top: 10px">

                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px">
                    <label class="control-label">Документ, подтверждающий право собственности</label>
                    {{ Form::select("contract[data][document_owner]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::DOCUMENT_OWNER,$contract->data->document_owner, ['class' => 'form-control select2-ws']) }}
                </div>


                <div class="row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px">
                    <label class="control-label">Ограничение права собственности</label>
                    <div class="clear"></div>
                    <div class="form-equally col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        {{ Form::select("contract[data][type_ownership_restriction]", \App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_SPORT, $contract->data->type_ownership_restriction, ['class' => 'form-control select2-ws', 'id' => 'type_ownership_restriction']) }}
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 type_ownership_restriction" style="padding-right: 0px;">
                        {{ Form::text("contract[data][ownership_restriction]", $contract->data->ownership_restriction, ['class' => 'form-control', 'id'=>'ownership_restriction', 'placeholder' => 'Описание']) }}
                    </div>

                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-left: 0px">
                    <label class="control-label">Рыночная стоимость</label>
                    {{ Form::text("contract[data][price]", titleFloatFormat($contract->data->price, 0 ,1), ['class' => 'form-control sum']) }}
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px">
                    <label class="control-label">Срок по титулу (мес.)</label>
                    {{ Form::text("contract[data][title_period]", titleNumberFormat($contract->data->title_period, 0 ,1), ['class' => 'form-control sum']) }}
                </div>


            </div>


            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 risk-form-title" style="padding-top: 10px;padding-right: 0px;padding-left: 25px;">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px;">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_deal_proxy]", 1, $contract->data->is_deal_proxy, ['class' => '']) }} Сделка проводится по доверенности? </label>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px;">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_owners_age]", 1, $contract->data->is_owners_age, ['class' => '']) }} Среди собственников есть младше 18 и старше 65 лет? </label>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px;">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_object_owner_age]", 1, $contract->data->is_object_owner_age, ['class' => '']) }} Объект в собственности менее 3 лет?</label>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px;">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_owner_ul]", 1, $contract->data->is_owner_ul, ['class' => '']) }} Среди собственников есть юридические лица? </label>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px;">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_owner_payment]", 1, $contract->data->is_owner_payment, ['class' => '']) }} Единовременная оплата ? </label>

                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0px;">
                    <label class="control-label">{{ Form::checkbox("contract[data][is_not_agreement]", 1, $contract->data->is_not_agreement, ['class' => '']) }} Отсутствует согласие супруга / супруги?</label>

                </div>


            </div>

        </div>

        <div class="clear"></div>

    </div>

</div>



<script>

    function initTerms() {

        formatTime();
        setValidObjectAddress();

        @if((int)$contract->prolongation_bso_id > 0)
        @else


            $('#search-contract').suggestions({
                serviceUrl: "/suggestions/dadata/prolongation",
                token: "",
                type: "PARTY",
                count: 5,
                params:{product:'{{$contract->product_id}}'},
                formatResult: function(e, t, n, i) {
                    var s = this;
                    e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);
                    return e;
                },

                onSelect: function (suggestion) {
                    return prolongationToContract(suggestion.id, '{{$contract->id}}');
                }
            });

        @endif


        $('#object_address').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {


                $('[name="contract[object][address]"]').val($(this).val());
                $('[name="contract[object][address_kladr]"]').val(suggestion.data.kladr_id);
                $('[name="contract[object][address_fias]"]').val(suggestion.data.fias_id);
                $('[name="contract[object][address_region]"]').val(suggestion.data.region);
                $('[name="contract[object][address_city]"]').val(suggestion.data.city);
                $('[name="contract[object][address_city_kladr_id]"]').val(suggestion.data.city_kladr_id);
                $('[name="contract[object][address_street]"]').val(suggestion.data.street_with_type);
                $('[name="contract[object][address_house]"]').val(suggestion.data.house);
                $('[name="contract[object][address_block]"]').val(suggestion.data.block);
                $('[name="contract[object][address_flat]"]').val(suggestion.data.flat);

                $('[name="contract[object][address_latitude]"]').val(suggestion.data.geo_lat);
                $('[name="contract[object][address_longitude]"]').val(suggestion.data.geo_lon);

            }
        });



        $('#risk-life').switchbutton({
            onChange: function(checked){
                viewFormRisk('life');
            }
        });

        $('#risk-property').switchbutton({
            onChange: function(checked){
                viewFormRisk('property');
            }
        });

        $('#risk-title').switchbutton({
            onChange: function(checked){
                viewFormRisk('title');
            }
        });

        viewFormRisk('life');
        viewFormRisk('property');
        viewFormRisk('title');


        viewIsFormDataParam('health_deviation');
        viewIsFormDataParam('sport');
        viewIsFormDataParam('ownership_restriction');


        $('#type_health_deviation').change(function () {
            viewIsFormDataParam('health_deviation');
        });
        $('#type_sport').change(function () {
            viewIsFormDataParam('sport');
        });

        $('#type_ownership_restriction').change(function () {
            viewIsFormDataParam('ownership_restriction');
        });

    }


    function viewFormRisk(risk_name) {
        if($('#risk-'+risk_name).prop('checked')){
            $('.risk-form-'+risk_name).show();
        }else{
            $('.risk-form-'+risk_name).hide();
        }
    }

    function viewIsFormDataParam(select_id) {

        if($('#type_'+select_id).val() == 0){
            $('#'+select_id).removeClass('valid_accept');
            $('#'+select_id).val('');
            $('#'+select_id).prop('disabled', true);
        }else{
            $('#'+select_id).addClass('valid_accept');
            $('#'+select_id).prop('disabled', false);
        }
    }

    
    function setValidObjectAddress() {
        if($('#class_realty').val() == 0){
            $('#object_address_flat').addClass('valid_accept');
        }else{
            $('#object_address_flat').removeClass('valid_accept');
        }
    }



</script>