<div data-step="{{$start_step}}" id="step-{{$start_step}}" class="calc__step fadeIn animated @if($active_step == $start_step) active @endif">
    <div class="calc__step-main">
        <div class="calc__step-title">
            Территория страхования
        </div>


        <div class="row row__custom">
            <div class="form__list col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row row__custom">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                    <div class="form__field">
                        {{ Form::text("contract[object][address]",  $object->address, ['class' => 'valid_accept', 'id' => "object_address"]) }}
                        <div class="form__label">Адрес, Дом, Корпус, Квартира <span class="required">*</span></div>
                    </div>
                </div>

                <input name="contract[object][address_kladr]" id="object_address_kladr" value="{{$object->address_kladr}}" type="hidden"/>

                <input name="contract[object][address_region]" id="object_address_region" value="{{$object->address_region}}" type="hidden"/>
                <input name="contract[object][address_city]" id="object_address_city" value="{{$object->address_city}}" type="hidden"/>
                <input name="contract[object][address_city_kladr_id]" id="object_address_city_kladr_id" value="{{$object->address_city_kladr_id}}" type="hidden"/>
                <input name="contract[object][address_street]" id="object_address_street" value="{{$object->address_street}}" type="hidden"/>

                <input name="contract[object][address_latitude]" id="object_address_latitude" value="{{$object->address_latitude}}" type="hidden"/>
                <input name="contract[object][address_longitude]" id="object_address_longitude" value="{{$object->address_longitude}}" type="hidden"/>

                <input name="contract[object][address_house]" id="object_address_house" value="{{$object->address_house}}" type="hidden"/>
                <input name="contract[object][address_block]" id="object_address_block" value="{{$object->address_block}}" type="hidden"/>
                <input name="contract[object][address_flat]" id="object_address_flat" value="{{$object->address_flat}}" type="hidden" class="valid_accept" data-form="object_address"/>
                    </div>


            </div>
        </div>

        <div class="row row__custom">

        {{--Условия договора--}}
        @include('client.contracts.default.terms.default.edit', [
            'contract'=>$contract,
        ])
        </div>

        <div class="row row__custom justify-content-center">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                <div class="form__field" style="margin-top: 15px;margin-bottom:-18px;font-size: 18px;font-weight: bold;">
                    Программы
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-xl-6 col-lg-6 col__custom" >

                <div class="form__list">
                    <div class="row row__custom justify-content-center">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">

                            <div class="calc__menu">
                                <ul>

                                    @foreach($contract->product->flats_risks as $key => $flats_risks)
                                        @if($key%2==0)
                                            @continue
                                        @endif
                                        <li>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">
                                                <div class="checkbox__wrap checkbox__wrap-small" style="padding-top: 20px;margin-bottom: -12px;">
                                                    <label>
                                                        {{ Form::checkbox("contract[risks][programs][$flats_risks->id]", 1,
                                                            (array_search("$flats_risks->id", $terms) !== false ?1:0), ['class' => ''])
                                                        }}
                                                        <div class="checkbox__decor" style="margin-top: 10px;"></div>
                                                        <div class="checkbox__title">
                                                            {{$flats_risks->title}} <br/> {{titleFloatFormat($flats_risks->insurance_amount)}} руб. {{$flats_risks->insurance_amount_comment}}
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-xl-6 col-lg-6 col__custom" >


                <div class="form__list">
                    <div class="row row__custom justify-content-center">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">

                            <div class="calc__menu">
                                <ul>

                                    @foreach($contract->product->flats_risks as $key => $flats_risks)
                                        @if($key%2!=0)
                                            @continue
                                        @endif
                                        <li>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">
                                                <div class="checkbox__wrap checkbox__wrap-small" style="padding-top: 20px;margin-bottom: -12px;">
                                                    <label>
                                                        {{ Form::checkbox("contract[risks][programs][$flats_risks->id]", 1,
                                                            (array_search("$flats_risks->id", $terms) !== false ?1:0), ['class' => ''])
                                                        }}
                                                        <div class="checkbox__decor" style="margin-top: 10px;"></div>
                                                        <div class="checkbox__title">
                                                            {{$flats_risks->title}} <br/> {{titleFloatFormat($flats_risks->insurance_amount)}} руб. {{$flats_risks->insurance_amount_comment}}
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                        </li>

                                    @endforeach
                                </ul>
                            </div>
                        </div>


                    </div>
                </div>


            </div>
        </div>



    </div>
    <div class="actions__wrap d-flex justify-content-center">
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__prev"></a>
        </div>
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__next" id="calc_butt"></a>
        </div>
    </div>
</div>

<div></div>



<script>

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
   

</script>