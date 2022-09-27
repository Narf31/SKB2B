<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
    <div class="form__field" style="margin-top: 15px;margin-bottom:-18px;font-size: 18px;font-weight: bold;">
        Условия договора
    </div>
</div>


<div class="form__list col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row row__custom">
        <input type="hidden" name="contract[begin_time]" value="00:00"/>
        <input type="hidden" name="contract[is_prolongation]" value="{{$contract->is_prolongation}}"/>
        <input type="hidden" name="contract[prolongation_bso_title]" value="{{$contract->prolongation_bso_title}}"/>

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col__custom form__item">
            <div class="form__field">
                {{ Form::text("contract[begin_date]", setDateTimeFormatRu($contract->begin_date, 1), ['class' => 'valid_accept format-date', 'id'=>"begin_date_0", 'onchange'=>"setEndDates(0)"]) }}
                <div class="form__label">Дата начала <span class="required">*</span></div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col__custom form__item">
            <div class="form__field">
                {{ Form::text("contract[end_date]", setDateTimeFormatRu($contract->end_date, 1), ['class' => 'valid_accept format-date', 'id'=>"end_date_0"]) }}
                <div class="form__label">Дата окончания <span class="required">*</span></div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col__custom form__item">
            <div class="form__field">
                <div class="select__wrap">
                    {{Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id'), $contract->installment_algorithms_id, ['class' => '']) }}
                </div>
            </div>
        </div>

    </div>



</div>
