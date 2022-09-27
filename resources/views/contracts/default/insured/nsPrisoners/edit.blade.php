
<div class="page-heading" data-intro='Если застрахованный является страхователем нажмите для автоматической подстановки'>
    <h2 class="inline-h1">Застрахованный
        <i class="fa fa-user" style="font-size: 16px;cursor: pointer;color: rgb(234, 137, 58);" onclick="isInsurer()" ></i>
    </h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-md-12 col-lg-4" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    ФИО <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][title]", $insurer->title, ['class' => 'form-control valid_accept', 'id'=>"insurers_fio", 'data-key'=>"insurers", 'placeholder' => '']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Пол <span class="required">*</span>
                                </label>
                                {{Form::select("contract[insurers][sex]", collect([0=>"муж.", 1=>'жен.']), $insurer->sex, ['class' => 'form-control  select2-ws valid_accept', 'id' => "insurers_sex", 'data-key'=>"insurers"]) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата рождения <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][birthdate]", setDateTimeFormatRu($insurer->birthdate, 1), ['class' => 'form-control valid_accept format-date', 'id'=>"insurers_birthdate", 'placeholder' => '18.05.1976']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Телефон
                                </label>
                                {{ Form::text("contract[insurers][phone]", $insurer->phone, ['class' => 'form-control  phone', 'placeholder' => '+7 (451) 653-13-54']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Email
                                </label>
                                {{ Form::text("contract[insurers][email]", $insurer->email, ['class' => 'form-control', 'placeholder' => 'test@mail.ru']) }}
                            </div>
                        </div>
                    </div>


                    <div class="clear"></div>





                    <div class="col-md-12 col-lg-6" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Гражданство
                                </label>
                                {{ Form::select("contract[insurers][citizenship_id]", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'),($insurer->citizenship_id > 0?$insurer->citizenship_id:51), ['class' => 'form-control select2-all']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 col-lg-6" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Место рождения <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[ns_prisoners][address_born]", $contract->data->address_born, ['class' => 'form-control valid_accept', 'id' => "ns_prisoners_address_born", 'placeholder' => '']) }}
                                {{ Form::text("contract[ns_prisoners][address_born_kladr]", $contract->data->address_born_kladr, ['class' => 'hidden', 'id' => "ns_prisoners_address_born_kladr"]) }}

                                {{ Form::text("contract[ns_prisoners][address_born_fias_code]", $contract->data->address_born_fias_code, ['class' => 'hidden', 'id' => "ns_prisoners_address_born_fias_code"]) }}
                                {{ Form::text("contract[ns_prisoners][address_born_fias_id]", $contract->data->address_born_fias_id, ['class' => 'hidden', 'id' => "ns_prisoners_address_born_fias_id"]) }}

                            </div>
                        </div>
                    </div>


                    <div class="clear"></div>


                    <div class="col-md-4 col-lg-2" >
                        <label class="control-label">Тип документа</label>
                        {{Form::select("contract[insurers][doc_type]", collect(\App\Models\Contracts\ContractsInsurer::DOC_TYPE[0]), $insurer->doc_type, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="col-md-4 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Серия
                                </label>
                                {{ Form::text("contract[insurers][doc_serie]", $insurer->doc_serie, ['class' => 'form-control', 'placeholder' => '1234']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Номер
                                </label>
                                {{ Form::text("contract[insurers][doc_number]", $insurer->doc_number, ['class' => 'form-control', 'placeholder' => '567890']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата выдачи
                                </label>
                                {{ Form::text("contract[insurers][doc_date]", setDateTimeFormatRu($insurer->doc_date, 1), ['class' => 'form-control format-date ', 'placeholder' => '12.05.2006']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-4" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Кем выдан (Орган)
                                </label>
                                {{ Form::text("contract[insurers][doc_info]", $insurer->doc_info, ['class' => 'form-control', 'placeholder' => '', 'id' => "insurers_doc_info"]) }}
                            </div>
                        </div>
                    </div>


                    <div class="clear"></div>

                    <div class="col-md-12 col-lg-12" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label" style="width: 100%;max-width: none;">
                                    Инкриминируемое деяние (осужден) по следующим статьям Уголовного Кодекса РФ<span class="required">*</span>
                                </label>
                                {{ Form::text("contract[ns_prisoners][convicted_under_articles]", $contract->data->convicted_under_articles, ['class' => 'form-control valid_accept']) }}
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>


                    <div class="col-md-3 col-lg-3" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label" style="width: 100%;max-width: none;">
                                    Срок, на который осужден
                                </label>
                                {{ Form::text("contract[ns_prisoners][convicted_term]", $contract->data->convicted_term, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label" style="width: 100%;max-width: none;">
                                    Срок содержания Застрахованного лица<span class="required">*</span>
                                </label>
                                {{Form::select("contract[ns_prisoners][convicted_term_contract]", collect(\App\Models\Directories\Products\Data\NSPrisoners::CONVICTED_TERM_CONTRSCT), $contract->data->convicted_term_contract, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-6" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Адрес местонахождения <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[ns_prisoners][address_location]", $contract->data->address_location, ['class' => 'form-control valid_accept', 'id' => "ns_prisoners_address_location", 'placeholder' => '']) }}
                                {{ Form::text("contract[ns_prisoners][address_location_kladr]", $contract->data->address_location_kladr, ['class' => 'hidden', 'id' => "ns_prisoners_address_location_kladr"]) }}

                                {{ Form::text("contract[ns_prisoners][address_location_fias_code]", $contract->data->address_location_fias_code, ['class' => 'hidden', 'id' => "ns_prisoners_address_location_fias_code"]) }}
                                {{ Form::text("contract[ns_prisoners][address_location_fias_id]", $contract->data->address_location_fias_id, ['class' => 'hidden', 'id' => "ns_prisoners_address_location_fias_id"]) }}

                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <div class="col-md-12 col-lg-12" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label" style="width: 100%;max-width: none;">
                                    Наличие хронических заболеваний
                                </label>
                                <br/><br/>
                                <div class="form-equally col-md-2 col-lg-1" >
                                <input @if((int)$contract->data->is_chronic_diseases == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="contract[ns_prisoners][is_chronic_diseases]" id="ns_prisoners_is_chronic_diseases" type="checkbox">
                                </div>
                                <div class="form-equally col-md-10 col-lg-11 is_chronic_diseases">
                                    {{ Form::text("contract[ns_prisoners][chronic_diseases]", $contract->data->chronic_diseases, ['class' => 'form-control ', 'placeholder' => 'укажите имеющиеся заболевания']) }}
                                </div>

                            </div>
                        </div>
                    </div>



                    <div class="clear"></div>


                    <div class="col-md-12 col-lg-12" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label" style="width: 100%;max-width: none;">
                                    Наличие инвалидности
                                </label>
                                <br/><br/>
                                <div class="form-equally col-md-2 col-lg-1" >
                                    <input @if((int)$contract->data->is_disabilities == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="contract[ns_prisoners][is_disabilities]" id="ns_prisoners_is_disabilities" type="checkbox">
                                </div>
                                <div class="form-equally col-md-10 col-lg-11 is_disabilities">
                                    {{ Form::text("contract[ns_prisoners][disabilities]", $contract->data->disabilities, ['class' => 'form-control ', 'placeholder' => 'укажите группу инвалидности']) }}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


<script>


    function initStartInsureds(){

        $('#insurers_fio').suggestions({
            serviceUrl: '{{url("/suggestions/dadata/")}}',
            token: "",
            type: "NAME",
            count: 5,
            onSelect: function (suggestion) {

            }
        });


        $('#ns_prisoners_address_born').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');
                $('#ns_prisoners_address_born').val($(this).val());
                $('#ns_prisoners_address_born_kladr').val(suggestion.data.city_kladr_id);
                $('#ns_prisoners_address_born_fias_code').val(suggestion.data.fias_code);
                $('#ns_prisoners_address_born_fias_id').val(suggestion.data.fias_id);

            }
        });


        $('#ns_prisoners_address_location').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');
                $('#ns_prisoners_address_location').val($(this).val());
                $('#ns_prisoners_address_location_kladr').val(suggestion.data.city_kladr_id);
                $('#ns_prisoners_address_location_fias_code').val(suggestion.data.fias_code);
                $('#ns_prisoners_address_location_fias_id').val(suggestion.data.fias_id);

            }
        });

        viewChronicDiseases();
        $('#ns_prisoners_is_chronic_diseases').switchbutton({
            onChange: function(checked){
                viewChronicDiseases();
            }
        });

        viewDisabilities();
        $('#ns_prisoners_is_disabilities').switchbutton({
            onChange: function(checked){
                viewDisabilities();
            }
        });


    }

    function isInsurer()
    {
        $('#insurers_fio').val($('#insurer_fio').val());
        $('#insurers_sex').select2('val', $('#insurer_sex').val());
        $('#insurers_birthdate').val($('#insurer_birthdate').val());


        if($('[name="contract[insurers][doc_type]"]').val() == $('[name="contract[insurer][doc_type]"]').val()){
            $('[name="contract[insurers][doc_serie]"]').val($('[name="contract[insurer][doc_serie]"]').val());
            $('[name="contract[insurers][doc_number]"]').val($('[name="contract[insurer][doc_number]"]').val());
            $('[name="contract[insurers][doc_date]"]').val($('[name="contract[insurer][doc_date]"]').val());
            $('[name="contract[insurers][doc_info]"]').val($('[name="contract[insurer][doc_info]"]').val());
        }

        if($('#insurer_is_resident').prop('checked')){
            $('[name="contract[insurers][citizenship_id]"]').select2("val", 51);
        }else{
            $('[name="contract[insurers][citizenship_id]"]').select2("val", $('[name="contract[insurer][citizenship_id]"]').val());
        }


        $('[name="contract[insurers][phone]"]').val($('[name="contract[insurer][phone]"]').val());
        $('[name="contract[insurers][email]"]').val($('[name="contract[insurer][email]"]').val());



        $('[name="contract[ns_prisoners][address_born]"]').val($('[name="contract[insurer][address_born]').val());
        $('[name="contract[ns_prisoners][address_born_kladr]"]').val($('[name="contract[insurer][address_born_kladr]"]').val());
        $('[name="contract[ns_prisoners][address_born_fias_code]"]').val($('[name="contract[insurer][address_born_fias_code]').val());
        $('[name="contract[ns_prisoners][address_born_fias_id]"]').val($('[name="contract[insurer][address_born_fias_id]').val());


    }


    function viewChronicDiseases() {

        if($('#ns_prisoners_is_chronic_diseases').prop('checked')){
            $('.is_chronic_diseases').show();
        }else{
            $('.is_chronic_diseases').hide();

        }

    }

    function viewDisabilities() {

        if($('#ns_prisoners_is_disabilities').prop('checked')){
            $('.is_disabilities').show();
        }else{
            $('.is_disabilities').hide();

        }

    }






</script>