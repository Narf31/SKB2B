<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-xs-12 col-sm-4 col-md-2 col-lg-1">
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
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date valid_accept" id="begin_date_0" onchange="setEndDates(0)" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата окончания <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[end_date]" class="form-control format-date end-date valid_accept" id="end_date_0" value="{{$contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : Carbon\Carbon::now()->addYear()->subDay(1)->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" >
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




<script>

    function initTerms() {

        formatTime();



    }



</script>