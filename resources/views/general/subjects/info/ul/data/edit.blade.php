<div class="row col-sm-5">
    <label class="col-sm-12 control-label">Организационная форма</label>
    <div class="col-sm-12">
        {{ Form::select("of_id", \App\Models\Clients\GeneralUlOf::orderBy("full_title")->get()->pluck('full_title', 'code')->prepend('Не выбрано', 0), $general->data->of_id, ['class' => 'form-control select2-ws']) }}
    </div>
</div>


<div class="form-equally row col-sm-7">
    <label class="col-sm-12 control-label">Краткое название</label>
    <div class="col-sm-12">
        {{ Form::text("title", $general->title, ['class' => 'form-control party-autocomplete', 'id'=>'title']) }}
    </div>
</div>

<div class="clear"></div>

<div class="row col-sm-12">
    <label class="col-sm-12 control-label" style="max-width: none;">Полное название</label>
    <div class="col-sm-12">
        {{ Form::text("full_title", $general->data->full_title, ['class' => 'form-control']) }}
    </div>
</div>

<div class="clear"></div>

<div class="row col-sm-12">
    <label class="col-sm-12 control-label" style="max-width: none;">Полное наименование на ин.языке</label>
    <div class="col-sm-12">
        {{ Form::text("full_title_en", $general->data->full_title_en, ['class' => 'form-control']) }}
    </div>
</div>

<div class="clear"></div>

<div class="row col-sm-6">
    <label class="col-sm-12 control-label">Моб. телефон</label>
    <div class="col-sm-12">
        {{Form::text("phone", $general->phone, ['class' => 'form-control phone']) }}
    </div>
</div>

<div class="row form-equally col-sm-6">
    <label class="col-sm-12 control-label">E-mail</label>
    <div class="col-sm-12">
        {{Form::text("email", $general->email, ['class' => 'form-control']) }}
    </div>
</div>

<div class="clear"></div>

<div class="row col-sm-5">
    <label class="col-sm-12 control-label" style="max-width: none;">Форма собственности (ОКФС)</label>
    <div class="col-sm-12">
        {{ Form::select("ownership_id", collect(\App\Models\Clients\GeneralSubjectsUl::OWNERSHIP),$general->data->ownership_id, ['class' => 'form-control select2-ws']) }}
    </div>
</div>

<div class="col-sm-2">
    <label class="col-sm-12 control-label" style="max-width: none;">Резидент</label>
    <div class="col-sm-12">
        <input @if($general->is_resident == 1 || !$general->is_resident) checked="checked" @endif class="easyui-switchbutton is_resident" data-options="onText:'Да',offText:'Нет'" name="is_resident" id="is_resident" type="checkbox">
    </div>
</div>


