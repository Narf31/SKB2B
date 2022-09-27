<div class="row form-horizontal">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" >

        <input type="hidden" name="contract[{{$subject_name}}][general_subject_id]" id="{{$subject_name}}_general_subject_id" value="{{$subject->general_subject_id}}"/>

        <div class="field form-col" @if($subject_name == 'insurer') data-intro='Поиск по конртагенту выглядит так <b>Иванов Иан Иванович 31.05.1989</b> данные подставятся автоматически' @endif>
            <div>
                <label class="control-label">
                    Фамилия имя отчество <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][fio]", $subject->get_info()->fio, ['class' => 'form-control valid_accept clear_offers', 'id'=>"{$subject_name}_fio", 'data-key'=>"{$subject_name}", 'placeholder' => 'Иванов Иван Иванович']) }}
            </div>
        </div>
    </div>

    @if(isset($is_lat) && $is_lat == 1)
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    ФИО лат. <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][fio_lat]", $subject->get_info()->fio_lat, ['class' => 'form-control clear_offers '.((isset($is_lat) && $is_lat == 1)?"valid_accept":""), 'id'=>"{$subject_name}_fio_lat", 'placeholder' => 'Ivanov Ivan Ivanovich']) }}
            </div>
        </div>
    </div>
    @endif


    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Пол <span class="required">*</span>
                </label>
                {{Form::select("contract[{$subject_name}][sex]", collect([0=>"муж.", 1=>'жен.']), $subject->get_info()->sex, ['class' => 'form-control clear_offers select2-ws valid_accept', 'id' => "{$subject_name}_sex", 'data-key'=>"{$subject_name}"]) }}
            </div>
        </div>
    </div>



    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Дата рождения <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][birthdate]", setDateTimeFormatRu($subject->get_info()->birthdate, 1), ['class' => 'form-control clear_offers valid_accept format-date ', 'id'=>"{$subject_name}_birthdate", 'placeholder' => '18.05.1976']) }}
                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
            </div>
        </div>
    </div>



    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Телефон <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][phone]", $subject->phone, ['class' => 'form-control clear_offers phone valid_accept valid_phone', 'placeholder' => '+7 (451) 653-13-54']) }}
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
        <div class="field form-col" @if($subject_name == 'insurer') data-intro='Email нужен для доступа в личный кабинет' @endif>
            <div>
                <label class="control-label">
                    Email <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][email]", $subject->email, ['class' => 'form-control valid_accept clear_offers valid_email', 'placeholder' => 'test@mail.ru']) }}
            </div>
        </div>
    </div>



    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-3" >
        <div class="field form-col" @if($subject_name == 'insurer') data-intro='Выберите город из списка' @endif>
            <div>
                <label class="control-label">
                    Место рождения <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][address_born]", $subject->get_info()->address_born, ['class' => 'form-control clear_offers valid_accept', 'id' => "{$subject_name}_address_born", 'placeholder' => '']) }}
                {{ Form::text("contract[{$subject_name}][address_born_kladr]", $subject->get_info()->address_born_kladr, ['class' => 'hidden not_valid', 'id' => "{$subject_name}_address_born_kladr", 'data-parent'=>"{$subject_name}_address_born"]) }}

                {{ Form::text("contract[{$subject_name}][address_born_fias_code]", $subject->get_info()->address_born_fias_code, ['class' => 'hidden not_valid', 'id' => "{$subject_name}_address_born_fias_code"]) }}
                {{ Form::text("contract[{$subject_name}][address_born_fias_id]", $subject->get_info()->address_born_fias_id, ['class' => 'hidden not_valid', 'id' => "{$subject_name}_address_born_fias_id"]) }}
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-1 col-lg-1" >
        <div class="field form-col">
            <div style="margin-bottom: 2px;">
                <label class="control-label">
                    Резидент
                </label>
                <br/>
                <input @if($subject->is_resident == 1 || !isset($subject->is_resident)) checked="checked" @endif class="easyui-switchbutton is_resident clear_offers" data-options="onText:'Да',offText:'Нет'" name="contract[{{$subject_name}}][is_resident]" id="{{$subject_name}}_is_resident" type="checkbox">

            </div>
        </div>
    </div>



    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
        <div class="field form-col" @if($subject_name == 'insurer') data-intro='Выберите полный адрес из списка' @endif>
            <div>
                <label class="control-label">
                    Адрес регистрации <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][address_register]",  $subject->get_info()->address_register, ['class' => 'form-control valid_accept clear_offers', 'id' => "{$subject_name}_address_register", 'placeholder' => '']) }}


                <input name="contract[{{$subject_name}}][address_register_fias_code]" id="{{$subject_name}}_address_register_fias_code" value="{{$subject->get_info()->address_register_fias_code}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_fias_id]" id="{{$subject_name}}_address_register_fias_id" value="{{$subject->get_info()->address_register_fias_id}}" type="hidden" class="not_valid"/>

                <input name="contract[{{$subject_name}}][address_register_kladr]" id="{{$subject_name}}_address_register_kladr" value="{{$subject->get_info()->address_register_kladr}}" type="hidden" data-parent="{{$subject_name}}_address_register" class="valid_accept"/>


                <input name="contract[{{$subject_name}}][address_register_region]" id="{{$subject_name}}_address_register_region" value="{{$subject->get_info()->address_register_region}}" type="hidden" class="not_valid"/>

                <input name="contract[{{$subject_name}}][address_register_city]" id="{{$subject_name}}_address_register_city" value="{{$subject->get_info()->address_register_city}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_city_kladr_id]" id="{{$subject_name}}_address_register_city_kladr_id" value="{{$subject->get_info()->address_register_city_kladr_id}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_street]" id="{{$subject_name}}_address_register_street" value="{{$subject->get_info()->address_register_street}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_house]" id="{{$subject_name}}_address_register_house" value="{{$subject->get_info()->address_register_house}}" type="hidden" data-parent="{{$subject_name}}_address_register" class="valid_accept"/>
                <input name="contract[{{$subject_name}}][address_register_block]" id="{{$subject_name}}_address_register_block" value="{{$subject->get_info()->address_register_block}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_flat]" id="{{$subject_name}}_address_register_flat" value="{{$subject->get_info()->address_register_flat}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_zip]" id="{{$subject_name}}_address_register_zip" value="{{$subject->get_info()->address_register_zip}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_register_okato]" id="{{$subject_name}}_address_register_okato" value="{{$subject->get_info()->address_register_okato}}" type="hidden" class="not_valid"/>

            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Адрес фактический <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][address_fact]", $subject->get_info()->address_fact, ['class' => 'form-control clear_offers valid_accept', 'id' => "{$subject_name}_address_fact", 'placeholder' => '']) }}


                <input name="contract[{{$subject_name}}][address_fact_fias_code]" id="{{$subject_name}}_address_fact_fias_code" value="{{$subject->get_info()->address_fact_fias_code}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_fias_id]" id="{{$subject_name}}_address_fact_fias_id" value="{{$subject->get_info()->address_fact_fias_id}}" type="hidden" class="not_valid"/>

                <input name="contract[{{$subject_name}}][address_fact_kladr]" id="{{$subject_name}}_address_fact_kladr" value="{{$subject->get_info()->address_fact_kladr}}" type="hidden" data-parent="{{$subject_name}}_address_fact" class="valid_accept"/>


                <input name="contract[{{$subject_name}}][address_fact_region]" id="{{$subject_name}}_address_fact_region" value="{{$subject->get_info()->address_fact_region}}" type="hidden" class="not_valid"/>

                <input name="contract[{{$subject_name}}][address_fact_city]" id="{{$subject_name}}_address_fact_city" value="{{$subject->get_info()->address_fact_city}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_city_kladr_id]" id="{{$subject_name}}_address_fact_city_kladr_id" value="{{$subject->get_info()->address_fact_city_kladr_id}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_street]" id="{{$subject_name}}_address_fact_street" value="{{$subject->get_info()->address_fact_street}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_house]" id="{{$subject_name}}_address_fact_house" value="{{$subject->get_info()->address_fact_house}}" type="hidden" data-parent="{{$subject_name}}_address_fact" class="valid_accept"/>
                <input name="contract[{{$subject_name}}][address_fact_block]" id="{{$subject_name}}_address_fact_block" value="{{$subject->get_info()->address_fact_block}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_flat]" id="{{$subject_name}}_address_fact_flat" value="{{$subject->get_info()->address_fact_flat}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_zip]" id="{{$subject_name}}_address_fact_zip" value="{{$subject->get_info()->address_fact_zip}}" type="hidden" class="not_valid"/>
                <input name="contract[{{$subject_name}}][address_fact_okato]" id="{{$subject_name}}_address_fact_okato" value="{{$subject->get_info()->address_fact_okato}}" type="hidden" class="not_valid"/>
            </div>
        </div>
    </div>


    <div class="clear"></div>





    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 {{$subject_name}}_is_not_resident" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Гражданство
                </label>
                {{ Form::select("contract[{$subject_name}][citizenship_id]", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'), ($subject->citizenship_id>0?$subject->citizenship_id:51), ['class' => 'form-control select2-all clear_offers', 'placeholder' => '']) }}
            </div>
        </div>
    </div>





    <div class="clear"></div>

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" >
        <label class="control-label">Тип документа</label>
        {{Form::select("contract[{$subject_name}][doc_type]", collect(\App\Models\Contracts\SubjectsFlDocType::getDocType()->pluck('title', 'isn')), ($subject->get_info()->doc_type?$subject->get_info()->doc_type:1165), ['class' => 'form-control select2-ws clear_offers', 'style'=>'width: 100%;'])}}
    </div>




    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Серия <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][doc_serie]", $subject->get_info()->doc_serie, ['class' => 'form-control to_up_letters valid_accept clear_offers', 'placeholder' => '1234']) }}
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Номер <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][doc_number]", $subject->get_info()->doc_number, ['class' => 'form-control to_up_letters valid_accept clear_offers', 'placeholder' => '567890']) }}
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Дата выдачи <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][doc_date]", setDateTimeFormatRu($subject->get_info()->doc_date, 1), ['class' => 'form-control valid_accept clear_offers format-date ', 'placeholder' => '12.05.2006']) }}
                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-2" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Код подразделения <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][doc_office]", $subject->get_info()->doc_office, ['class' => 'form-control valid_accept clear_offers', 'placeholder' => '567890', 'id' => "{$subject_name}_doc_office"]) }}
            </div>
        </div>
    </div>

    <div class="clear"></div>


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Кем выдан <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][doc_info]", $subject->get_info()->doc_info, ['class' => 'form-control valid_accept clear_offers', 'placeholder' => 'РУВД Москвы', 'id' => "{$subject_name}_doc_info"]) }}
            </div>
        </div>
    </div>

