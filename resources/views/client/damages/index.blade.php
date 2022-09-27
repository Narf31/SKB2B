@extends('client.layouts.app')

@section('head')

@append

@section('content')




    <div class="row row__custom justify-content-between">

        <div class="row col-xs-12 col-sm-12 col-md-8 col-xl-8 col-lg-8 col__custom">

            <div class="row content__box-title seo__item col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12">
                Убытки
            </div>

            @if(auth()->guard('client')->check())
                <div class="row text col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12">
                    @include("client.damages.orders.index", ['client' => auth()->guard('client')->user()] )
                </div>
            @else

            <div class="row text col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12">

                <br/><br/><br/><br/>
                <p style="font-size: 18px;">
                    Для подачи заявки онлайн необходимо <br/><br/>
                    <a class="btn__round d-flex align-items-center justify-content-center" href="{{urlClient("/login")}}">авторизоватся</a>
                </p>
            </div>
            @endif



            <div>
                <div>
                    <hr/>
                    <div class="row col-xs-12 col-sm-12 col-md-12 col-xl-12 col-lg-12 col__custom">
                        <div class="row col-xs-12 col-sm-12 col-md-12 col-xl-9 col-lg-9 text-center text-sm-left">
                            <h2>Наше приложения VIP-POLIS</h2>
                            <p>Вы можете оформить убыток через мобильное приложение</p>
                            <img src="/assets/client/img/landing/google-app.png" alt=""  style="height: 50px;">
                            <img src="/assets/client/img/landing/apple-app.png" alt="" style="height: 50px;padding-left: 20px;">

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-xl-3 col-lg-3 flex-first flex-md-last" style="padding-top: 10px;">
                            <img src="/assets/client/img/landing/block-app.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <div class="col-xs-12 col-sm-12 col-md-4 col-xl-4 col-lg-4 col__custom">
            <div class="content__box-right" style="width: 100%;padding-bottom: 20px;">
                <div class="content__box-title seo__item">
                    Порядок действий
                </div>
                <div class="calc__menu">
                    <ul>
                        @foreach($products as $product)
                            <li>
                                <a href="{{urlClient("/damages/product/{$product->id}")}}" class="info-menu" style="cursor: pointer;" >{{$product->title}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>



    </div>

@endsection


