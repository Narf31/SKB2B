<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" data-intro='Условия страхования'>
        <div class="row form-horizontal">
            <h2 class="inline-h1">Условия договора</h2>
            <br/><br/>

            <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
                    <div class="field form-col">
                        <div>
                            <label class="control-label">
                                Время <span class="required">*</span>
                            </label>
                            <input placeholder="" name="contract[begin_time]" class="form-control valid_accept format-time" value="{{$contract->begin_time  ?? '00:00'}}">
                            <span class="glyphicon glyphicon-time calendar-icon"></span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5">
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
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-5">
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

                <div class="clear"></div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4" >
                    <label class="control-label">Алгоритм рассрочки</label>
                    {{ Form::select("contract[installment_algorithms_id]", $contract->getAlgorithms()->pluck('title', 'id') , $contract->installment_algorithms_id, ['class' => 'form-control select2-ws']) }}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
                    <label class="control-label">Тип договора</label>
                    {{Form::select("contract[is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация']), $contract->is_prolongation, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
                    <label class="control-label">Договор пролонгации</label>
                    {{ Form::text("contract[prolongation_bso_title]", $contract->prolongation_bso_title, ['class' => 'form-control']) }}
                </div>

                <div class="clear"></div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="control-label">Территория страхования <span class="required">*</span></label>
                    {{ Form::text("contract[object][address]",  $object->address, ['class' => 'form-control valid_accept', 'id' => "object_address", 'placeholder' => '']) }}
                    <input name="contract[object][address_region]" id="object_address_region" value="{{$object->address_region}}" type="hidden"/>
                    <input name="contract[object][address_city_kladr_id]" id="object_address_city_kladr_id" value="{{$object->address_city_kladr_id}}" type="hidden"/>
                    <input name="contract[object][address_latitude]" id="object_address_latitude" value="{{$object->address_latitude}}" type="hidden"/>
                    <input name="contract[object][address_longitude]" id="object_address_longitude" value="{{$object->address_longitude}}" type="hidden"/>

                    <input name="contract[object][address_city]" id="object_address_city" value="{{$object->address_city}}" type="hidden"/>
                    <input name="contract[object][address_street]" id="object_address_street" value="{{$object->address_street}}" type="hidden"/>

                </div>

                <div class="clear"></div>

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                    <label class="control-label">Дом <span class="required">*</span></label>
                    {{Form::text("contract[object][address_house]", $object->address_house ? $object->address_house : "", ['class' => 'form-control valid_accept', 'id' => 'object_address_house'])}}
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                    <label class="control-label">Строение</label>
                    {{Form::text("contract[object][address_block]", $object->address_block ? $object->address_block : "", ['class' => 'form-control', 'id' => 'object_address_block'])}}
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
                    <label class="control-label">Квартира <span class="required">*</span></label>
                    {{Form::text("contract[object][address_flat]", $object->address_flat ? $object->address_flat : "", ['class' => 'form-control valid_accept', 'id' => 'object_address_flat'])}}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2" >
                    <label class="control-label">Этаж</label>
                    {{Form::text("contract[object][flat_floor]", $object->flat_floor ? $object->flat_floor : "", ['class' => 'form-control'])}}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2" >
                    <label class="control-label">Дом этажей</label>
                    {{Form::text("contract[object][house_floor]", $object->house_floor ? $object->house_floor : "", ['class' => 'form-control'])}}
                </div>
                <div class="clear"></div>

            </div>
        </div>
    </div>

</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" data-intro='Программы страхования'>
        <div class="row form-horizontal">
            <h2 class="inline-h1">Программы</h2>
            <br/><br/>

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th width="10%"></th>
                    <th>Правила</th>
                    <th>Страховая сумма, руб.</th>
                </tr>
                </thead>
                <tbody>

                @foreach($contract->product->flats_risks as $flats_risks)

                    <tr class="program programs_{{$flats_risks->id}}">
                        <th>{{ Form::checkbox("contract[risks][programs][$flats_risks->id]", 1, (array_search("$flats_risks->id", $terms) !== false ?1:0), ['class' => 'easyui-switchbutton programs', 'data-name'=>"programs_{$flats_risks->id}", 'data-options'=>"onText:'Да',offText:'Нет'", 'id' => "programs_{$flats_risks->id}" ]) }}</th>
                        <th>
                            {{$flats_risks->title}}

                        </th>

                        <th><strong style="font-size: 16px;">{{titleFloatFormat($flats_risks->insurance_amount)}} руб. {{$flats_risks->insurance_amount_comment}}</strong></th>
                    </tr>

                @endforeach

                </tbody>
            </table>

        </div>
        <br/>
    </div>


</div>


<script>

    function initTerms() {





    }


    function initStartObject()
    {
        $('#object_address').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {

                key = $(this).data('key');
                $('#object_address').val($(this).val());
                $('#object_address_kladr').val(suggestion.data.kladr_id);
                $('#object_address_region').val(suggestion.data.region);
                $('#object_address_city').val(suggestion.data.city);
                $('#object_address_city_kladr_id').val(suggestion.data.city_kladr_id);
                $('#object_address_street').val(suggestion.data.street_with_type);
                $('#object_address_house').val(suggestion.data.house);
                $('#object_address_block').val(suggestion.data.block);
                $('#object_address_flat').val(suggestion.data.flat);

                $('#object_address_latitude').val(suggestion.data.geo_lat);
                $('#object_address_longitude').val(suggestion.data.geo_lon);



            }
        });
    }


    function initStartRisks(){

        stateViewRowProgram();

        setTimeout(function(){
            $('.switchbutton').click(function(){
                stateViewRowProgram()
            });
        }, 2000);


    }



    function stateViewRowProgram()
    {
        $(".program").css('background-color', '#fff');
        $(".programs").each(function( index ) {
            if($( this ).prop('checked')){
                $("."+$( this ).data('name')).css('background-color', '#e6ffe6');
            }
        });

        clearCalc();

    }

</script>