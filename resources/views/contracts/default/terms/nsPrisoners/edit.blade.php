<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

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
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date valid_accept" value="{{setDateTimeFormatRu($contract->begin_date, 1)}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Срок действия <span class="required">*</span>
                                </label>
                                {{Form::select("contract[ns_prisoners][insurance_term]", collect([6=>"6 месяцев", 12=>'12 месяцев']), $contract->data->insurance_term, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2" >
                        <label class="control-label">Алгоритм рассрочки</label>
                        {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label class="control-label">Скидка за счет КВ %</label>
                        {{ Form::text("contract[data][official_discount]", titleFloatFormat($contract->data->official_discount, 0, 1), ['class' => 'form-control sum-max-value clear_offers', 'data-sum-max-value' => 25]) }}
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
                </div>

            </div>
        </div>
    </div>
</div>




<script>

    function initTerms() {





    }



</script>