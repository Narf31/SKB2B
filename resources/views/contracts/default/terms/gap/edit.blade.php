
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row page-heading">
            <h2 class="inline-h1">Условия договора</h2>
        </div>

        <div class="row form-horizontal" >
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
                        <label class="control-label">Страховая сумма <span class="required">*</span></label>
                        {{ Form::text("contract[insurance_amount]", ($contract->insurance_amount > 0)? titleFloatFormat($contract->insurance_amount, 0, 1):'', ['class' => 'form-control sum valid_accept', 'onblur'=>"copyDataValIsNull('car_price', 'insurance_amount');", 'id'=>'insurance_amount']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Процент АВ</label>
                        {{ Form::select("contract[financial_policy_kv_bordereau]", \App\Models\Directories\Products\Data\GAP\Gap::KV_AGENT, (int)$contract->financial_policy_kv_bordereau, ['class' => 'form-control select2-ws clear_offers']) }}

                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Алгоритм рассрочки</label>
                        {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws clear_offers']) }}
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
                        <label class="control-label">Вариант страхования</label>
                        {{ Form::select("contract[data][insurance_option]", \App\Models\Directories\Products\Data\GAP\Gap::OPTION, $contract->data->insurance_option, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>




                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Полис КАСКО СК <span class="required">*</span></label>
                        {{ Form::text("contract[data][sk_title]", $contract->data->sk_title, ['class' => 'form-control valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Номер полиса КАСКО <span class="required">*</span></label>
                        {{ Form::text("contract[data][kasko_number]", $contract->data->kasko_number, ['class' => 'form-control valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Дата начала полиса КАСКО <span class="required">*</span></label>
                        {{ Form::text("contract[data][kasko_date]", getDateFormatRu($contract->data->kasko_date), ['class' => 'form-control format-date valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4">
                        <label class="control-label">Кредитное авто</label>
                        {{ Form::select("contract[data][is_auto_credit]", \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::IS_CREDIT, $contract->data->is_auto_credit, ['class' => 'form-control select2-all clear_offers']) }}
                    </div>


                    <div class="clear"></div>


                    @if(auth()->user()->hasPermission('role', 'is_curator'))
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="control-label">Агент</label>
                            {{ Form::select("contract[set_agent_id]", \App\Models\User::getALLUserWhere()->get()->pluck('name', 'id'), $contract->agent_id, ['class' => 'form-control select2-all clear_offers']) }}
                        </div>
                        <div class="clear"></div>
                    @endif


                </div>

            </div>
        </div>
    </div>
</div>






<script>

    function initTerms() {


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



    }






</script>