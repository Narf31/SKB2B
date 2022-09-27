<div data-step="{{$start_step}}" id="step-{{$start_step}}" class="calc__step fadeIn animated @if($active_step == $start_step) active @endif">
    <div class="calc__step-main">
        <div class="calc__step-title">

        </div>
        <div class="form__list">

            <div class="row row__custom">

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col__custom form__item">
                    <div class="form__field" style="margin-top: 10px;font-size: 18px;font-weight: bold;">
                        {{$subject_title}} физическое лицо
                    </div>
                    <input type="hidden" name="contract[{{$subject_name}}][type]" id="{{$subject_name}}_type" data-key="{{$subject_name}}" value="{{$subject->type}}"/>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col__custom form__item">

                    <div class="form__field">
                        <div class="form__label">Телефон</div>
                        {{ Form::text("contract[{$subject_name}][phone]", $subject->phone, ['class' => 'phone']) }}
                    </div>

                </div>
            </div>
            <br/>
            <br/>

            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
            <div class="row row__custom" id="control_form_{{$subject_name}}_main">
            </div>
        </div>
    </div>
    <div class="actions__wrap d-flex justify-content-center">
        @if($start_step!=1)
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__prev"></a>
        </div>
        @endif
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__next"></a>
        </div>
    </div>
</div>

<div></div>


<div data-step="{{$start_step+1}}" id="step-{{$start_step+1}}" class="calc__step fadeIn animated @if($active_step == ($start_step+1)) active @endif">
    <div class="calc__step-main">
        <div class="calc__step-title">
            Адрес регистрации
        </div>

        <div class="form__list">
            <div class="row row__custom" id="control_form_{{$subject_name}}_next">
            </div>
        </div>
    </div>

    <div class="actions__wrap d-flex justify-content-center">
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__prev"></a>
        </div>
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__next"></a>
        </div>
    </div>
</div>

@if($is_contact == true)
<div data-step="{{$start_step+2}}" id="step-{{$start_step+2}}" class="calc__step fadeIn animated @if($active_step == ($start_step+2)) active @endif">
    <div class="calc__step-main">
        <div class="calc__step-header">
            <div class="calc__step-title">
                Контактная информация
            </div>
            <div class="calc__step-text">
                Корректно заполненные поля для оформления договора
            </div>
        </div>
        <div class="row row__custom justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 col__custom">
                <div class="form__list">
                    <div class="row row__custom justify-content-center">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">
                            <div class="form__field">
                                <div class="form__label">Телефон</div>
                                {{ Form::text("contract[{$subject_name}][phone]", $subject->phone, ['class' => 'phone']) }}
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">
                            <div class="form__field">
                                <div class="form__label">Электронная почта</div>
                                {{ Form::text("contract[{$subject_name}][email]", $subject->email, ['class' => 'valid_accept']) }}
                                <div class="form__field-hint">
                                    На указанную почту мы вышлем вам договор
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">
                            <div class="checkbox__wrap checkbox__wrap-small">
                                <label>
                                    <input type="checkbox" checked>
                                    <div class="checkbox__decor"></div>
                                    <div class="checkbox__title">
                                        Согласен с правилами предоставления информации
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="actions__wrap d-flex justify-content-center">
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__prev"></a>
        </div>
        <div class="btn__item d-flex">
            <a href="#" class="btn__nav btn__next"></a>
        </div>
    </div>
</div>
@endif


<script>
    function initSubject_{{$subject_name}}()
    {
        $('#{{$subject_name}}_type').change(function () {

            loaderShow();
            $.get("{{urlClient("/contracts/online/action/".$contract->md5_token)}}/subject?name={{$subject_name}}&type="+parseInt($(this).val()), {}, function (response)  {
                loaderHide();
                $("#control_form_{{$subject_name}}_main").html(response.view_0);
                $("#control_form_{{$subject_name}}_next").html(response.view_1);


                activeInputForms();



                initStartSubject();
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


        });

        $('#{{$subject_name}}_type').change();



    }


    document.addEventListener("DOMContentLoaded", function (event) {
        initSubject_{{$subject_name}}();
        @if($subject_name != 'insurer')
            setTimeout('viewSubjectData_{{$subject_name}}()', 1000);
        @endif
    });


    function viewSubjectData_{{$subject_name}}()
    {
        if($('#{{$subject_name}}_is_insurer').prop('checked')){
            $("#main_control_form_{{$subject_name}}").hide();
        }else{
            $('#{{$subject_name}}_type').change();
            $("#main_control_form_{{$subject_name}}").show();
        }
    }


</script>

