
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



                    <div class="col-md-6 col-lg-4" >
                        <div class="field form-col" data-intro='Выберите полный адрес из списка'>
                            <div>
                                <label class="control-label">
                                    Адрес регистрации <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[migrants][address_register]",  $contract->data->address_register, ['class' => 'form-control valid_accept', 'id' => "migrants_address_register", 'placeholder' => '']) }}


                                <input name="contract[migrants][address_register_fias_code]" id="migrants_address_register_fias_code" value="{{$contract->data->address_register_fias_code}}" type="hidden"/>
                                <input name="contract[migrants][address_register_fias_id]" id="migrants_address_register_fias_id" value="{{$contract->data->address_register_fias_id}}" type="hidden"/>

                                <input name="contract[migrants][address_register_kladr]" id="migrants_address_register_kladr" value="{{$contract->data->address_register_kladr}}" type="hidden"/>


                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата регистрации <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[migrants][date_register]", setDateTimeFormatRu($contract->data->date_register, 1), ['class' => 'form-control valid_accept format-date', 'placeholder' => '12.05.2006']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12 col-lg-6" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Гражданство
                                </label>
                                {{ Form::select("contract[insurers][citizenship_id]", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'),$insurer->citizenship_id, ['class' => 'form-control select2-all']) }}
                            </div>
                        </div>
                    </div>


                    <div class="clear"></div>


                    <div class="col-md-4 col-lg-2" >
                        <label class="control-label">Тип документа</label>
                        {{Form::select("contract[insurers][doc_type]", collect(\App\Models\Contracts\ContractsInsurer::DOC_TYPE[2]), $insurer->doc_type, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="col-md-4 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Серия <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][doc_serie]", $insurer->doc_serie, ['class' => 'form-control valid_accept', 'placeholder' => '1234']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Номер <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][doc_number]", $insurer->doc_number, ['class' => 'form-control valid_accept', 'placeholder' => '567890']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата выдачи <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][doc_date]", setDateTimeFormatRu($insurer->doc_date, 1), ['class' => 'form-control valid_accept format-date ', 'placeholder' => '12.05.2006']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-4" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Кем выдан (Орган) <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][doc_info]", $insurer->doc_info, ['class' => 'form-control valid_accept', 'placeholder' => '', 'id' => "insurers_doc_info"]) }}
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


    function initStartInsureds(){

        $('#insurers_fio').suggestions({
            serviceUrl: '{{url("/suggestions/dadata/")}}',
            token: "",
            type: "NAME",
            count: 5,
            onSelect: function (suggestion) {

            }
        });


        $('#migrants_address_register').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');
                $('#migrants_address_born').val($(this).val());
                $('#migrants_address_born_kladr').val(suggestion.data.city_kladr_id);
                $('#migrants_address_born_fias_code').val(suggestion.data.fias_code);
                $('#migrants_address_born_fias_id').val(suggestion.data.fias_id);

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

        $('[name="contract[insurers][citizenship_id]"]').select2("val", $('[name="contract[insurer][citizenship_id]"]').val());

        $('[name="contract[insurers][phone]"]').val($('[name="contract[insurer][phone]"]').val());
        $('[name="contract[insurers][email]"]').val($('[name="contract[insurer][email]"]').val());



        $('[name="contract[migrants][address_register]"]').val($('[name="contract[insurer][address_register]').val());
        $('[name="contract[migrants][address_register_fias_code]"]').val($('[name="contract[insurer][address_register_fias_code]"]').val());
        $('[name="contract[migrants][address_register_fias_id]"]').val($('[name="contract[insurer][address_register_fias_id]').val());
        $('[name="contract[migrants][address_register_kladr]"]').val($('[name="contract[insurer][address_register_kladr]').val());


    }



</script>