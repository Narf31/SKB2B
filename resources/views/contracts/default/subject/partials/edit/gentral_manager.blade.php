
<div class="row form-equally page-heading">
    <h2 class="inline-h1">{{$general_manager->podft->job_position}}</h2>
    <div class="clear"></div>
</div>
<br/>
<div class="row form-horizontal">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row form-horizontal">
            <div class="col-md-6 col-lg-4" >

                <input type="hidden" name="contract[{{$subject_name}}][general][manager][id]" value="{{$general_manager->id}}" class="not_valid"/>

                <div class="field form-col" @if($subject_name == 'insurer') data-intro='Поиск по конртагенту выглядит так <b>Иванов Иан Иванович 31.05.1989</b> данные подставятся автоматически' @endif>
                    <div>
                        <label class="control-label">
                            ФИО <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][fio]", $general_manager->title, ['class' => 'form-control valid_accept', 'id'=>"{$subject_name}_fio", 'data-key'=>"{$subject_name}", 'placeholder' => 'Иванов Иван Иванович']) }}
                    </div>
                </div>
            </div>


            <div class="col-md-3 col-lg-2" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Пол <span class="required">*</span>
                        </label>
                        {{Form::select("contract[{$subject_name}][general][manager][sex]", collect([0=>"муж.", 1=>'жен.']), ($general_manager->data)?$general_manager->data->sex:'', ['class' => 'form-control  select2-ws valid_accept', 'id' => "{$subject_name}_sex", 'data-key'=>"{$subject_name}"]) }}
                    </div>
                </div>
            </div>



            <div class="col-md-3 col-lg-2" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Дата рождения <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][birthdate]", setDateTimeFormatRu($general_manager->data->birthdate, 1), ['class' => 'form-control valid_accept format-date ', 'id'=>"{$subject_name}_birthdate", 'placeholder' => '18.05.1976']) }}
                        <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                    </div>
                </div>
            </div>


            @php
                $doc = $general_manager->getDocumentsType(1165);
            @endphp


            <div class="col-md-3 col-lg-2" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Телефон <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][phone]", $general_manager->phone, ['class' => 'form-control phone valid_accept', 'placeholder' => '+7 (451) 653-13-54']) }}
                    </div>
                </div>
            </div>


            <div class="col-md-3 col-lg-2" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Email <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][email]", $general_manager->email, ['class' => 'form-control valid_accept', 'placeholder' => 'test@mail.ru']) }}
                    </div>
                </div>
            </div>

            <div class="clear"></div>



            <div class="col-md-6 col-lg-4" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Гражданство
                        </label>
                        {{ Form::select("contract[{$subject_name}][general][manager][citizenship_id]", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'), ($general_manager->citizenship_id>0?$general_manager->citizenship_id:51), ['class' => 'form-control select2-all', 'placeholder' => '']) }}
                    </div>
                </div>
            </div>


            @php
                $register = $general_manager->getAddressType(1);
                $fact = $general_manager->getAddressType(2);
            @endphp

            <div class="col-md-6 col-lg-4" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Адрес регистрации <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][register][address]",  $register->address, ['class' => 'form-control general_manager_address valid_accept','data-type' => 'register', 'id' => "{$subject_name}_general_manager_register_address"]) }}


                        <input name="contract[{{$subject_name}}][general][manager][register][kladr]" value="{{$register->kladr}}" type="hidden" data-parent="{{$subject_name}}_general_manager_register_address" class="valid_accept"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][fias_code]" value="{{$register->fias_code}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][fias_id]" value="{{$register->fias_id}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][okato]" value="{{$register->okato}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][zip]" value="{{$register->zip}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][region]" value="{{$register->region}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][city]" value="{{$register->city}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][city_kladr_id]" value="{{$register->city_kladr_id}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][street]" value="{{$register->street}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][house]" value="{{$register->house}}" type="hidden"  data-parent="{{$subject_name}}_general_manager_register_address" class="valid_accept"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][block]" value="{{$register->block}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][register][flat]" value="{{$register->flat}}" type="hidden" class="not_valid"/>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Адрес фактический <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][fact][address]",  $fact->address, ['class' => 'form-control general_manager_address valid_accept','data-type' => 'fact', 'id' => "{$subject_name}_general_manager_fact_address"]) }}

                        <input name="contract[{{$subject_name}}][general][manager][fact][kladr]" value="{{$fact->kladr}}" type="hidden" data-parent="{{$subject_name}}_general_manager_fact_address" class="valid_accept"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][fias_code]" value="{{$fact->fias_code}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][fias_id]" value="{{$fact->fias_id}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][okato]" value="{{$fact->okato}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][zip]" value="{{$fact->zip}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][region]" value="{{$fact->region}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][city]" value="{{$fact->city}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][city_kladr_id]" value="{{$fact->city_kladr_id}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][street]" value="{{$fact->street}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][house]" value="{{$fact->house}}" type="hidden" data-parent="{{$subject_name}}_general_manager_fact_address" class="valid_accept"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][block]" value="{{$fact->block}}" type="hidden" class="not_valid"/>
                        <input name="contract[{{$subject_name}}][general][manager][fact][flat]" value="{{$fact->flat}}" type="hidden" class="not_valid"/>
                    </div>
                </div>
            </div>


            <div class="clear"></div>




            <div class="col-md-4 col-lg-4" >
                <label class="control-label">Тип документа</label>
                {{Form::select("contract[{$subject_name}][general][manager][doc_type]", collect(\App\Models\Contracts\SubjectsFlDocType::getDocType()->pluck('title', 'isn')), $doc->type_id?$doc->type_id : 1165, ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
            </div>




            <div class="col-md-4 col-lg-2" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Серия <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][serie]", $doc->serie, ['class' => 'form-control valid_accept', 'placeholder' => '1234']) }}
                    </div>
                </div>
            </div>


            <div class="col-md-4 col-lg-2" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Номер <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][number]", $doc->number, ['class' => 'form-control valid_accept', 'placeholder' => '567890']) }}
                    </div>
                </div>
            </div>


            <div class="col-md-4 col-lg-2 is_limit_payment_total" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Дата выдачи <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][doc_date]", setDateTimeFormatRu($doc->date_issue, 1), ['class' => 'form-control valid_accept format-date ', 'placeholder' => '12.05.2006']) }}
                        <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2 is_limit_payment_total" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Код подразделения <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][unit_code]", $doc->unit_code, ['class' => 'form-control valid_accept', 'placeholder' => '567890', 'id' => "{$subject_name}_doc_office"]) }}
                    </div>
                </div>
            </div>

            <div class="clear"></div>


            <div class="col-lg-12 is_limit_payment_total" >
                <div class="field form-col">
                    <div>
                        <label class="control-label">
                            Кем выдан <span class="required">*</span>
                        </label>
                        {{ Form::text("contract[{$subject_name}][general][manager][issued]", $doc->issued, ['class' => 'form-control valid_accept', 'placeholder' => 'РУВД Москвы', 'id' => "{$subject_name}_doc_info"]) }}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="clear"></div>
</div>




