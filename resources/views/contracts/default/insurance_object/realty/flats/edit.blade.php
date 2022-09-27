
    <div class="page-heading">
        <h2 class="inline-h1">Территория страхования</h2>
    </div>

    <div class="row form-horizontal" >
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">


                        <div class="col-md-6 col-lg-8" >
                            <div class="field form-col">
                                <div>
                <span>
                    Адрес, Дом, Корпус, Квартира <span class="required">*</span>
                </span>
                                    {{ Form::text("contract[object][address]",  $object->address, ['class' => 'form-control', 'id' => "object_address", 'placeholder' => 'Нижегородская обл, Лукояновский р-н, поселок Новая Москва']) }}

                                    {{ Form::text("contract[object][address_kladr]",  $object->address_kladr, ['class' => 'hidden', 'id' => "object_address_kladr"]) }}
                                    <input name="contract[object][address_region]" id="object_address_region" value="{{$object->address_region}}" type="hidden"/>
                                    <input name="contract[object][address_city]" id="object_address_city" value="{{$object->address_city}}" type="hidden"/>
                                    <input name="contract[object][address_city_kladr_id]" id="object_address_city_kladr_id" value="{{$object->address_city_kladr_id}}" type="hidden"/>
                                    <input name="contract[object][address_street]" id="object_address_street" value="{{$object->address_street}}" type="hidden"/>

                                    <input name="contract[object][address_latitude]" id="object_address_latitude" value="{{$object->address_latitude}}" type="hidden"/>
                                    <input name="contract[object][address_longitude]" id="object_address_longitude" value="{{$object->address_longitude}}" type="hidden"/>

                                    <input name="contract[object][address_house]" id="object_address_house" value="{{$object->address_house}}" type="hidden"/>
                                    <input name="contract[object][address_block]" id="object_address_block" value="{{$object->address_block}}" type="hidden"/>
                                    <input name="contract[object][address_flat]" id="object_address_flat" value="{{$object->address_flat}}" type="hidden"/>

                                </div>
                            </div>
                        </div>



                        <div class="col-md-6 col-lg-2" >
                            <span>Дом этажей</span>
                            {{Form::text("contract[object][house_floor]", $object->house_floor ? $object->house_floor : "", ['class' => 'form-control'])}}
                        </div>

                        <div class="col-md-6 col-lg-2" >
                            <span>Квартира этаж</span>
                            {{Form::text("contract[object][flat_floor]", $object->flat_floor ? $object->flat_floor : "", ['class' => 'form-control'])}}
                        </div>

                        <div class="clear"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>



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