<div class="form-equally col-sm-5 is_not_resident">
    <label class="col-sm-12 control-label">Гражданство</label>
    <div class="col-sm-12">
        {{ Form::select("citizenship_id", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'),$general->citizenship_id, ['class' => 'form-control select2-all', 'placeholder' => '']) }}
    </div>
</div>

<div class="clear"></div>


<div class="row col-sm-6 is_f_resident">
    <label class="col-sm-12 control-label" style="max-width: none;">ИНН</label>
    <div class="col-sm-12">
        {{ Form::text("inn", $general->data->inn, ['class' => 'form-control', 'id'=>'inn']) }}
    </div>
</div>

<div class="form-equally row col-sm-6 is_f_resident">
    <label class="col-sm-12 control-label" style="max-width: none;">КПП</label>
    <div class="col-sm-12">
        {{ Form::text("kpp", $general->data->kpp, ['class' => 'form-control']) }}
    </div>
</div>


<div class="clear"></div>

<div class="row col-sm-6 is_f_resident">
    <label class="col-sm-12 control-label" style="max-width: none;">ОГРН</label>
    <div class="col-sm-12">
        {{ Form::text("ogrn", $general->data->ogrn, ['class' => 'form-control', 'id'=>"ogrn"]) }}
    </div>
</div>


<div class="form-equally row col-sm-6 is_f_resident">
    <label class="col-sm-12 control-label" style="max-width: none;">Дата присвоения ОГРН</label>
    <div class="col-sm-12">
        {{ Form::text("date_orgn", setDateTimeFormatRu($general->data->date_orgn, 1), ['class' => 'form-control format-date']) }}
    </div>
</div>

<div class="clear"></div>

<div class="row col-sm-6 is_f_resident">
    <label class="col-sm-12 control-label" style="max-width: none;">Наименование рег. органа</label>
    <div class="col-sm-12">
        {{ Form::text("issued", $general->data->issued, ['class' => 'form-control']) }}
    </div>
</div>


<div class="form-equally row col-sm-6 is_f_resident">
    <label class="col-sm-12 control-label" style="max-width: none;">Место гос. регистрации</label>
    <div class="col-sm-12">
        {{ Form::text("place_registration", $general->data->place_registration, ['class' => 'form-control']) }}
    </div>
</div>


<div class="clear"></div>




<div class="row col-sm-6">
    <label class="col-sm-12 control-label">Банк</label>
    <div class="col-sm-12">
        {{Form::select("bank_id",  \App\Models\Settings\Bank::orderBy("title")->get()->pluck('title', 'id')->prepend('Выберите банк', 0), $general->data->bank_id, ['class' => 'form-control  select2-all']) }}
    </div>
</div>

<div class="form-equally col-sm-6">
    <label class="col-sm-12 control-label">БИК</label>
    <div class="col-sm-12">
        {{Form::text("bik", $general->data->bik, ['class' => 'form-control']) }}
    </div>
</div>

<div class="clear"></div>

<div class="row col-sm-6">
    <label class="col-sm-12 control-label">№ расчетного счета</label>
    <div class="col-sm-12">
        {{Form::text("rs", $general->data->rs, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-equally col-sm-6">
    <label class="col-sm-12 control-label">№ к/с</label>
    <div class="col-sm-12">
        {{Form::text("ks", $general->data->ks, ['class' => 'form-control']) }}
    </div>
</div>


<div class="clear"></div>


@include("general.subjects.info.address.edit", [
    'title_address' => 'Адрес регистрации',
    'name_address' => 'register',
    'address' => $general->getAddressType(1),
    'copy_to' => 'fact',
    'size' => '12',
])

@include("general.subjects.info.address.edit", [
    'title_address' => 'Адрес фактический',
    'name_address' => 'fact',
    'address' => $general->getAddressType(2),
    'size' => '12',
])

<div class="clear"></div>


<script src="/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

<script>
    
    function initDataSubjects() {

        viewCitizenship();

        $('#is_resident').switchbutton({
            onChange: function(checked){
                viewCitizenship();
            }
        });


        $('.address').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {

                key = $(this).data('key');
                setDataAddress(key, suggestion);

                copy = $(this).data('copy');
                if(copy.length > 0 && $('#'+copy+'_address').val().length == 0){
                    setDataAddress(copy, suggestion);
                }

            }
        });


        $(".party-autocomplete").suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "PARTY",
            count: 5,
            onSelect: function (suggestion) {
                var data = suggestion.data;

                $('#title').val(suggestion.value);
                $('#inn').val(data.inn);
                $('#ogrn').val(data.ogrn);


            }
        });

        formatDate();

    }


    function setDataAddress(key, suggestion) {
        $('#'+key+'_address').val(suggestion.value);
        $('#'+key+'_fias_code').val(suggestion.data.fias_code);
        $('#'+key+'_fias_id').val(suggestion.data.fias_id);
        $('#'+key+'_kladr').val(suggestion.data.kladr_id);
        $('#'+key+'_okato').val(suggestion.data.okato);
        $('#'+key+'_zip').val(suggestion.data.postal_code);
        $('#'+key+'_region').val(suggestion.data.region);
        $('#'+key+'_city').val(suggestion.data.city);
        $('#'+key+'_city_kladr_id').val(suggestion.data.city_kladr_id);
        $('#'+key+'_street').val(suggestion.data.street_with_type);
        $('#'+key+'_house').val(suggestion.data.house);
        $('#'+key+'_block').val(suggestion.data.block);
        $('#'+key+'_flat').val(suggestion.data.flat);
        return true;
    }

    function viewCitizenship() {

        if($('#is_resident').prop('checked')){
            $('.is_not_resident').hide();
            $('.is_f_resident').show();
        }else{
            $('.is_not_resident').show();
            $('.is_f_resident').hide();
        }

    }


</script>