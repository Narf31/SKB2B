<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">


                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date valid_accept" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
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
                                {{ Form::select("contract[migrants][date_month]", \App\Models\Directories\Products\Data\Migrants::DATE_MONTH , $contract->data->date_month, ['class' => 'form-control select2-ws', 'onchange' => 'getCheckDisabled()']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Программа <span class="required">*</span>
                                </label>
                                {{ Form::select("contract[migrants][programs_id]", \App\Models\Directories\Products\Data\Migrants::PROGRAMS , $contract->data->programs_id, ['class' => 'form-control select2-ws', 'onchange' => 'getCheckDisabled()']) }}
                            </div>
                        </div>
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
                </div>

            </div>
        </div>
    </div>
</div>



<div class="page-heading">
    <h2 class="inline-h1">Дополнительные риски</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Несчастный случай</label>
                        {{ Form::select("contract[migrants][ns]", \App\Models\Directories\Products\Data\Migrants::NO_YES, $contract->data->ns, ['class' => 'form-control select2-ws dop_programs', 'id'=>'ns']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Беременность</label>
                        {{ Form::select("contract[migrants][pregnancy]", \App\Models\Directories\Products\Data\Migrants::NO_YES, $contract->data->pregnancy, ['class' => 'form-control select2-ws dop_programs', 'id'=>'pregnancy']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Диспансеризация</label>
                        {{ Form::select("contract[migrants][clinical_examination]", \App\Models\Directories\Products\Data\Migrants::NO_YES, $contract->data->clinical_examination, ['class' => 'form-control select2-ws dop_programs', 'id'=>'clinical_examination']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Стоматологическая помощь</label>
                        {{ Form::select("contract[migrants][dental_care]", \App\Models\Directories\Products\Data\Migrants::NO_YES, $contract->data->dental_care, ['class' => 'form-control select2-ws dop_programs', 'id'=>'dental_care']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Погребение</label>
                        {{ Form::select("contract[migrants][interment]", \App\Models\Directories\Products\Data\Migrants::NO_YES, $contract->data->interment, ['class' => 'form-control select2-ws dop_programs', 'id'=>'interment']) }}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Транспортировка</label>
                        {{ Form::select("contract[migrants][transportation]", \App\Models\Directories\Products\Data\Migrants::NO_YES, $contract->data->transportation, ['class' => 'form-control select2-ws dop_programs', 'id'=>'transportation']) }}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


<script>

    function initTerms() {

        //formatTime();

        getCheckDisabled();

    }



</script>