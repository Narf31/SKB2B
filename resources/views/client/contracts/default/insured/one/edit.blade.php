<div data-step="{{$start_step}}" id="step-{{$start_step}}" class="calc__step fadeIn animated @if($active_step == $start_step) active @endif">
    <div class="calc__step-main">
        <div class="calc__step-title">
            Застрахованный
        </div>
        <div class="form__list">
            <div class="row row__custom">



                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col__custom form__item">
                    <div class="form__field">
                        {{ Form::text("contract[insurers][title]", $insurer->title, ['class' => 'valid_accept', 'id'=>"insurers_fio", 'data-key'=>"insurers"]) }}
                        <div class="form__label">ФИО <span class="required">*</span></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col__custom form__item">
                    <div class="form__field">
                        {{ Form::text("contract[insurers][birthdate]", setDateTimeFormatRu($insurer->birthdate, 1), ['class' => 'valid_accept format-date', 'id'=>"insurers_birthdate"]) }}

                        <div class="form__label">Дата рождения <span class="required">*</span></div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col__custom form__item">
                    <div class="form__field">
                        <div class="select__wrap">
                            {{Form::select("contract[insurers][sex]", collect([0=>"муж.", 1=>'жен.']), $insurer->sex, ['class' => '', 'id' => "insurers_sex", 'data-key'=>"insurers"]) }}
                        </div>
                    </div>
                </div>

                <div class="link__wrap d-flex">
                    <span onclick="isInsurer()" class="d-flex align-items-center link__icon " style="cursor: pointer;">
                        <i class="icon__add"></i>
                        <span>Страхователь</span>
                    </span>
                </div>




                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>


            </div>
        </div>
    </div>
    <div class="actions__wrap d-flex justify-content-center">
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__prev"></a>
        </div>
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__next" id="calc_butt"></a>
        </div>
    </div>
</div>



<script>


    function initStartInsureds(){

        $('#insurer_fio').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "NAME",
            count: 5,
            onSelect: function (suggestion) {

            }
        });
    }

    function isInsurer()
    {
        $('#insurers_fio').val($('#insurer_fio').val());
        $('#insurers_fio').change();
        $('#insurers_birthdate').val($('#insurer_birthdate').val());
        $('#insurers_birthdate').change();

        $('#insurers_sex').val($('#insurer_sex').val());
        $('.select__wrap select').trigger('refresh');

    }



</script>