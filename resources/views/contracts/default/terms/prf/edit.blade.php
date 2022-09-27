



<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="form-equally col-xs-12 col-sm-12 col-md-8 col-lg-7">

        <div class="row page-heading">
            <h2 class="inline-h1">Условия договора</h2>
        </div>
        <div class="row form-horizontal" >
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <input type="hidden" name="contract[installment_algorithms_id]" value="{{$contract->installment_algorithms_id}}"/>
                    <input type="hidden" name="contract[is_prolongation]" value="0"/>
                    <input type="hidden" name="contract[prolongation_bso_title]" value=""/>



                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date valid_accept" id="begin_date_0" onchange="setDayDate()" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <label class="control-label">Дата окончания <span class="required">*</span></label>
                        {{Form::text("contract[end_date]", ($contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : ''), ['class' => 'form-control format-date valid_accept', 'id'=>'end_date_0', 'onchange'=>'setDayDate()'])}}
                        <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                    </div>


                    <div class="col-xs-12 col-sm-3 col-md-4 col-lg-3">
                        <label class="control-label" style="max-width: none;">Дней прибывания<span class="required">*</span></label>
                        {{Form::text("contract[prf][count_day]", $contract->data->count_day, ['class' => 'form-control valid_accept sum', 'id'=>'count_day', 'onchange'=>'setDateDay()'])}}
                    </div>



                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3" >
                        <label class="control-label">Программа</label>
                        {{Form::select("contract[prf][programs]", collect(\App\Models\Directories\Products\Data\PRF::PROGRAMS), $contract->data->programs_id, ['class' => 'form-control select2-ws'])}}
                    </div>


                    <div class="col-xs-12 col-sm-7 col-md-4 col-lg-3" >
                        <label class="control-label">Страховая сумма</label>
                        {{Form::select("contract[prf][amount]", collect(\App\Models\Directories\Products\Data\PRF::AMOUNT), $contract->data->amount, ['class' => 'form-control select2-ws'])}}
                    </div>


                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3" >
                        <label class="control-label">Несчастный случай</label>
                        {{Form::select("contract[prf][ns_program]", collect(\App\Models\Directories\Products\Data\PRF::NS_PROGRAM), $contract->data->ns_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"ns_program", 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="col-xs-12 col-sm-7 col-md-4 col-lg-3 ns_program" >
                        <label class="control-label">Страховая сумма</label>
                        {{Form::select("contract[prf][ns_amount]", collect(\App\Models\Directories\Products\Data\PRF::NS_AMOUNT), $contract->data->ns_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>



                    <div class="clear"></div>












                </div>


            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-5 ">

        <div class="row page-heading">
            <h2 class="inline-h1">Риски</h2>
        </div>
        <div class="row form-horizontal" >
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    @php
                        $dataArray = $contract->data->toArray();
                    @endphp

                    @foreach(\App\Models\Directories\Products\Data\PRF::OPTIONS as $KEY => $VAL)

                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" >
                            <label class="control-label">{{$VAL}}</label><br/>
                            <input @if(isset($dataArray[$KEY]) && $dataArray[$KEY] == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="contract[prf][{{$KEY}}]" type="checkbox" value="1">
                        </div>

                    @endforeach




                </div>
            </div>
        </div>
    </div>
</div>






<script>

    function initTerms() {

        $(".is_program").each(function( index ) {
            isViewDataProgram($( this ).data('group'));
        });

        $('.is_program').change(function(){
            isViewDataProgram($( this ).data('group'));
        });


    }




    function setDayDate() {
        dataDay = days_between($('#begin_date_0').val(), $('#end_date_0').val());
        $("#count_day").val(dataDay);
    }

    function setDateDay()
    {
        count_day = parseInt($('#count_day').val());
        if(count_day > 0){
            $('#end_date_0').val(get_end_dates_day($('#begin_date_0').val(), (count_day)));
        }
    }


    function isViewDataProgram(name) {


        if($("[name*='contract[prf]["+name+"]']" ).val() > 0){
            $("."+name).show();
        }else{
            $("."+name).hide();
        }
    }



</script>