<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][title]", $subject->get_info()->title, ['class' => '  valid_accept', 'id'=>"{$subject_name}_title"]) }}
        <div class="form__label">Название компании <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][inn]", $subject->get_info()->inn, ['class' => '  valid_accept', 'id'=>"{$subject_name}_inn"]) }}
        <div class="form__label">ИНН <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][kpp]", $subject->get_info()->kpp, ['class' => '  valid_accept', 'id'=>"{$subject_name}_kpp"]) }}
        <div class="form__label">КПП <span class="required">*</span></div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][general_manager]", $subject->get_info()->general_manager, ['class' => 'valid_accept', 'id'=>"{$subject_name}_general_manager"]) }}
        <div class="form__label">Генеральный директор <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][bik]", $subject->get_info()->bik, ['class' => '  valid_accept', 'id'=>"{$subject_name}_bik"]) }}
        <div class="form__label">БИК <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][ogrn]", $subject->get_info()->ogrn, ['class' => ' ', 'id'=>"{$subject_name}_ogrn"]) }}
        <div class="form__label">ОГРН <span class="required">*</span></div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
    <div class="form__field" style="margin-top: 10px;font-size: 18px;font-weight: bold;">
        Контактное лицо
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][fio]", $subject->get_info()->fio, ['class' => '  valid_accept', 'id'=>"{$subject_name}_fio", 'data-key'=>"{$subject_name}"]) }}
        <div class="form__label">ФИО <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][birthdate]", setDateTimeFormatRu($subject->get_info()->birthdate, 1), ['class' => '  format-date']) }}
        <div class="form__label">Дата рождения </div>
    </div>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col__custom form__item">
    <div class="form__field">
        {{Form::text("contract[{$subject_name}][position]", $subject->get_info()->position, ['class' => '']) }}
        <div class="form__label">Должность</div>
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


<script>



    function initStartSubject()
    {

        $('#{{$subject_name}}_fio, #{{$subject_name}}_general_manager').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "NAME",
            count: 5,
            onSelect: function (suggestion) {
                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');
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
                $('#{{$subject_name}}_address_fact_flat').change();

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');

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
                $('#{{$subject_name}}_address_fact_flat').change();

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');
            }
        });


        $('#{{$subject_name}}_title, #{{$subject_name}}_inn, #{{$subject_name}}_kpp').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "NAME",
            type: "PARTY",
            count: 5,
            onSelect: function (suggestion) {
                var data = suggestion.data;

                $('#{{$subject_name}}_title').val(suggestion.value);
                $('#{{$subject_name}}_title').change();
                $('#{{$subject_name}}_inn').val(data.inn);
                $('#{{$subject_name}}_inn').change();
                $('#{{$subject_name}}_kpp').val(data.kpp);
                $('#{{$subject_name}}_kpp').change();
                $('#{{$subject_name}}_ogrn').val(data.ogrn);
                $('#{{$subject_name}}_ogrn').change();

                if(data.management && data.management.name){
                    $('#{{$subject_name}}_general_manager').val(data.management.name);
                    $('#{{$subject_name}}_general_manager').change();
                }

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');

            }
        });


        $('.phone').mask('+7 (999) 999-99-99');
    }



</script>

