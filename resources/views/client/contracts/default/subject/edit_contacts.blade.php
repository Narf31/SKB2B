<div data-step="{{$start_step}}" id="step-{{$start_step}}" class="calc__step fadeIn animated">
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
                                        Согласен на обработку персональных данных
                                    </div>
                                </label>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 col__custom">
                <div class="form__list">
                    <div class="row row__custom justify-content-center" style="margin-top: 15px;">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">

                            <div class="check__item" >
                                <label style="margin-bottom: 5px;">
                                    <input type="radio" name="payment_type" value="1" checked >
                                    <div class="check__btn d-flex align-items-center justify-content-center">
                                        Платежная страница
                                    </div>
                                </label>
                                <label style="margin-bottom: 5px;">
                                    <input type="radio" name="payment_type" value="2">
                                    <div class="check__btn d-flex align-items-center justify-content-center">
                                        Промо код
                                    </div>
                                </label>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" id="paymentsum" style="margin-top: -20px;">
                                <div class="form__field">
                                    <div class="form__label">К оплате </div>
                                    <div class="calc__step-title" style="text-align: right;padding-top: 10px;height: 44px;">
                                        {{ titleFloatFormat(1000) }} руб.
                                    </div>

                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" id="promocode" style="margin-top: -20px;">
                                <div class="form__field">
                                    <div class="form__label">Промо-код</div>
                                    {{ Form::text("contract[payment][promocode]", '', ['class' => '']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" style="margin-top: -20px;">
                            <span onclick="saveContractAndCalc(1)" class="btn__small d-flex align-items-center justify-content-center">
                                Офирмить договор
                            </span>
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

    </div>
</div>

<script>
    function initPayment()
    {

        $("input[name='payment_type']").change(function () {

           if($(this).prop("checked")){
               if($(this).val() == 2){
                   $("#promocode").show();
                   $("#paymentsum").hide();

               }else{
                   $("#promocode").hide();
                   $("#paymentsum").show();
               }
           }


        });

        $("input[name='payment_type']").change();



    }


    document.addEventListener("DOMContentLoaded", function (event) {
        initPayment();

    });





</script>