<div class="page-heading">
    <h2 class="inline-h1">Условия договора</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1">
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
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date-today valid_accept" id="begin_date_0" onchange="setEndDates(0)" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
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
                                <input placeholder="" name="contract[end_date]" readonly class="form-control end-date valid_accept" id="end_date_0" value="{{$contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : Carbon\Carbon::now()->addYear()->subDay(1)->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Вид договора</label>
                        {{ Form::select("contract[osago][is_epolicy]", collect(\App\Models\Directories\Products\Data\Osago::CONTRACT_TYPE) , $contract->data->is_epolicy, ['class' => 'form-control select2-ws clear_offers']) }}
                    </div>

                    <input type="hidden" name="contract[installment_algorithms_id]" value="{{$contract->getAlgorithms()->first()->id}}"/>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" >
                        <label class="control-label">Договор пролонгации</label>
                        {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control']) }}
                    </div>

                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                        <label  class="control-label" style="max-width:100%;">Период действия совпадает с датами договора</label>
                        {{ Form::checkbox('contract[osago][is_period_same]', 1, $contract->data->period_beg2 ? 0 : 1 ,['style' => 'width:18px;height:18px;margin-left:5px;position:absolute;','onclick' => "viewPeriods()", 'id'=>"is_period_same"]) }}
                    </div>

                    <div class="form-equally osago-periods row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Начало периода 1 <span class="required">*</span>
                                    </label>
                                    <input placeholder="" name="contract[osago][period_beg1]" id="period_beg1" value="{{getDateFormatRu($contract->data->period_beg1)}}" class="form-control format-date valid_accept">
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Конец  периода 1 <span class="required">*</span>
                                    </label>
                                    <input placeholder="" name="contract[osago][period_end1]" id="period_end1" value="{{getDateFormatRu($contract->data->period_end1)}}" class="form-control format-date valid_accept">
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Начало периода 2
                                    </label>
                                    <input placeholder="" name="contract[osago][period_beg2]" value="{{getDateFormatRu($contract->data->period_beg2)}}" class="form-control format-date ">
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Конец  периода 2
                                    </label>
                                    <input placeholder="" name="contract[osago][period_end2]" value="{{getDateFormatRu($contract->data->period_end2)}}" class="form-control format-date ">
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Начало периода 3
                                    </label>
                                    <input placeholder="" name="contract[osago][period_beg3]" value="{{getDateFormatRu($contract->data->period_beg3)}}" class="form-control format-date ">
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Конец  периода 3
                                    </label>
                                    <input placeholder="" name="contract[osago][period_end3]" value="{{getDateFormatRu($contract->data->period_end3)}}" class="form-control format-date ">
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>
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
        setEndDates(0);
        viewPeriods();

    }

    function viewPeriods() {
        if($('#is_period_same').prop('checked')){
            $('.osago-periods').hide();
            $('[name="contract[osago][period_beg2]"]').val('');
            $('[name="contract[osago][period_beg3]"]').val('');
            $('[name="contract[osago][period_end2]"]').val('');
            $('[name="contract[osago][period_end3]"]').val('');
            setEndDates(0);

        }else{
            $('.osago-periods').show();
        }
    }



</script>