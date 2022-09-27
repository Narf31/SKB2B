<div class="row  form-horizontal">


    <div class="is_default_{{$subject_name}} row col-xs-12 col-sm-12 col-md-12 col-lg-6">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" @if($subject_name == 'insurer') data-intro='Выберите организацию из списка' @endif>
            <label class="control-label">Название компании<span class="required">*</span></label>
            {{ Form::text("contract[{$subject_name}][title]", $subject->title, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_title"]) }}
        </div>

        <input type="hidden" class="not_valid" name="contract[{{$subject_name}}][is_resident]" value="1"/>

        <input type="hidden" class="not_valid" name="contract[{{$subject_name}}][of][code]" value="{{$subject->get_info()->of_code}}" id="{{$subject_name}}_of_code"/>
        <input type="hidden" class="not_valid" name="contract[{{$subject_name}}][of][full_title]" value="{{$subject->get_info()->of_full_title}}" id="{{$subject_name}}_of_full_title"/>
        <input type="hidden" class="not_valid" name="contract[{{$subject_name}}][of][title]" value="{{$subject->get_info()->of_title}}" id="{{$subject_name}}_of_title"/>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">ИНН<span class="required">*</span></label>
            {{ Form::text("contract[{$subject_name}][inn]", $subject->inn, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_inn"]) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">ОГРН</label>
            {{ Form::text("contract[{$subject_name}][ogrn]", $subject->ogrn, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_ogrn"]) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">КПП</label>
            {{ Form::text("contract[{$subject_name}][kpp]", $subject->get_info()->kpp, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_kpp"]) }}
        </div>

        <div class="clear"></div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label class="control-label" style="max-width: none;">Полное наименование</label>
            {{ Form::text("contract[{$subject_name}][title_full]", $subject->get_info()->title_full, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_title_full"]) }}
        </div>

        <div class="clear"></div>




        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div class="field form-col" data-intro='Выберите полный адрес из списка'>
                <div>

                    @include("contracts.default.subject.partials.edit.default.address",
                    [
                        'subject_name' => $subject_name,
                        'ad_title' => 'Адрес регистрации',
                        'ad_name' => 'register',
                        'ad_data' => $subject->get_info()->toArray(),
                        'not_valid_accept' => true,
                    ])


                </div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <div class="field form-col">
                <div>

                    @include("contracts.default.subject.partials.edit.default.address",
                    [
                        'subject_name' => $subject_name,
                        'ad_title' => 'Адрес фактический',
                        'ad_name' => 'fact',
                        'ad_data' => $subject->get_info()->toArray(),
                        'not_valid_accept' => true,
                    ])

                </div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <label class="control-label">Моб. телефон</label>
            {{ Form::text("contract[{$subject_name}][phone]", $subject->phone, ['class' => 'form-control phone not_valid', 'placeholder' => '+7 (451) 653-13-54']) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <label class="control-label">Email</label>
            {{ Form::text("contract[{$subject_name}][email]", $subject->email, ['class' => 'form-control not_valid', 'placeholder' => 'test@mail.ru']) }}
        </div>

    </div>



    <div class="is_default_{{$subject_name}} col-xs-12 col-sm-12 col-md-12 col-lg-6">



        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8" >
            <label class="control-label">Банк</label>
            {{Form::select("contract[{$subject_name}][bank_id]",  \App\Models\Settings\Bank::orderBy("title")->get()->pluck('title', 'id')->prepend('Выберите банк', 0), $subject->get_info()->bank_id, ['class' => 'form-control not_valid select2-all']) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
            <label class="control-label">БИК</label>
            {{ Form::text("contract[{$subject_name}][bik]", $subject->get_info()->bik, ['class' => 'form-control not_valid']) }}
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <label class="control-label">№ расчетного счета</label>
            {{ Form::text("contract[{$subject_name}][rs]", $subject->get_info()->rs, ['class' => 'form-control not_valid']) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
            <label class="control-label">№ к/с</label>
            {{ Form::text("contract[{$subject_name}][ks]", $subject->get_info()->ks, ['class' => 'form-control not_valid']) }}
        </div>

        <div style="display: none;">

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">ОКПО</label>
                {{ Form::text("contract[{$subject_name}][okpo]", $subject->get_info()->okpo, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_okpo"]) }}
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">ОКТМО</label>
                {{ Form::text("contract[{$subject_name}][oktmo]", $subject->get_info()->oktmo, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_oktmo"]) }}
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">ОКФС</label>
                {{ Form::text("contract[{$subject_name}][okfs]", $subject->get_info()->okfs, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_okfs"]) }}
            </div>


            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">ОКАТО</label>
                {{ Form::text("contract[{$subject_name}][okato]", $subject->get_info()->okato, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_okato"]) }}
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">ОКОГУ</label>
                {{ Form::text("contract[{$subject_name}][okogy]", $subject->get_info()->okogy, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_okogy"]) }}
            </div>

            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4" >
                <label class="control-label">ОКВЭД код</label>
                {{ Form::text("contract[{$subject_name}][okved_code]", $subject->get_info()->okved_code, ['class' => 'form-control not_valid', 'id'=>"{$subject_name}_okved_code"]) }}
            </div>

        </div>



        @include("contracts.default.subject.partials.edit.manager", ['manager' => $subject->get_info()])


        <div class="clear"></div>



    </div>


    @if($subject_name == 'beneficiar')
        <div class="is_bank_{{$subject_name}}  col-xs-12 col-sm-12 col-md-6 col-lg-6">

            <label class="control-label">Банк </label>
            {{Form::select("contract[{$subject_name}][bank_general_subject_id]",  \App\Models\Clients\GeneralSubjects::where('person_category_id', '10')->where('status_work_id', '0')->orderBy("title")->get()->pluck('title', 'id')->prepend('Выберите банк', 0), $subject->general_subject_id, ['class' => 'form-control not_valid select2-all']) }}

        </div>

        <div class="is_bank_{{$subject_name}}  col-xs-12 col-sm-12 col-md-6 col-lg-6">

            <label class="control-label">Примечание</label>
            {{ Form::text("contract[{$subject_name}][bank_comments]", $subject->comments, ['class' => 'form-control not_valid']) }}

        </div>
    @endif


</div>




<script>



    function initStartSubject()
    {


        @if($subject_name == 'beneficiar')
        if($('*').is('#is_auto_credit')){
            if($("#is_auto_credit").val() == 1){
                $('.is_default_beneficiar').hide();
                $('.is_bank_beneficiar').show();
            }else{
                $('.is_bank_beneficiar').hide();
            }
        }

        @endif


        $('#{{$subject_name}}_title, #{{$subject_name}}_inn').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "NAME",
            type: "PARTY",
            count: 5,
            onSelect: function (suggestion) {
                var data = suggestion.data;

                $('#{{$subject_name}}_title').val(suggestion.value);
                $('#{{$subject_name}}_inn').val(data.inn);
                $('#{{$subject_name}}_ogrn').val(data.ogrn);
                $('#{{$subject_name}}_kpp').val(data.kpp);

                if(data.name){
                    $('#{{$subject_name}}_title_full').val(data.name.full_with_opf);
                }

                if(data.opf){
                    $('#{{$subject_name}}_of_code').val(data.opf.code);
                    $('#{{$subject_name}}_of_full_title').val(data.opf.full);
                    $('#{{$subject_name}}_of_title').val(data.opf.short);
                }


                if(data.address){
                    setSubjectAddress_{{$subject_name}}('register', data.address);
                    setSubjectAddress_{{$subject_name}}('fact', data.address);
                }


                $('#{{$subject_name}}_okpo').val(data.okpo);
                $('#{{$subject_name}}_oktmo').val(data.oktmo);
                $('#{{$subject_name}}_okfs').val(data.okfs);
                $('#{{$subject_name}}_okato').val(data.okato);
                $('#{{$subject_name}}_okogy').val(data.okogu);
                $('#{{$subject_name}}_okved_code').val(data.okved);

                if(data.management){
                    $('#{{$subject_name}}_manager_position').val(data.management.post);
                    $('#{{$subject_name}}_manager_fio').val(data.management.name);
                }

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');


                {{--
                loaderShow();

                $.post("{{url("/contracts/online/{$contract->id}/action/subject/search/ul/?name={$subject_name}")}}", {title:suggestion.value, inn:data.inn,
                    ogrn:data.ogrn}, function (response)  {

                    $('#{{$subject_name}}_type').change();


                }).always(function () {
                    loaderHide();

                });
                --}}

            }
        });



        $('#{{$subject_name}}_address_register, #{{$subject_name}}_address_fact').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');


                setSubjectAddress(key, suggestion);

                if(key == 'register'){
                    setSubjectAddress_{{$subject_name}}('fact', suggestion);
                }

                $('[name*="contract[{{$subject_name}}]"').removeClass('form-error');

            }
        });



        $('.general_manager_address').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('type');
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][address]"').val($(this).val());
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][kladr]"').val(suggestion.data.kladr_id);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][fias_code]"').val(suggestion.data.fias_code);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][fias_id]"').val(suggestion.data.fias_id);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][okato]"').val(suggestion.data.okato);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][zip]"').val(suggestion.data.postal_code);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][region]"').val(suggestion.data.region);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][city]"').val(suggestion.data.city);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][city_kladr_id]"').val(suggestion.data.city_kladr_id);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][street]"').val(suggestion.data.street_with_type);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][house]"').val(suggestion.data.house);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][block]"').val(suggestion.data.block);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+'][flat]"').val(suggestion.data.flat);
                $('[name*="contract[{{$subject_name}}][general][manager]['+key+']"').removeClass('form-error');
            }
        });


        $('.phone').mask('+7 (999) 999-99-99');

        initSelect2();
        formatDate();

    }


    function setSubjectAddress(key, suggestion) {



        $('#{{$subject_name}}_address_'+ key).val(suggestion.value);

        $('#{{$subject_name}}_'+ key +'_kladr').val(suggestion.data.kladr_id);
        $('#{{$subject_name}}_'+ key +'_okato').val(suggestion.data.okato);
        $('#{{$subject_name}}_'+ key +'_zip').val(suggestion.data.postal_code);
        $('#{{$subject_name}}_'+ key +'_region').val(suggestion.data.region);
        $('#{{$subject_name}}_'+ key +'_city').val(suggestion.data.city);
        $('#{{$subject_name}}_'+ key +'_city_kladr_id').val(suggestion.data.city_kladr_id);
        $('#{{$subject_name}}_'+ key +'_street').val(suggestion.data.street_with_type);
        $('#{{$subject_name}}_'+ key +'_house').val(suggestion.data.house);
        $('#{{$subject_name}}_'+ key +'_block').val(suggestion.data.block);
        $('#{{$subject_name}}_'+ key +'_flat').val(suggestion.data.flat);
        $('#{{$subject_name}}_'+ key +'_fias_code').val(suggestion.data.fias_code);
        $('#{{$subject_name}}_'+ key +'_fias_id').val(suggestion.data.fias_id);
        return true;
    }

    function setSubjectAddress_{{$subject_name}}(key, suggestion) {



        $('#{{$subject_name}}_address_'+ key).val(suggestion.value);

        $('#{{$subject_name}}_'+ key +'_kladr').val(suggestion.data.kladr_id);
        $('#{{$subject_name}}_'+ key +'_okato').val(suggestion.data.okato);
        $('#{{$subject_name}}_'+ key +'_zip').val(suggestion.data.postal_code);
        $('#{{$subject_name}}_'+ key +'_region').val(suggestion.data.region);
        $('#{{$subject_name}}_'+ key +'_city').val(suggestion.data.city);
        $('#{{$subject_name}}_'+ key +'_city_kladr_id').val(suggestion.data.city_kladr_id);
        $('#{{$subject_name}}_'+ key +'_street').val(suggestion.data.street_with_type);
        $('#{{$subject_name}}_'+ key +'_house').val(suggestion.data.house);
        $('#{{$subject_name}}_'+ key +'_block').val(suggestion.data.block);
        $('#{{$subject_name}}_'+ key +'_flat').val(suggestion.data.flat);
        $('#{{$subject_name}}_'+ key +'_fias_code').val(suggestion.data.fias_code);
        $('#{{$subject_name}}_'+ key +'_fias_id').val(suggestion.data.fias_id);
        return true;
    }



</script>




