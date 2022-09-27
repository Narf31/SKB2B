@extends('client.layouts.app')

@section('head')

@append

@section('content')


    <div class="row row__custom justify-content-between">

        <div class="row col-xs-12 col-sm-12 col-md-6 col-lg-4 col__custom">



            <div class="reviews__item form__list col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form method="POST" action="{{ urlClient('/login') }}" id="authorizationForm">
                    {{ csrf_field() }}

                <div class="row row__custom">
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <div class="form__field" style="margin-top: 10px;margin-left: 5px;font-size: 18px;font-weight: bold;">
                            Авторизация
                        </div>
                    </div>
                </div>


                @if (isset($errors))
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger text-center">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $error }}</span>
                        </div>
                    @endforeach
                @endif

                @if (session('success') && !count($errors))
                    <div class="alert alert-success text-center">
                        <button class="close" data-close="alert"></button>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                <div class="row row__custom">

                    <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <div class="form__field">
                            <input type="text" class="" name="email" value="">
                            <div class="form__label">Email <span class="required">*</span></div>
                        </div>
                    </div>


                </div>

                <div class="row row__custom">


                    <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <div class="form__field">
                            <input type="password" class="" name="password" value="">
                            <div class="form__label">{{ trans('auth.login.password') }} <span class="required">*</span></div>
                            <div class="form__field-hint">
                                <a style="color: #01B49F !important;" href="{{urlClient("/registration")}}">Забыли пароль?</a>
                            </div>
                        </div>

                    </div>
                </div>
                <br/><br/>
                <div class="row row__custom">
                    <div class="row__custom col-xs-12 col-sm-12 col-md-12 col-lg-12 col__custom form__item">
                        <span onclick="$('#authorizationForm').submit();" class="btn__round d-flex align-items-center justify-content-center">
                            {{ trans('form.buttons.enter') }}
                        </span>
                    </div>
                </div>

                </form>
            </div>


        </div>


        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col__custom">
            <div class="card__box">
                <div class="custom__title seo__item">
                    О компании
                </div>
                <div class="descr">


                    <p><br><strong>АО «СГ «ПРЕСТИЖ-ПОЛИС»</strong> зарегистрировано (регистрационный № 3889) в качестве страховой организации. Имеет лицензии на проведение имущественного и личного страхования (СИ № 3889 и СЛ № 3889 от 08 февраля 2016 г. С<span>рок действия лицензий – без ограничения срока действия</span>).<br>Основными постоянными партнерами в осуществлении перестрахования являются крупнейшие участники российского страхового и перестраховочного рынка.</p>
                    <p><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Руководители и ведущие сотрудники <strong>АО «СГ «ПРЕСТИЖ-ПОЛИС»</strong> – это команда профессионалов, имеющих многолетний успешный опыт работы в ведущих страховых компаниях России и значимых государственных структурах. <br><br></p>


                </div>

                <hr>
            </div>
        </div>

    </div>



@endsection


