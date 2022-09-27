@extends('layouts.frame')


@section('title')

    {{$data->title}} - {{$data->birthdate}}

@stop

@section('content')


    {{ Form::open(['url' => url('/general/subjects/create'), 'method' => 'post', 'class' => 'row form-horizontal']) }}

    <input type="hidden" name="contract_id" value="{{$contract_id}}"/>
    <input type="hidden" name="type" value="{{$type}}"/>
    <input type="hidden" name="sex" value="{{$data->sex}}"/>
    <input type="hidden" name="birthdate" value="{{$data->birthdate}}"/>
    <input type="hidden" name="title" value="{{$data->title}}"/>


    <div class="row col-sm-4">
        <label class="col-sm-12 control-label">Пол</label>
        <div class="col-sm-12">
            {{Form::select("sex", collect([0=>"муж.", 1=>'жен.']), $data->sex, ['class' => 'form-control  select2-ws validate']) }}
        </div>
    </div>

    <div class="col-sm-4">
        <label class="col-sm-12 control-label">ИНН</label>
        <div class="col-sm-12">
            {{Form::text("inn", '', ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row form-equally col-sm-4">
        <label class="col-sm-12 control-label">СНИЛС</label>
        <div class="col-sm-12">
            {{Form::text("snils", '', ['class' => 'form-control snils']) }}
        </div>
    </div>
    <div class="clear"></div>

    <div class="row col-sm-6">
        <label class="col-sm-12 control-label">Моб. телефон</label>
        <div class="col-sm-12">
            {{Form::text("phone", '', ['class' => 'form-control phone']) }}
        </div>
    </div>

    <div class="row form-equally col-sm-6">
        <label class="col-sm-12 control-label">E-mail</label>
        <div class="col-sm-12">
            {{Form::text("email", '', ['class' => 'form-control']) }}
        </div>
    </div>


    <div class="clear"></div>

    @include("general.subjects.info.address.edit", [
        'title_address' => 'Место рождения',
        'name_address' => 'born',
        'address' => getAddressDefault(0),
        'copy_to' => 'register',
        'size' => '4',
    ])



    <div class="col-sm-2">
        <label class="col-sm-12 control-label">Резидент</label>
        <div class="col-sm-12">
            <input checked="checked" class="easyui-switchbutton is_resident" data-options="onText:'Да',offText:'Нет'" name="is_resident" id="is_resident" type="checkbox">
        </div>
    </div>

    <div class="col-sm-6 is_not_resident">
        <label class="col-sm-12 control-label">Гражданство</label>
        <div class="col-sm-12">
            {{ Form::select("citizenship_id", \App\Models\Settings\Country::orderBy('title')->get()->pluck('title', 'id'), 51, ['class' => 'form-control select2-all', 'placeholder' => '']) }}
        </div>
    </div>


    @include("general.subjects.info.address.edit", [
        'title_address' => 'Адрес регистрации',
        'name_address' => 'register',
        'address' => getAddressDefault(1),
        'copy_to' => 'fact',
        'size' => '12',
    ])

    @include("general.subjects.info.address.edit", [
        'title_address' => 'Адрес фактический',
        'name_address' => 'fact',
        'address' => getAddressDefault(2),
        'size' => '12',
    ])


    @include("general.subjects.info.documents.edit", [
        'docs' => collect(\App\Models\Contracts\SubjectsFlDocType::getDocType()->pluck('title', 'isn')),
        'index' => 0,
        '_pref' => '(Основной)',
        'is_main' => 1,
        'doc' => getDocumentsDefault(1165),
    ])


    {{Form::close()}}


@stop


@section('footer')

    <button onclick="createClients()" type="submit" class="btn btn-primary">{{ trans('form.buttons.create') }}</button>

@endsection


@section('js')


    <script src="/js/jquery.easyui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">

    <script>

        function createClients()
        {

            if(validate()){

                submitForm();

            }

        }


        $(function(){


            viewCitizenship();

            $('#is_resident').switchbutton({
                onChange: function(checked){
                    viewCitizenship();
                }
            });

            $('.snils').mask('999-999-999 99');
            $('.phone').mask('+7 (999) 999-99-99');


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




            formatDate();

        });

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
            }else{
                $('.is_not_resident').show();
            }

        }


        /**
         * Форматирование поля с датой
         **/
        function formatDate() {
            var configuration = {
                timepicker: false,
                format: 'd.m.Y',
                yearStart: 1900,
                scrollInput: false
            };



            $.datetimepicker.setLocale('ru');
            $('input.format-date').datetimepicker(configuration).keyup(function (event) {
                if (event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 38 && event.keyCode != 40) {
                    var pattern = new RegExp("[0-9.]{10}");
                    if (pattern.test($(this).val())) {
                        $(this).datetimepicker('hide');
                        $(this).datetimepicker('show');
                    }
                }
            });
            $('input.format-date').each(function () {
                var im = new Inputmask("99.99.9999", {
                    "oncomplete": function () {
                    }
                });
                im.mask($(this));
            });
        }



    </script>
    <script src="/js/online.js"></script>

@endsection