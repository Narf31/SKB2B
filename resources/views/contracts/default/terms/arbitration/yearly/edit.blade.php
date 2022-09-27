<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во текущих процедур</label>
                        {{Form::select("contract[arbitration][count_current_procedures]", collect(\App\Models\Directories\Products\Data\LiabilityArbitrationManager::CURRENT_PROCEDURES), $contract->data->count_current_procedures, ['class' => 'form-control select2-ws', 'id'=>'count_current_procedures', 'onchange'=>'getOriginalTariff()'])}}
                    </div>


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
                        {{ Form::text("contract[insurance_amount]", ($contract->insurance_amount > 0)? titleFloatFormat($contract->insurance_amount, 0, 1):'', ['class' => 'form-control sum valid_accept', 'id'=>'insurance_amount']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                        <label class="control-label">Алгоритм рассрочки</label>
                        {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws']) }}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-2" >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-2" >
                        <label class="control-label">Договор пролонгации</label>
                        {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control']) }}
                    </div>

                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" >
                        <label class="control-label">Заказчик (СРО)</label>
                        {{Form::text("contract[arbitration][cro_title]", ($contract->data->cro?$contract->data->cro->title:''), ['class' => 'form-control searchOrganization', 'data-set-id'=>"cro_id", 'id'=>'cro_title']) }}
                        <input type="hidden" class="valid_accept" data-parent="cro_title" name="contract[arbitration][cro_id]" id="cro_id" value="{{$contract->data->cro_id}}"/>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1">
                        <label class="control-label">Срочно</label><br/>
                        <input @if($contract->data->is_urgently == 1) checked="checked" @endif class="easyui-switchbutton clear_offers" data-options="onText:'Да',offText:'Нет'" name="contract[arbitration][is_urgently]" type="checkbox">
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1">
                        <label class="control-label">Стаж (лет) <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][experience]", $contract->data->experience, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во жалоб <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][count_complaints]", $contract->data->count_complaints, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во предупреждений <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][count_warnings]", $contract->data->count_warnings, ['class' => 'form-control sum valid_accept']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Кол-во штрафов <span class="required">*</span></label>
                        {{ Form::text("contract[arbitration][count_fines]", $contract->data->count_fines, ['class' => 'form-control sum valid_accept']) }}
                    </div>




                </div>

            </div>
        </div>
    </div>
</div>




<script>

    function initTerms() {

        formatTime();
        searchOrganization();

    }



    function searchOrganization() {
        $('.searchOrganization').suggestions({
            serviceUrl: "/suggestions/dadata/organization",
            token: "",
            type: "PARTY",
            count: 5,
            minChars: 2,
            formatResult: function(e, t, n, i) {
                var s = this;
                e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);
                return e;
            },

            onSelect: function (suggestion) {

                $("#"+$(this).data("set-id")).val(suggestion.id);

            }
        });
    }

</script>