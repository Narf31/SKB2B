<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom" style="height: 300px;">



    <div class="reviews__item form__list col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="row row__custom">
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                    <div class="form__field" style="margin-top: 10px;margin-left: 5px;font-size: 18px;font-weight: bold;">
                        Смена пароля
                    </div>
                </div>
            </div>

        <div class="alert alert-danger text-center" id="errors-text" style="display: none;">
        </div>

        <div class="alert alert-success text-center" id="success-text" style="display: none;">
            asd
        </div>
            <br/><br/>



            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

            <div class="row row__custom elements">

                <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                    <div class="form__field">
                        <input type="password" id="password" value="">
                        <div class="form__label">Текущий пароль <span class="required">*</span></div>
                    </div>

                </div>
            </div>

            <div class="row row__custom elements">

                <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                    <div class="form__field">
                        <input type="password" id="passwor_new" value="">
                        <div class="form__label">Новый пароль <span class="required">*</span></div>
                    </div>

                </div>
            </div>

            <div class="row row__custom elements">

                <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                    <div class="form__field">
                        <input type="password" id="passwor_new_duble" value="">
                        <div class="form__label">Повторите пароль <span class="required">*</span></div>
                    </div>

                </div>
            </div>

            <br/><br/>
            <div class="row row__custom elements">
                <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <span onclick="changePass()" class="btn__round d-flex align-items-center justify-content-center">
                            Сменить пароль
                        </span>
                </div>
            </div>

    </div>


</div>

<script>


    function changePass() {


        loaderShow();

        $.post('{{urlClient("/change-pass")}}', {password:$("#password").val(), passwor_new:$("#passwor_new").val(), passwor_new_duble:$("#passwor_new_duble").val()}, function (response) {


            if(response.state == 0){
                setError(response.msg);
            }else{
                setSuccess(response.msg);
            }

        }).always(function () {
            loaderHide();
        });

    }

    function setError(msg) {
        $("#errors-text").html(msg);
        $("#errors-text").show();
    }

    function setSuccess(msg) {
        $("#errors-text").hide();
        $(".elements").hide();
        $("#success-text").show();
        $("#success-text").html(msg);

    }


</script>