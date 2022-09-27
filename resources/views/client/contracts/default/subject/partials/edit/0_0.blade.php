

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][fio]", $subject->get_info()->fio, ['class' => 'valid_accept', 'id'=>"{$subject_name}_fio", 'data-key'=>"{$subject_name}"]) }}
        <div class="form__label">ФИО <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][birthdate]", setDateTimeFormatRu($subject->get_info()->birthdate, 1), ['class' => 'valid_accept format-date', 'id'=>"{$subject_name}_birthdate"]) }}
        <div class="form__label">Дата рождения <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col__custom form__item">
    <div class="form__field">
        <div class="select__wrap">
            {{Form::select("contract[{$subject_name}][sex]", collect([0=>"муж.", 1=>'жен.']), $subject->get_info()->sex, ['class' => 'valid_accept', 'id' => "{$subject_name}_sex", 'data-key'=>"{$subject_name}"]) }}
        </div>
    </div>
</div>



<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


<input type="hidden" value="0" name="contract[{{$subject_name}}][doc_type]"/>

<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][doc_serie]", $subject->get_info()->doc_serie, ['class' => 'valid_accept']) }}
        <div class="form__label">Паспорт серия <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][doc_number]", $subject->get_info()->doc_number, ['class' => 'valid_accept']) }}
        <div class="form__label">Паспорт номер <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][doc_date]", setDateTimeFormatRu($subject->get_info()->doc_date, 1), ['class' => 'valid_accept format-date']) }}
        <div class="form__label">Дата выдачи <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][doc_office]", $subject->get_info()->doc_office, ['class' => '', 'id' => "{$subject_name}_doc_office"]) }}
        <div class="form__label">Код</div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][doc_info]", $subject->get_info()->doc_info, ['class' => 'valid_accept', 'id' => "{$subject_name}_doc_info"]) }}
        <div class="form__label">Кем выдан <span class="required">*</span></div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>






<script>



    function initStartSubject()
    {




        $('#{{$subject_name}}_fio').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "NAME",
            count: 5,
            onSelect: function (suggestion) {


            }
        });

        $('#{{$subject_name}}_address_register').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {

                key = $(this).data('key');
                $('#{{$subject_name}}_address_register').val($(this).val());
                $('#{{$subject_name}}_address_register_kladr').val(suggestion.data.kladr_id);
                $('#{{$subject_name}}_address_register_okato').val(suggestion.data.okato);
                $('#{{$subject_name}}_address_register_zip').val(suggestion.data.postal_code);
                $('#{{$subject_name}}_address_register_region').val(suggestion.data.region);
                $('#{{$subject_name}}_address_register_city').val(suggestion.data.city);
                $('#{{$subject_name}}_address_register_city_kladr_id').val(suggestion.data.city_kladr_id);
                $('#{{$subject_name}}_address_register_street').val(suggestion.data.street_with_type);
                $('#{{$subject_name}}_address_register_house').val(suggestion.data.house);
                $('#{{$subject_name}}_address_register_house').change();
                $('#{{$subject_name}}_address_register_block').val(suggestion.data.block);
                $('#{{$subject_name}}_address_register_block').change();
                $('#{{$subject_name}}_address_register_flat').val(suggestion.data.flat);
                $('#{{$subject_name}}_address_register_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_register_fias_id').val(suggestion.data.fias_id);

                $('#{{$subject_name}}_address_register_flat').change();


                $('#{{$subject_name}}_address_fact').val($(this).val());
                $('#{{$subject_name}}_address_fact').change();

                $('#{{$subject_name}}_address_fact_kladr').val(suggestion.data.kladr_id);
                $('#{{$subject_name}}_address_fact_region').val(suggestion.data.region);
                $('#{{$subject_name}}_address_fact_okato').val(suggestion.data.okato);
                $('#{{$subject_name}}_address_fact_zip').val(suggestion.data.postal_code);

                $('#{{$subject_name}}_address_fact_city').val(suggestion.data.city);
                $('#{{$subject_name}}_address_fact_city_kladr_id').val(suggestion.data.city_kladr_id);
                $('#{{$subject_name}}_address_fact_street').val(suggestion.data.street_with_type);
                $('#{{$subject_name}}_address_fact_house').val(suggestion.data.house);
                $('#{{$subject_name}}_address_fact_house').change();
                $('#{{$subject_name}}_address_fact_block').val(suggestion.data.block);
                $('#{{$subject_name}}_address_fact_block').change();
                $('#{{$subject_name}}_address_fact_flat').val(suggestion.data.flat);

                $('#{{$subject_name}}_address_fact_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_fact_fias_id').val(suggestion.data.fias_id);

                $('#{{$subject_name}}_address_fact_flat').change();



            }
        });



        $('#{{$subject_name}}_address_fact').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');
                $('#{{$subject_name}}_address_fact').val($(this).val());
                $('#{{$subject_name}}_address_fact_kladr').val(suggestion.data.kladr_id);
                $('#{{$subject_name}}_address_fact_region').val(suggestion.data.region);
                $('#{{$subject_name}}_address_fact_okato').val(suggestion.data.okato);
                $('#{{$subject_name}}_address_fact_zip').val(suggestion.data.postal_code);

                $('#{{$subject_name}}_address_fact_city').val(suggestion.data.city);
                $('#{{$subject_name}}_address_fact_city_kladr_id').val(suggestion.data.city_kladr_id);
                $('#{{$subject_name}}_address_fact_street').val(suggestion.data.street_with_type);
                $('#{{$subject_name}}_address_fact_house').val(suggestion.data.house);
                $('#{{$subject_name}}_address_fact_house').change();
                $('#{{$subject_name}}_address_fact_block').val(suggestion.data.block);
                $('#{{$subject_name}}_address_fact_block').change();
                $('#{{$subject_name}}_address_fact_flat').val(suggestion.data.flat);

                $('#{{$subject_name}}_address_fact_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_fact_fias_id').val(suggestion.data.fias_id);

                $('#{{$subject_name}}_address_fact_flat').change();




            }
        });


        $('.phone').mask('+7 (999) 999-99-99');

        register_is_fact();

    }

    function register_is_fact()
    {
        if($('#address_register_is_fact').is(':checked')){
            $('.address_fact_form').hide();
        }else{
            $('.address_fact_form').show();
        }

    }


</script>

