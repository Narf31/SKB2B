
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row page-heading">
            <h2 class="inline-h1">Условия договора</h2>
        </div>

        <div class="row form-horizontal" >
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-xs-4 col-sm-2 col-md-1 col-lg-1" >
                        <label class="control-label">Шенген</label><br/>
                        <input @if($contract->data->is_schengen == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="contract[vzr][is_schengen]" id="is_schengen" type="checkbox" value="1">
                    </div>


                    @php

                    $сountry_json = [];
                    if(strlen($contract->data->сountry_json) > 0){
                        $сountry_json = json_decode($contract->data->сountry_json);
                    }

                    @endphp

                    <div class="col-xs-12 col-sm-10 col-md-11 col-lg-11" >
                        <label class="control-label">Страна</label>
                        {{Form::select("contract[vzr][сountry_json][]", \App\Models\Settings\Country::where('id', '!=', '51')->orderBy('title')->get()->pluck('title', 'id'), $сountry_json, ['class' => 'form-control select2-all', 'multiple' => true])}}
                    </div>

                    <input type="hidden" name="contract[installment_algorithms_id]" value="{{$contract->installment_algorithms_id}}"/>
                    <input type="hidden" name="contract[is_prolongation]" value="0"/>
                    <input type="hidden" name="contract[prolongation_bso_title]" value=""/>



                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Тип договора</label>
                        {{Form::select("contract[vzr][type_agr_id]", collect(\App\Models\Directories\Products\Data\VZR::TYPE_AGR), $contract->data->type_agr_id, ['class' => 'form-control select2-ws', 'id'=>'type_agr_id', "onchange"=>"viewDataVZR(1)", 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="col-xs-12 col-sm-3 col-md-4 col-lg-3 type_agr type_agr_1" >
                        <label class="control-label">Программа</label>
                        {{Form::select("contract[vzr][programs][1]", collect(\App\Models\Directories\Products\Data\VZR::PROGRAMS[1]), $contract->data->programs_id, ['class' => 'form-control select2-ws', 'id'=>'programs_1', 'onchange'=>'viewPrice()'])}}
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-4 col-lg-3 type_agr type_agr_2" >
                        <label class="control-label">Программа</label>
                        {{Form::select("contract[vzr][programs][2]", collect(\App\Models\Directories\Products\Data\VZR::PROGRAMS[2]), $contract->data->programs_id, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>




                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 type_agr ss1" >
                        <label class="control-label">Страховая сумма</label>
                        {{Form::select("contract[vzr][amount][1]", collect(\App\Models\Directories\Products\Data\VZR::AMOUNT[1]), $contract->data->amount, ['class' => 'form-control select2-ws', 'id'=>'amount_1'])}}
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 type_agr ss3" >
                        <label class="control-label">Страховая сумма</label>
                        {{Form::select("contract[vzr][amount][3]", collect(\App\Models\Directories\Products\Data\VZR::AMOUNT[3]), $contract->data->amount, ['class' => 'form-control select2-ws', 'id'=>'amount_3'])}}
                    </div>



                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 type_agr type_agr_2" >
                        <label class="control-label">Страховая сумма</label>
                        {{Form::select("contract[vzr][amount][2]", collect(\App\Models\Directories\Products\Data\VZR::AMOUNT[2]), $contract->data->amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="col-xs-12 col-sm-3 col-md-4 col-lg-2" >
                        <label class="control-label">Валюта</label>
                        {{Form::select("contract[vzr][currency_id]", \App\Models\Settings\Currency::getCurrencyDay(),  $contract->data->currency_id, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-4 col-lg-1" >
                        <label class="control-label">Франшиза</label>
                        {{Form::select("contract[vzr][franchise_id]", collect(\App\Models\Directories\Products\Data\VZR::FRANCHISE), $contract->data->franchise_id, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>



                    <div class="clear"></div>



                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-2">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                <input placeholder="" name="contract[begin_date]" class="form-control format-date valid_accept" id="begin_date_0" onchange="setDataVZR()" value="{{ $contract->begin_date  ? setDateTimeFormatRu($contract->begin_date, 1): Carbon\Carbon::now()->format('d.m.Y')}}">
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>



                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-2">
                        <label class="control-label">Дата окончания <span class="required">*</span></label>
                        {{Form::text("contract[end_date]", ($contract->end_date  ? setDateTimeFormatRu($contract->end_date, 1) : ''), ['class' => 'form-control format-date valid_accept', 'id'=>'end_date_0', 'onchange'=>'setDayDate()'])}}
                        <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                    </div>


                    <div class="col-xs-12 col-sm-2 col-md-4 col-lg-2 type_agr type_agr_1">
                        <label class="control-label">Дней прибывания<span class="required">*</span></label>
                        {{Form::text("contract[vzr][count_day]", $contract->data->count_day, ['class' => 'form-control valid_accept sum', 'id'=>'count_day'])}}
                    </div>

                    <div class="col-xs-12 col-sm-5 col-md-4 col-lg-2 type_agr type_agr_2" >
                        <label class="control-label">Дней прибывания</label>
                        {{Form::select("contract[vzr][day_to]", collect(\App\Models\Directories\Products\Data\VZR::DAY_TO), $contract->data->day_to, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 type_agr type_agr_2" >

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Дата начала</th>
                                <th>Дата окончания</th>
                                <th>Дней</th>
                                <th>
                                    <span class="btn btn-primary pull-right" onclick="openDatesPer()">Добавить</span>
                                </th>
                            </tr>
                            </thead>

                            @php

                                $dates = [];
                                if(strlen($contract->data->dates) > 10){
                                    $dates = json_decode($contract->data->dates);
                                }

                                $_d_k = 1;

                            @endphp

                            <tbody id="dates_table">
                            @foreach($dates as $date_arr)

                                <tr id="dates_table-{{$_d_k}}">
                                    <td>{{$date_arr->date_from}}</td>
                                    <td>{{$date_arr->date_to}}</td>
                                    <td>{{$date_arr->date_day}}</td>
                                    <td>
                                        <span class="pull-right" style="cursor: pointer;color: red;" onclick="remuveData('dates_table-{{$_d_k}}')"><i class="fa fa-close"></i></span>
                                    </td>
                                </tr>

                                @php
                                    $_d_k++;
                                @endphp
                            @endforeach

                            </tbody>
                        </table>

                    </div>


                    <div class="clear"></div>




                </div>

            </div>
        </div>
    </div>
</div>




<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="form-equally col-xs-12 col-sm-12 col-md-8 col-lg-8">

        <div class="row page-heading">
            <h2 class="inline-h1">Дополнительные программы</h2>
        </div>
        <div class="row form-horizontal" >
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">


                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">Задержка рейса</label>
                            {{Form::select("contract[vzr][flight_delay_program]", collect(\App\Models\Directories\Products\Data\VZR::FLIGHT_DELAY_PROGRAM), $contract->data->flight_delay_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"flight_delay_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 flight_delay_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][flight_delay_amount]", collect(\App\Models\Directories\Products\Data\VZR::FLIGHT_DELAY_AMOUNT), $contract->data->flight_delay_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_missed_flight">
                            <label class="control-label">Опоздание на рейс</label>
                            {{Form::select("contract[vzr][missed_flight_program]", collect(\App\Models\Directories\Products\Data\VZR::MISSED_FLIGHT_PROGRAM), $contract->data->missed_flight_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"missed_flight_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 missed_flight_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][missed_flight_amount]", collect(\App\Models\Directories\Products\Data\VZR::MISSED_FLIGHT_AMOUNT), $contract->data->missed_flight_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_baggage">
                            <label class="control-label">Багаж</label>
                            {{Form::select("contract[vzr][baggage_program]", collect(\App\Models\Directories\Products\Data\VZR::BAGGAGE_PROGRAM), $contract->data->baggage_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"baggage_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 baggage_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][baggage_amount]", collect(\App\Models\Directories\Products\Data\VZR::BAGGAGE_AMOUNT), $contract->data->baggage_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_baggage">
                            <label class="control-label">Отмена поездки</label>
                            {{Form::select("contract[vzr][cancel_trip_program]", collect(\App\Models\Directories\Products\Data\VZR::CANCEL_TRIP_PROGRAM), $contract->data->cancel_trip_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"cancel_trip_program", 'style'=>'width: 100%;'])}}
                        </div>


                        <div class="clear"></div>


                    </div>

                    <div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">


                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_legal_aid">
                            <label class="control-label">Несчастный случай</label>
                            {{Form::select("contract[vzr][ns_program]", collect(\App\Models\Directories\Products\Data\VZR::NS_PROGRAM), $contract->data->ns_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"ns_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ns_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][ns_amount]", collect(\App\Models\Directories\Products\Data\VZR::NS_AMOUNT), $contract->data->ns_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_сivil_responsibility">
                            <label class="control-label">Гражданская ответственность</label>
                            {{Form::select("contract[vzr][сivil_responsibility_program]", collect(\App\Models\Directories\Products\Data\VZR::CIVIL_RESPONSIBILITY_PROGRAM), $contract->data->сivil_responsibility_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"сivil_responsibility_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 сivil_responsibility_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][сivil_responsibility_amount]", collect(\App\Models\Directories\Products\Data\VZR::CIVIL_RESPONSIBILITY_AMOUNT), $contract->data->сivil_responsibility_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>


                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_legal_aid">
                            <label class="control-label">Юридическая помощь</label>
                            {{Form::select("contract[vzr][legal_aid_program]", collect(\App\Models\Directories\Products\Data\VZR::LEGAL_AID_PROGRAM), $contract->data->legal_aid_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"legal_aid_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 legal_aid_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][legal_aid_amount]", collect(\App\Models\Directories\Products\Data\VZR::LEGAL_AID_AMOUNT), $contract->data->legal_aid_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 is_legal_aid">
                            <label class="control-label">Отмена экскурсии</label>
                            {{Form::select("contract[vzr][cancel_tour_program]", collect(\App\Models\Directories\Products\Data\VZR::CANCEL_TOUR_PROGRAM), $contract->data->cancel_tour_program, ['class' => 'form-control select2-ws is_program', "data-group"=>"cancel_tour_program", 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 cancel_tour_program">
                            <label class="control-label">Страховая сумма</label>
                            {{Form::select("contract[vzr][cancel_tour_amount]", collect(\App\Models\Directories\Products\Data\VZR::CANCEL_TOUR_AMOUNT), $contract->data->cancel_tour_amount, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                        </div>

                        <div class="clear"></div>

                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">

        <div class="row page-heading">
            <h2 class="inline-h1">Риски</h2>
        </div>
        <div class="row form-horizontal" >
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    @php
                        $dataArray = $contract->data->toArray();
                    @endphp

                    @foreach(\App\Models\Directories\Products\Data\VZR::OPTIONS as $KEY => $VAL)

                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" >
                            <label class="control-label">{{$VAL}}</label><br/>
                            <input @if(isset($dataArray[$KEY]) && $dataArray[$KEY] == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="contract[vzr][{{$KEY}}]" type="checkbox" value="1">
                        </div>

                    @endforeach


                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                        <label class="control-label">Спорт</label>
                        {{Form::select("contract[vzr][sport_id]", collect(\App\Models\Directories\Products\Data\VZR::SPORTS), $contract->data->sport_id, ['class' => 'form-control select2-ws', 'onchange'=>'viewSport()', 'id'=>'sport_id'])}}
                    </div>

                    <div class="clear"></div>

                    @foreach(\App\Models\Directories\Products\Data\VZR::SPORTS_TEXT as $KEY => $VAL)

                        <span class="sport_text col-xs-12 col-sm-12 col-md-12 col-lg-12" id="sport_text_{{$KEY}}" style="text-align: justify;font-size: 18px;color: #000;display: none;" >
                           {{$VAL}}
                        </span>

                    @endforeach


                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                        <label class="control-label">Профессия</label>
                        {{Form::select("contract[vzr][profession_id]", collect(\App\Models\Directories\Products\Data\VZR::PROFESSIONS), $contract->data->profession_id, ['class' => 'form-control select2-ws', 'onchange'=>'viewProfession()', 'id'=>'profession_id'])}}
                    </div>

                    <div class="clear"></div>

                    @foreach(\App\Models\Directories\Products\Data\VZR::PROFESSIONS_TEXT as $KEY => $VAL)

                        <span class="profession_text col-xs-12 col-sm-12 col-md-12 col-lg-12" id="profession_text_{{$KEY}}" style="text-align: justify;font-size: 18px;color: #000;display: none;" >
                           {{$VAL}}
                        </span>

                    @endforeach

                    <div class="clear"></div>

                </div>
            </div>
        </div>
    </div>
</div>





<div id="dates_per" class="hidden">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: white;">
        <div class="form-horizontal">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label">Дата начала</label>
                {{Form::text("date_from", '', ['class' => 'form-control mydate date_from', 'style'=>'width: 100%;'])}}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label">Дата окончания</label>
                {{Form::text("date_to", '', ['class' => 'form-control mydate date_to', 'style'=>'width: 100%;'])}}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <span class="btn btn-success pull-right" onclick="setDatesPer()">Сохранить</span>
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

        viewDataVZR(0);
        viewSport();
        viewProfession();

        $('#is_schengen').switchbutton({
            onChange: function(checked){
                viewDataVZR(0);
            }
        });


    }


    function openDatesPer()
    {
        $.fancybox.open("<div id='fancybox-data'>"+$("#dates_per").html()+"</div>");

        $("#fancybox-data").find('.mydate').datepicker({
            dateFormat: 'dd.mm.yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '2015:2030'
        });

    }

    function setDatesPer() {

        date_from = $("#fancybox-data").find(".date_from").val();
        date_to = $("#fancybox-data").find(".date_to").val();

        index = $("#dates_table tr").children().length+1;

        day = days_between(date_from, date_to);

        html = "<tr id='dates_table-"+index+"'><td>" +
            date_from +
            "</td><td>" +
            date_to +
            "</td><td>" +
            '<input type="hidden" name="contract[vzr][dates]['+index+'][date_from]" value="'+date_from+'"/>' +
            '<input type="hidden" name="contract[vzr][dates]['+index+'][date_to]" value="'+date_to+'"/>' +
            '<input type="hidden" name="contract[vzr][dates]['+index+'][date_day]" value="'+day+'"/>' +
            day+
            "</td><td>" +
            '<span class="pull-right" style="cursor: pointer;color: red;" onclick="remuveData('+"'dates_table-"+index+"'"+')"><i class="fa fa-close"></i></span>' +
            "</td></tr>";
        $("#dates_table").append(html);

        $.fancybox.close();
    }

    function remuveData(id) {
        $("#"+id).remove();
    }

    function viewDataVZR(state) {

        $(".type_agr").hide();

        if($("#type_agr_id").val() == 1){
            $("#end_date_0").removeAttr('readonly');
            $("#count_day").removeAttr('readonly');
            if(state == 1){
                $("#end_date_0").val('');
                $("#count_day").val('');
            }


            if($("#is_schengen").prop('checked')){
                $(".ss3").show();
            }else{
                $(".ss1").show();
            }


        }

        if($("#type_agr_id").val() == 2){
            $("#end_date_0").attr('readonly', 'readonly');
            $("#count_day").attr('readonly', 'readonly');
        }

        $(".type_agr_"+$("#type_agr_id").val()).show();

        setDataVZR();
    }

    function setDataVZR() {

        if($("#type_agr_id").val() == 1){
            setDayDate();
        }

        if($("#type_agr_id").val() == 2){
            setEndDates(0);
            $("#count_day").val(365);
        }

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


        if($("[name*='contract[vzr]["+name+"]']" ).val() > 0){
            $("."+name).show();
        }else{
            $("."+name).hide();
        }
    }

    function viewSport() {

        $(".sport_text").hide();
        name = "#sport_text_"+$("#sport_id").val();
        $(name).show();

    }

    function viewProfession() {

        $(".profession_text").hide();
        name = "#profession_text_"+$("#profession_id").val();
        $(name).show();

    }

    function viewPrice() {

        loaderShow();

        $.post('/contracts/online/{{$contract->id}}/action/view-control', $('#product_form').serialize(), function (response) {

            var options = "";
            var val = 0;

            for(var k in response) {

                if(val == 0){
                    val = k;
                }

                options += "<option value='" + k + "'>" + response[k] + "</option>";

            }


            $("#amount_1").html(options).select2('val', val);


        }).always(function () {
            loaderHide();
        });

        return true;


    }




</script>