</div>


<script>



    function initStartSubject()
    {


        $('.clear_offers').change(function() {
            $('#offers').html('');
        });


        $('#{{$subject_name}}_fio').suggestions({
            serviceUrl: '{{url("/suggestions/dadata/")}}',
            token: "",
            type: "NAME",
            count: 5,
            formatResult: function(e, t, n, i) {
                var s = this;
                e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);

                if(n.data && parseInt(n.data.source) > 0){
                    e += '<br/><div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">' + n.data.document + '</span></div>';
                    e += '<br/><div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">' + n.data.address + '</span></div>';
                }else{
                    if(n.data.default_text){
                        e += '<br/><div class="' + s.classes.subtext + '"><span class="' + s.classes.subtext_inline + '">' + n.data.default_text + '</span></div>';
                    }
                }

                return e;
            },

            onSelect: function (suggestion) {
                $(this).val(suggestion.unrestricted_value);
                if(parseInt(suggestion.data.source) == -1){

                    loaderShow();
                    $.get("{{url("/contracts/online/".(int)$contract->id)}}/action/clear-general?name={{$subject_name}}&title="+suggestion.unrestricted_value, {}, function (response)  {
                        loaderHide();

                        if (Boolean(response.state) === true) {

                            flashMessage('success', "Данные успешно сохранены!");
                            $('#{{$subject_name}}_type').change();

                        }else {
                            flashHeaderMessage(response.msg, 'danger');
                        }


                    })
                    .done(function() {
                        loaderShow();
                    })
                    .fail(function() {
                        loaderHide();
                    })
                    .always(function() {
                        loaderHide();
                    });


                }
                if(parseInt(suggestion.data.source) > 0){

                    loaderShow();
                    $.get("{{url("/contracts/online/".(int)$contract->id)}}/action/clone-general?name={{$subject_name}}&document={{$general_document}}&general_id="+parseInt(suggestion.data.source), {}, function (response)  {
                        loaderHide();

                        if (Boolean(response.state) === true) {

                            flashMessage('success', "Данные успешно сохранены!");
                            $('#{{$subject_name}}_type').change();

                        }else {
                            flashHeaderMessage(response.msg, 'danger');
                        }


                    })
                    .done(function() {
                        loaderShow();
                    })
                    .fail(function() {
                        loaderHide();
                    })
                    .always(function() {
                        loaderHide();
                    });

                }
            }
        });



        $('#{{$subject_name}}_address_born').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');
                $('#{{$subject_name}}_address_born').val($(this).val());
                $('#{{$subject_name}}_address_born_kladr').val(suggestion.data.city_kladr_id);
                $('#{{$subject_name}}_address_born_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_born_fias_id').val(suggestion.data.fias_id);

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
                $('#{{$subject_name}}_address_register_block').val(suggestion.data.block);
                $('#{{$subject_name}}_address_register_flat').val(suggestion.data.flat);

                $('#{{$subject_name}}_address_register_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_register_fias_id').val(suggestion.data.fias_id);

                $('#{{$subject_name}}_address_fact').val($(this).val());
                $('#{{$subject_name}}_address_fact_kladr').val(suggestion.data.kladr_id);
                $('#{{$subject_name}}_address_fact_region').val(suggestion.data.region);
                $('#{{$subject_name}}_address_fact_okato').val(suggestion.data.okato);
                $('#{{$subject_name}}_address_fact_zip').val(suggestion.data.postal_code);

                $('#{{$subject_name}}_address_fact_city').val(suggestion.data.city);
                $('#{{$subject_name}}_address_fact_city_kladr_id').val(suggestion.data.city_kladr_id);
                $('#{{$subject_name}}_address_fact_street').val(suggestion.data.street_with_type);
                $('#{{$subject_name}}_address_fact_house').val(suggestion.data.house);
                $('#{{$subject_name}}_address_fact_block').val(suggestion.data.block);
                $('#{{$subject_name}}_address_fact_flat').val(suggestion.data.flat);

                $('#{{$subject_name}}_address_fact_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_fact_fias_id').val(suggestion.data.fias_id);

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
                $('#{{$subject_name}}_address_fact_city').val(suggestion.data.city);
                $('#{{$subject_name}}_address_fact_city_kladr_id').val(suggestion.data.city_kladr_id);
                $('#{{$subject_name}}_address_fact_street').val(suggestion.data.street_with_type);
                $('#{{$subject_name}}_address_fact_house').val(suggestion.data.house);
                $('#{{$subject_name}}_address_fact_block').val(suggestion.data.block);
                $('#{{$subject_name}}_address_fact_okato').val(suggestion.data.okato);
                $('#{{$subject_name}}_address_fact_zip').val(suggestion.data.postal_code);
                $('#{{$subject_name}}_address_fact_flat').val(suggestion.data.flat);

                $('#{{$subject_name}}_address_fact_fias_code').val(suggestion.data.fias_code);
                $('#{{$subject_name}}_address_fact_fias_id').val(suggestion.data.fias_id);

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');

            }
        });


        formatDate();
        $('.phone').mask('+7 (999) 999-99-99');

        $('.select2-ws').select2("destroy").select2({
            width: '100%',
            dropdownCssClass: "bigdrop",
            dropdownAutoWidth: true,
            minimumResultsForSearch: -1
        });

        if($('*').is('.select2-all')) {
            $('.select2-all').select2("destroy").select2({
                width: '100%',
                dropdownCssClass: "bigdrop",
                dropdownAutoWidth: true
            });
        }


        $('#{{$subject_name}}_is_resident').switchbutton({
            onChange: function(checked){
                viewCitizenship('{{$subject_name}}');
            }
        });

        viewCitizenship('{{$subject_name}}');

    }





</script>

