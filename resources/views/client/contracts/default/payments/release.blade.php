<div data-step="{{$start_step}}" id="step-{{$start_step}}" class="calc__step fadeIn animated @if($active_step == $start_step) active @endif">
    <div class="calc__step-main">
        <div class="calc__step-header">
            <div class="calc__step-title">
                Оплата договора {{ $contract->product->title }}
            </div>
            <div class="calc__step-text">
                Выберите метод оплаты, после подтверждения договор придет на email
            </div>
        </div>


        <div class="row row__custom justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-6 col-xl-6 col-lg-6 col__custom" >
                <div class="form__list">
                    <div class="row row__custom justify-content-center">



                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">

                            <div class="calc__menu">
                                <ul>

                                    @foreach($payments_type as $key => $type)

                                    <li>

                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item">
                                            <div class="checkbox__wrap checkbox__wrap-small" style="padding-top: 20px;margin-bottom: -12px;">
                                                <label>
                                                    <input type="radio" name="payment_type" value="{{$key}}" @if($default_type == $key) checked @endif >
                                                    <div class="checkbox__decor"></div>
                                                    <div class="checkbox__title">
                                                        {{$type}}
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                    </li>

                                    @endforeach
                                </ul>
                            </div>
                        </div>


                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" id="promocode">
                            <div class="form__field">
                                <div class="form__label">Ведите промокод</div>
                                {{ Form::text("contract[payment][promocode]", '', ['class' => '', 'id'=>'pcode']) }}
                                <div class="form__field-hint" id="error_pcode" style="color: red;"></div>
                            </div>
                        </div>



                    </div>
                </div>





            </div>

            <div class="col-xs-12 col-sm-12 col-md-6 col-xl-6 col-lg-6 col__custom">

                <div class="form__list">
                    <div class="row row__custom justify-content-center">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" style="margin-top: 20px;margin-bottom: 20px;">
                            <div class="form__field">
                                <div class="form__label">Электронная почта</div>
                                {{ Form::text("contract[{$subject_name}][email]", (auth()->guard('client')->check())?auth()->guard('client')->user()->email:$subject->email, ['class' => 'valid_accept', 'id'=>'email']) }}
                                <div class="form__field-hint">
                                    На указанную почту мы вышлем вам договор
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" >
                            <div class="form__field">
                                <div class="checkbox__title">Сумма оплаты </div>
                                <div id="payment_total" class="calc__step-title" style="text-align: right;margin-top: -28px;margin-bottom: 10px;">
                                    {{ titleFloatFormat($contract->payment_total) }} руб.
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

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 col__custom form__item" >
                            <span onclick="acceptContract()" class="btn__small d-flex align-items-center justify-content-center">
                                Оформить договор
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
                if($(this).val() == 5){
                    $("#promocode").show();

                }else{
                    $("#promocode").hide();
                }
            }


        });

        $("input[name='payment_type']").change();



    }


    function acceptContract()
    {
        state = 0;
        payment_type = $("input[name='payment_type']:checked").val();

        if($("#email").val().length>0){

            if(payment_type == 5){
                if($("#pcode").val().length>3){
                    state = 1;
                }else{
                    $("#pcode").css('border-color', 'red');
                }
            }else{
                state = 1;
            }
        }else{
            $("#email").css('border-color', 'red');
        }

        if(state == 1){
            releaseContract();
        }

    }

    function setPromoError(msg)
    {
        $("#error_pcode").html(msg);
        $("#pcode").css('border-color', 'red');
    }


    document.addEventListener("DOMContentLoaded", function (event) {
        initPayment();

    });





</script>